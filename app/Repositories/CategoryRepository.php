<?php
namespace App\Repositories;
use App\Core\Database;
use PDO;

class CategoryRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function getAllCategory(): array {
        $categories  =  $this->db->query("
                SELECT * FROM  categories as c ORDER BY c.id DESC
            "
        );
        return $categories->fetchAll();
    }
}