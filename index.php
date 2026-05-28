<?php
/*
 * File index.php là Entry Point (Điểm bắt đầu) của toàn bộ hệ thống mô hình MVC.
 * Mọi yêu cầu (request) từ người dùng đều sẽ đi qua file này đầu tiên.
 */

// Bắt đầu hoặc tiếp tục một phiên làm việc (Session)
session_start();

// Đăng ký Autoloader tự động nạp các lớp (class) khi được gọi
spl_autoload_register(function ($class_name) {
    // Danh sách các thư mục chứa lớp của ứng dụng
    $directories = [
        'core/',
        'controllers/',
        'models/'
    ];

    foreach ($directories as $dir) {
        $file = __DIR__ . '/' . $dir . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Khởi tạo đối tượng Database và thiết lập kết nối
$db = new Database();
$conn = $db->getConnection();

// Khởi tạo đối tượng Router và truyền kết nối CSDL
$router = new Router();
$router->handleRequest($conn);
?>
