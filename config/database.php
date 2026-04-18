<?php
// Sesuaikan dengan environment variables di docker-compose.yml
define('DB_HOST', 'db'); // BUKAN 'localhost', gunakan nama service docker-nya
define('DB_NAME', 'vibeflow_db');
define('DB_USER', 'vibeuser'); 
define('DB_PASS', 'vibepassword');

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                self::$connection = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("Database Connection Error: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
