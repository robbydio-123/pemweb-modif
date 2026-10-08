<?php
try {
    $databaseUrl = getenv("DATABASE_URL");
    $endpoint = null;
    if ($databaseUrl !== false && $databaseUrl !== "") {
        $parts = parse_url($databaseUrl);
        if (
            $parts === false ||
            !isset(
                $parts["scheme"],
                $parts["host"],
                $parts["user"],
                $parts["pass"],
                $parts["path"]
            ) ||
            !in_array($parts["scheme"], ["postgres", "postgresql"], true)
        ) {
            throw new RuntimeException("DATABASE_URL bukan URL PostgreSQL yang valid.");
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

        $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=$sslmode";
        if ($endpoint !== null) {
            $dsn .= ";options=endpoint=$endpoint";
        }

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    } else {
        $host = getenv("DB_HOST") ?: "127.0.0.1";
        $port = getenv("DB_PORT") ?: "5432";
        $db = getenv("DB_NAME") ?: "rental_motor";
        $user = getenv("DB_USER") ?: "postgres";
        $pass = getenv("DB_PASSWORD");
        $pass = $pass === false ? "" : $pass;
        $sslmode = getenv("DB_SSLMODE") ?: "prefer";

        $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=$sslmode";

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $pe) {
            // Coba alternatif password umum untuk localhost (postgres atau kosong)
            $altPass = ($pass === "") ? "postgres" : "";
            try {
                $pdo = new PDO($dsn, $user, $altPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } catch (PDOException $pe2) {
                throw $pe;
            }
        }
    }
} catch (PDOException | RuntimeException $e) {
    error_log("Koneksi PostgreSQL gagal: " . $e->getMessage());
    http_response_code(500);
    die("Koneksi database gagal: " . htmlspecialchars($e->getMessage()));
}
