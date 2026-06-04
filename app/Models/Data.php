<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?Database $instance = null;
    private PDO $conn;

    private function __construct() {
        $dsn = "mysql:host=" . Config::DB_HOST . 
               ";port=" . Config::DB_PORT . 
               ";dbname=" . Config::DB_NAME . 
               ";charset=" . Config::DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
            PDO::ATTR_EMULATE_PREPARES   => false,                   
        ];

        try {
            $this->conn = new PDO($dsn, Config::DB_USER, Config::DB_PASS, $options);
        } catch (PDOException $e) {
            error_log($e->getMessage());        
            throw new \Exception("Database connection failed");
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->conn;
    }

    private function __clone() {}

    public function __wakeup(): void {
        throw new \Exception("Cannot unserialize a singleton.");
    }
}