<?php
/*
 * Lớp Database
 * Nhiệm vụ: Khởi tạo và quản lý kết nối đến Cơ sở dữ liệu MySQL bằng thư viện PDO.
 * Sử dụng PDO giúp thao tác CSDL an toàn hơn (hỗ trợ Prepare Statement chống SQL Injection).
 */
class Database {
    // Các thông số cấu hình kết nối CSDL
    private $host = "localhost";           // Địa chỉ server CSDL (chạy trên XAMPP thường là localhost)
    private $db_name = "quan_ly_phong_tro"; // Tên database
    private $username = "root";            // Tên tài khoản MySQL mặc định của XAMPP
    private $password = "";                // Mật khẩu (XAMPP mặc định không có mật khẩu)
    
    // Biến lưu trữ kết nối (Connection Object)
    public $conn;

    // Hàm thực hiện kết nối và trả về đối tượng PDO
    public function getConnection() {
        $this->conn = null;

        try {
            // Khởi tạo kết nối PDO
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            // Set charset UTF-8 để hỗ trợ đọc/ghi tiếng Việt có dấu tránh bị lỗi font
            $this->conn->exec("set names utf8mb4");
            
            // Cấu hình chế độ báo lỗi (Bắn Exception nếu có lỗi truy vấn SQL)
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Bắt lỗi và in ra màn hình nếu kết nối thất bại
            echo "Connection error: " . $exception->getMessage();
        }

        // Trả về đối tượng kết nối
        return $this->conn;
    }
}
?>
