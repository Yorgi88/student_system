<?php

class Database {
    private static ?PDO $instance = null;

    private function __construct(){}

    public static function getConnection(): PDO{
        if(self::$instance === null){
            //load config
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $dbname = $_ENV['DB_NAME'] ?? 'student_system';
            $username = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASS'] ?? '';

            try {
                //code...
                $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                //throw $e;
                // error_log("Database connection failed: " . $e->getMessage());
                die("Database connection failed. Please try again later." . 
                $e->getMessage());
            }

        }
        return self::$instance;
    }

    private function __clone(){}
    public function __wakeup(){}
}


?>