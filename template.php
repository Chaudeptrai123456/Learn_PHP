<?php
namespace App\Core;
abstract class Controller {
    protected static array $routes = [];
    public static function get(string $uri, string $action): void {
        self::$routes['GET'][$uri] = $action;
    }
    public static function post(string $uri, string $action): void {
        self::$routes['POST'][$uri] = $action;
    }
public static function resolve(): void {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = '/assignment'; 
    if ($basePath !== '/' && str_starts_with($uri, $basePath)) {
        $uri = substr($uri, strlen($basePath));
    }
    $uri = rtrim($uri, '/') ?: '/';
    $routes = self::$routes[$requestMethod] ?? [];
    foreach ($routes as $route => $action) {
        $route = rtrim($route, '/') ?: '/';
        // convert /product/{slug} → regex
        $pattern = preg_replace('#\{[a-zA-Z]+\}#', '([^/]+)', $route);
        $pattern = "#^$pattern$#";
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches);
            // tách controller + method
            if (!str_contains($action, '@')) {
                die("Sai định dạng action");
            }
            [$controller, $method] = explode('@', $action);
            $controllerClass = "App\\Controllers\\$controller";
            if (!class_exists($controllerClass)) {
                die("Controller không tồn tại: $controllerClass");
            }
            $instance = new $controllerClass();
            if (!method_exists($instance, $method)) {
                die("Method không tồn tại: $method");
            }
            call_user_func_array([$instance, $method], $matches);
            return;
        }
    }
    http_response_code(404);
        echo "404 Not Found (URI: $uri)";
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






<?php
namespace App\Core;

class Router {

    private array $routes = [];

    public function get(string $path, string $action): void {
        $this->routes['GET'][$path] = $action;
    }

public function resolve(string $uri): void {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($uri, PHP_URL_PATH);
    foreach ($this->routes[$requestMethod] ?? [] as $route => $action) {
        $pattern = preg_replace('#\{[a-zA-Z]+\}#', '([^/]+)', $route);
        $pattern = "#^$pattern$#";
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches);
            [$controller, $actionMethod] = explode('@', $action);
            $controllerClass = "App\\Controllers\\$controller";
            if (!class_exists($controllerClass)) {
                die("Controller không tồn tại: $controllerClass");
            }
            $instance = new $controllerClass();
            if (!method_exists($instance, $actionMethod)) {
                die("Method không tồn tại: $actionMethod");
            }
            call_user_func_array([$instance, $actionMethod], $matches);
            exit;
        }
    }

    http_response_code(404);
    echo "404 Not Found";
}
}