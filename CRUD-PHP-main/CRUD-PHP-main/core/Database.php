<?php

class Database
{
    private static ?PDO $shared = null;
    public PDO $pdo;

    public function __construct()
    {
        $this->pdo = self::get();
    }

    public static function get(): PDO
    {
        if (self::$shared instanceof PDO) {
            return self::$shared;
        }

        $cfg    = require __DIR__ . '/../config/cfg.php';
        $driver = strtolower($cfg['driver'] ?? 'mysql');

        try {
            if ($driver === 'mysql') {
                $host = $cfg['host'] ?? 'localhost';
                $port = (int)($cfg['port'] ?? 3306);
                $dbname = $cfg['dbname'] ?? 'trabalho';
                $user = $cfg['user'] ?? 'root';
                $pass = $cfg['pass'] ?? '';


                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

                self::$shared = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                ]);

            } elseif ($driver === 'sqlite') {

                $path = $cfg['sqlite_path'] ?? ($cfg['sqplite_path'] ?? (__DIR__ . '/../database/trabalho.db'));
                $dsn  = "sqlite:" . $path;

                self::$shared = new PDO($dsn, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);


                self::$shared->exec("PRAGMA foreign_keys = ON;");
            } else {
                throw new RuntimeException("Driver de banco não suportado: {$driver}");
            }
        } catch (Throwable $e) {

            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'DB connection failed', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
            exit;
        }

        return self::$shared;
    }
}
