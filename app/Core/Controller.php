<?php
namespace App\Core;

abstract class Controller {

    protected static array $routes = [];

 public static function get(string $uri, string $action): void {
        self::$routes['GET'][$uri] = $action;
    }

    // public static function dispatch() {
    //     $method = $_SERVER['REQUEST_METHOD'];
    //     $uri = $_SERVER['REQUEST_URI'];

    //     $uri = strtok($uri, '?');

    //     $uri = str_replace('/assignment', '', $uri);

    //     foreach (self::$routes[$method] as $route => $action) {

    //         $pattern = str_replace('(:any)', '([^/]+)', $route);

    //         $pattern = "#^" . $pattern . "$#";

    //         if (preg_match($pattern, $uri, $matches)) {

    //             array_shift($matches); // bỏ full match

    //             list($controller, $methodAction) = explode('@', $action);

    //             $controller = "App\\Controllers\\" . $controller;

    //             call_user_func_array([new $controller, $methodAction], $matches);

    //             return;
    //         }
    //     }

    //     http_response_code(404);
    //     echo "404 Not Found";
    // }

    public static function post(string $uri, string $action): void {
        self::$routes['POST'][$uri] = $action;
    }
    public static function resolve(): void {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = '/assignment'; // tên folder project
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = rtrim($uri, '/') ?: '/';
        foreach (self::$routes[$requestMethod] ?? [] as $route => $action) {
            $route = rtrim($route, '/') ?: '/';
            $pattern = preg_replace('#\{[a-zA-Z]+\}#', '([^/]+)', $route);
            $pattern = "#^$pattern$#";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$controller, $method] = explode('@', $action);
                $controllerClass = "App\\Controllers\\$controller";
                if (!class_exists($controllerClass)) {
                    die("Controller không tồn tại");
                }
                $instance = new $controllerClass();
                if (!method_exists($instance, $method)) {
                    die("Method không tồn tại");
                }
                call_user_func_array([$instance, $method], $matches);
                return;
            }
        }
        http_response_code(404);
        header("Location: /assignment/404");
        exit;
    }
    protected function view(string $view, array $data = []): void {
        $viewFile = __DIR__ . "/../../views/$view.php";
        if (!file_exists($viewFile)) {
            die("View không tồn tại");
        }
        extract($data);
        require __DIR__ . "/../../views/layouts/header.php";
        require $viewFile;
        require __DIR__ . "/../../views/layouts/footer.php";
    }

    protected function redirect(string $url): void {
        header("Location: /" . ltrim($url, '/'));
        exit;
    }

    protected function json($data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

}