<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Controllers/HomeController.php';
require_once __DIR__ . '/app/Controllers/ProductController.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Routers/web.php';

use App\Core\Controller;
Controller::resolve();