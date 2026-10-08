<?php
try {
    $databaseUrl = getenv("DATABASE_URL");
    if ($databaseUrl !== false && $databaseUrl !== "") {
        $parts = parse_url($databaseUrl);
        if (
            $parts === false ||
            !isset(
                $parts["scheme"],
                $parts["host"],
                $parts["user"],
                $parts["pass"],
                $parts["path"],
            ) ||
            !in_array($parts["scheme"], ["postgres", "postgresql"], true)
        ) {
            throw new RuntimeException(
                "DATABASE_URL bukan URL PostgreSQL yang valid.",
            );
        }

        $query = [];
        if (isset($parts["query"])) {
            parse_str($parts["query"], $query);
        }

        $host = $parts["host"];
        $port = $parts["port"] ?? 5432;
        $db = rawurldecode(ltrim($parts["path"], "/"));
        $user = rawurldecode($parts["user"]);
        $pass = rawurldecode($parts["pass"]);
        $sslmode = $query["sslmode"] ?? (getenv("DB_SSLMODE") ?: "require");
        $endpoint = null;
        if (preg_match("/^(ep-[^.]+)\./", $host, $matches)) {
            $endpoint = $matches[1];
        }
    } else {
        $host = getenv("DB_HOST") ?: "127.0.0.1";
        $port = getenv("DB_PORT") ?: "5432";
        $db = getenv("DB_NAME") ?: "rental_motor";
        $user = getenv("DB_USER") ?: "postgres";
        $pass = getenv("DB_PASSWORD");
        $sslmode = getenv("DB_SSLMODE") ?: "prefer";

        if ($pass === false || $pass === "") {
            throw new RuntimeException(
                "Variabel DATABASE_URL atau DB_PASSWORD belum diatur.",
            );
        }
    }

    if ($db === "" || $user === "" || $pass === "") {
        throw new RuntimeException("Kredensial database tidak lengkap.");
    }

    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=$sslmode";
    if ($endpoint !== null) {
        $dsn .= ";options=endpoint=$endpoint";
    }

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException | RuntimeException $e) {
    error_log("Koneksi PostgreSQL gagal: " . $e->getMessage());
    http_response_code(500);
    die(
        "Koneksi database gagal. Periksa DATABASE_URL atau konfigurasi variabel DB_*."
    );
}