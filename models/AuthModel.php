<?php
/*
 * Lớp AuthModel
 * Nhiệm vụ: Xử lý tương tác CSDL cho các chức năng Đăng nhập, Đăng ký.
 */
class AuthModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /*
     * Xử lý đăng nhập
     * Tham số: $phone (Số điện thoại), $cccd (Căn cước công dân - đóng vai trò như mật khẩu)
     */
    public function login($phone, $cccd) {
        // Cấu hình cứng (Hardcode) tài khoản Admin để tiện test và quản trị
        if ($phone === 'admin' && $cccd === 'admin123') {
            return [
                'ma_nguoi_thue' => 0,
                'ho_ten' => 'Administrator',
                'so_dien_thoai' => 'admin',
                'cccd' => 'admin123',
                'role' => 'admin' // Phân quyền là admin
            ];
        }

        // Truy vấn bảng NGUOI_THUE để tìm user có SDT và CCCD trùng khớp
        $query = "SELECT * FROM NGUOI_THUE WHERE so_dien_thoai = :phone AND cccd = :cccd LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':cccd', $cccd);
        $stmt->execute();
        
        // Nếu tìm thấy (số dòng > 0)
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $user['role'] = 'user'; // Gán quyền là user thường
            return $user;
        }
        return false; // Đăng nhập thất bại
    }

    /*
     * Kiểm tra xem user đã tồn tại chưa (Dựa vào số điện thoại hoặc CCCD)
     * Tránh việc tạo 2 tài khoản trùng SDT hoặc trùng CCCD
     */
    public function checkUserExists($phone, $cccd) {
        $query = "SELECT * FROM NGUOI_THUE WHERE so_dien_thoai = :phone OR cccd = :cccd LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':cccd', $cccd);
        $stmt->execute();
        
        return $stmt->rowCount() > 0; // Trả về true nếu đã tồn tại, false nếu chưa
    }

    /*
     * Đăng ký tài khoản người thuê mới
     */
    public function register($fullName, $phone, $cccd) {
        $query = "INSERT INTO NGUOI_THUE (ho_ten, so_dien_thoai, cccd) VALUES (:fullName, :phone, :cccd)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fullName', $fullName);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':cccd', $cccd);
        
        // Thực thi câu query, nếu thành công thì trả về ID của user vừa được tạo (lastInsertId)
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
}
?>
