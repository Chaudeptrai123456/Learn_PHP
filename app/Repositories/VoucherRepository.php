<?php
namespace App\Repositories;

use App\Core\Database;
use PDO;


class VoucherRepository { 
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function getAllVoucher() {
        $sql = "SELECT * FROM vouchers ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

}