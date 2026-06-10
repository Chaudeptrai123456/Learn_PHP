<?php
namespace App\Controllers;
use App\admin\AdminDashboardService;
use App\Core\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Core\Database;
use PDO;
class AuthController extends Controller {
    public $db;
    private UserRepository $userRepo;
    private AdminDashboardService $adminDashboard;
    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
        $this->adminDashboard = new AdminDashboardService($this->db);
        $this->userRepo=new UserRepository();
    }
    public function dashboard(){
        
        return $this->view('/admin/dashboard',[
            'dashboard'=> $this->adminDashboard->getFullDashboard(),
            'orders'=> $this->adminDashboard->getDashboardOrders(),
        ]);
    }
    public function login() {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }

        return $this->view('public/pages/login');
    }
    public function signup(){
        return $this->view('public/pages/signup');
    }
public function profile(){
        return $this->view('/account/profile');
    
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
    print_r($user);
    if (isset($_POST['remember'])) {
        setcookie("user_email", $user->email, time() + (86400 * 30), "/"); 
    }
    print_r($_SESSION['user']);
    if ($_SESSION['user']['role']==='admin') {
        header("Location: /assignment/admin/dashboard");
        
        exit;
    } else  {
        header("Location: /assignment");
        exit;
    }
}
public function adminDashboard() {
    echo("admin");
    return $this->view('/admin/dashboard',[
            'dashboard'=> $this->adminDashboard->getFullDashboard()
    ]);
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
    $address  = $_POST['address'] ?? '';
    if (!$name || !$email || !$phone || !$address || !$password) {      
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
    $avatarUrl = '/avatar/default.jpg';  
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $uploadDir = __DIR__ . '/../../public/uploads/avatar/';
        $fileTmp  = $_FILES['avatar']['tmp_name'];
        $fileName = time() . '_' . basename($_FILES['avatar']['name']);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowExt = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowExt)) {

            $newPath = $uploadDir . $fileName;

            if (move_uploaded_file($fileTmp, $newPath)) {
                $avatarUrl = '/avatar/' . $fileName;
            }
        }
    }
    $user = new User([
    'name'        => $name,
    'email'       => $email,
    'phone'       => $phone,
    'address'     => $address, 
    'password'    => password_hash($password, PASSWORD_DEFAULT),
    'provider'    => 'local',
    'avatar_url'  => $avatarUrl,
    'role'        => 'user',
    'status'      => 1
    ]);
    $user = $this->userRepo->create($user);
    $_SESSION['user'] = $user->toArray();
    print_r($_SESSION['user']);
    header("Location: /assignment");
    exit;
}
}