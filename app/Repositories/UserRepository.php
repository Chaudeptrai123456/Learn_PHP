<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\User;
use PDO;

class UserRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
public function checkLogin($username, $password) {
    $sql = "SELECT * FROM users 
            WHERE email = :username OR phone = :username 
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'username' => $username
    ]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        return null;
    }
    if (!password_verify($password, $data['password'])) {
        return null;
    }
    if (isset($data['status']) && $data['status'] == 0) {
        return null;
    }
    return new User($data);
}
public function findByEmailOrPhone($email) {
    $sql = "SELECT * FROM users 
            WHERE email = :email
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'email' => $email
    ]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) return null;

    return new User($data);
}
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'email' => $email
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        return new User($data);
    }
public function existsByEmail($email) {
    $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'email' => $email
    ]);

    return $stmt->fetchColumn() > 0;
}
    public function create(User $user) {
        $sql = "INSERT INTO users 
                (email, name, avatar_url, password, provider, provider_id, role, status)
                VALUES 
                (:email, :name, :avatar_url, :password, :provider, :provider_id, :role, :status)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'email'        => $user->email,
            'name'         => $user->name,
            'avatar_url'   => $user->avatar_url,
            'password'     => $user->password,
            'provider'     => $user->provider,
            'provider_id'  => $user->provider_id,
            'role'         => $user->role,
            'status'       => $user->status,
        ]);

        $user->id = $this->db->lastInsertId();

        return $user;
    }
}