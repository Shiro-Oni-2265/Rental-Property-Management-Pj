<?php
/*
 * Lớp Base Controller (core/Controller.php)
 * Lớp cha cho tất cả các Controller trong hệ thống.
 * Cung cấp hàm render view tự động để giảm tải việc require thủ công.
 */
class Controller {
    protected $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Render một view HTML kèm theo dữ liệu
     * 
     * @param string $viewPath Đường dẫn view (ví dụ: 'admin/rooms/index')
     * @param array $data Mảng dữ liệu truyền sang View
     */
    protected function render($viewPath, $data = []) {
        // Trích xuất mảng dữ liệu thành các biến cục bộ (ví dụ: ['rooms' => $rooms] -> $rooms)
        extract($data);

        // Thiết lập biến môi trường để Layouts biết được đường dẫn gốc của app (tránh dùng $path_to_root thủ công)
        $path_to_root = './'; 

        // Khởi tạo các hàm helper dùng chung nếu cần
        if (file_exists('includes/functions.php')) {
            require_once 'includes/functions.php';
        }

        // Tự động load view
        $file = "views/{$viewPath}.php";
        if (file_exists($file)) {
            require_once $file;
        } else {
            echo "<h1>500 - Lỗi hệ thống: View '{$viewPath}' không tồn tại</h1>";
        }
    }
}
?>
