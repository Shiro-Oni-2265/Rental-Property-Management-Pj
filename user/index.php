<?php
session_start();
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerFile = "controllers/{$controllerClass}.php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass($conn);
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            echo "<h1>404 - Không tìm thấy action '{$actionName}'</h1>";
        }
    } else {
        echo "<h1>404 - Controller class '{$controllerClass}' không tồn tại</h1>";
    }
} else {
    echo "<h1>404 - Không tìm thấy trang</h1>";
}
?>
