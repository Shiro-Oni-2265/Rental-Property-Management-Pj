<?php
class Router {
    public function handleRequest($conn) {
        $controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'admin';
        $actionName = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

        // Custom default routing based on role if no controller is specified and user is logged in
        if (!isset($_GET['controller']) && isset($_SESSION['user_role'])) {
            $controllerName = $_SESSION['user_role'] === 'admin' ? 'admin' : 'user';
            $actionName = $_SESSION['user_role'] === 'admin' ? 'dashboard' : 'home';
        }

        // Authentication & Authorization check
        $publicRoutes = [
            'auth' => ['login', 'authenticate', 'logout']
        ];

        $isPublic = isset($publicRoutes[$controllerName]) && in_array($actionName, $publicRoutes[$controllerName]);
        
        if (!$isPublic && !isset($_SESSION['user_role'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile = "controllers/{$controllerClass}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass($conn);
                if (method_exists($controller, $actionName)) {
                    $controller->$actionName();
                } else {
                    echo "<h1>404 - Không tìm thấy action '{$actionName}' trong '{$controllerClass}'</h1>";
                }
            } else {
                echo "<h1>500 - Lỗi hệ thống: Controller class '{$controllerClass}' không tồn tại</h1>";
            }
        } else {
            echo "<h1>404 - Không tìm thấy module '{$controllerName}'</h1>";
        }
    }
}
?>
