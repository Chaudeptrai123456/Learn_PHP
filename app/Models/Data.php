<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $conn;

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
            error_log("Database Connection Error: " . $e->getMessage());
            die("<h1>Hệ thống đang bảo trì. Vui lòng quay lại sau.</h1>");
        }
    }

    // Phương thức tĩnh để lấy instance duy nhất
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    private function __clone() {}

    // Ngăn chặn việc unserialize object
    public function __wakeup() {
        throw new \Exception("Cannot unserialize a singleton.");
    }
}