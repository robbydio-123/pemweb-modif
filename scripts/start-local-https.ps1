param(
    [string]$DocumentRoot = "JS 10",
    [int]$PhpPort = 8000,
    [int]$HttpsPort = 8080,
    [string]$StartPath = "/index.php"
)

$ErrorActionPreference = "Stop"

$phpPath = "C:\xampp\php\php.exe"
$nodePath = "C:\Program Files\nodejs\node.exe"
$workspace = (Get-Location).Path
$documentRootPath = Join-Path $workspace $DocumentRoot
$phpServer = $null
$certificatePassword = [guid]::NewGuid().ToString("N")
$securePassword = ConvertTo-SecureString $certificatePassword -AsPlainText -Force
$certificateName = "Rental Motor Local HTTPS"
$certificateDirectory = Join-Path $env:LOCALAPPDATA "RentalMotorLocalHttps"
$pfxPath = Join-Path $certificateDirectory "localhost.pfx"

if (-not (Test-Path $phpPath)) {
    throw "PHP CLI was not found at $phpPath."
}
if (-not (Test-Path $nodePath)) {
    throw "Node.js was not found at $nodePath."
}
if (-not (Test-Path $documentRootPath -PathType Container)) {
    throw "Document root was not found at $documentRootPath."
}

New-Item -ItemType Directory -Path $certificateDirectory -Force | Out-Null

$certificate = Get-ChildItem Cert:\CurrentUser\My |
    Where-Object {
        $_.FriendlyName -eq $certificateName -and
        $_.NotAfter -gt (Get-Date) -and
        $_.HasPrivateKey
    } |
    Sort-Object NotAfter -Descending |
    Select-Object -First 1

if (-not $certificate) {
    $certificate = New-SelfSignedCertificate `
        -DnsName "localhost" `
        -CertStoreLocation Cert:\CurrentUser\My `
        -FriendlyName $certificateName `
        -KeyAlgorithm RSA `
        -KeyLength 2048 `
        -HashAlgorithm SHA256 `
        -KeyExportPolicy Exportable `
        -NotAfter (Get-Date).AddYears(2)
}

$trustedCertificate = Get-ChildItem Cert:\CurrentUser\Root |
    Where-Object { $_.Thumbprint -eq $certificate.Thumbprint } |
    Select-Object -First 1

if (-not $trustedCertificate) {
    $cerPath = Join-Path $certificateDirectory "localhost.cer"
    try {
        Export-Certificate -Cert $certificate -FilePath $cerPath -Force | Out-Null
        Import-Certificate -FilePath $cerPath -CertStoreLocation Cert:\CurrentUser\Root | Out-Null
    }
    finally {
        Remove-Item $cerPath -Force -ErrorAction SilentlyContinue
    }
}

$phpListener = Get-NetTCPConnection -State Listen -LocalPort $PhpPort -ErrorAction SilentlyContinue |
    Where-Object { $_.LocalAddress -in @("127.0.0.1", "0.0.0.0", "::1", "::") } |
    Select-Object -First 1

if (-not $phpListener) {
    $arguments = '-d extension=php_pdo_pgsql.dll -S 127.0.0.1:{0} -t "{1}"' -f $PhpPort, $documentRootPath
    $phpServer = Start-Process `
        -FilePath $phpPath `
        -ArgumentList $arguments `
        -WorkingDirectory $workspace `
        -PassThru `
        -WindowStyle Hidden

    for ($attempt = 0; $attempt -lt 40; $attempt++) {
        Start-Sleep -Milliseconds 250
        if ($phpServer.HasExited) {
            throw "PHP server stopped before it was ready (exit code $($phpServer.ExitCode))."
        }
        $phpListener = Get-NetTCPConnection -State Listen -LocalPort $PhpPort -ErrorAction SilentlyContinue |
            Where-Object { $_.LocalAddress -in @("127.0.0.1", "0.0.0.0", "::1", "::") } |
            Select-Object -First 1
        if ($phpListener) {
            break
        }
    }

    if (-not $phpListener) {
        throw "PHP server did not start listening on 127.0.0.1:$PhpPort."
    }
}

try {
    Export-PfxCertificate `
        -Cert $certificate `
        -FilePath $pfxPath `
        -Password $securePassword `
        -Force | Out-Null

    $env:LOCAL_HTTPS_PFX = $pfxPath
    $env:LOCAL_HTTPS_PASSPHRASE = $certificatePassword
    $env:LOCAL_HTTPS_HTTP_PORT = "$PhpPort"
    $env:LOCAL_HTTPS_PORT = "$HttpsPort"
    $env:LOCAL_HTTPS_START_PATH = $StartPath
    Write-Output "Starting Rental Motor HTTPS proxy on port $HttpsPort."
    & $nodePath (Join-Path $workspace "scripts\local-https-proxy.js")
    if ($LASTEXITCODE -ne 0) {
        throw "HTTPS proxy exited with code $LASTEXITCODE."
    }
}
finally {
    Remove-Item $pfxPath -Force -ErrorAction SilentlyContinue
    if ($phpServer -and -not $phpServer.HasExited) {
        Stop-Process -Id $phpServer.Id -Force
    }
}
