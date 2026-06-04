<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/app/Repositories/CategoryRepository.php';
require_once __DIR__ . '/app/Repositories/UserRepository.php';
require_once __DIR__ . '/app/Repositories/VoucherRepository.php';
require_once __DIR__ . '/app/Models/User.php';
require_once __DIR__ . '/app/Models/Product.php';
require_once __DIR__ . '/app/Models/ProductSku.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Controllers/HomeController.php';
require_once __DIR__ . '/app/Controllers/ProductController.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Routers/web.php';
require_once __DIR__ . '/app/Models/Data.php';
require_once __DIR__ . '/app/Repositories/ProductRepository.php';
require_once __DIR__ . '/app/Controllers/OrderController.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Models/Category.php';
require_once __DIR__ . '/app/DTOs/OrderItem.php';
require_once __DIR__ . '/app/Helper/router.helper.php';
use App\Core\Database;
try {
    $db = Database::getInstance()->getConnection();
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
use App\Core\Controller;
Controller::resolve();