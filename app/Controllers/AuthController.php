<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
class AuthController extends Controller {
    private UserRepository $userRepo;
    public function __construct(){
        $this->userRepo=new UserRepository();
    }
    public function login() {
        return $this->view('public/pages/login');
    }
    public function signup(){
        return $this->view('public/pages/signup');
    }

public function handleLogin() {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        echo "Thiếu thông tin";
        return;
    }

    $userRepo = new UserRepository();

    $user = $userRepo->findByEmailOrPhone($email);

    if (!$user) {
        $_SESSION['error'] = "Tài khoảng không tồn tại";
        header("Location: /assignment/login");
        exit;        
    }

    if (!password_verify($password, $user->password)) {
        $_SESSION['error'] = "Sai tài khoản";
        header("Location: /assignment/login");
        exit;  
    }

    $_SESSION['user'] = $user->toArray();

    if (isset($_POST['remember'])) {
        setcookie("user_email", $user->email, time() + (86400 * 30), "/"); 
    }
    // redirect
    header("Location: /assignment");
    exit;
}
    
public function handleSignUp() {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $name     = $_POST['fullName'] ?? '';
    $email    = $_POST['email'] ?? '';
    $phone    = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirmPassword'] ?? '';

    if (!$name || !$email || !$phone || !$password) {
        $_SESSION['error'] = "Thiếu thông tin";
        header("Location: /assignment/signup");
        exit;
    }

    if ($password !== $confirm) {
        $_SESSION['error'] = "Mật khẩu không khớp";
        header("Location: /assignment/signup");
        exit;
    }

    if ($this->userRepo->existsByEmail($email)) {
        $_SESSION['error'] = "Email đã tồn tại";
        header("Location: /assignment/signup");
        exit;
    }

    $user = new User([
        'name'        => $name,
        'email'       => $email,
        'phone'       => $phone,
        'password'    => password_hash($password, PASSWORD_DEFAULT),
        'provider'    => 'local',
        'avatar_url'  => 'https://i.imgur.com/6VBx3io.png',
        'role'        => 'user',
        'status'      => 1
    ]);

    $user = $this->userRepo->create($user);

    $_SESSION['user'] = $user->toArray();

    header("Location: /assignment");
    exit;
}
}