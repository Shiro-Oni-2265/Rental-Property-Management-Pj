<?php
class AuthModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($phone, $cccd) {
        // Hardcoded ADMIN login
        if ($phone === 'admin' && $cccd === 'admin123') {
            return [
                'ma_nguoi_thue' => 0,
                'ho_ten' => 'Administrator',
                'so_dien_thoai' => 'admin',
                'cccd' => 'admin123',
                'role' => 'admin'
            ];
        }

        $query = "SELECT * FROM NGUOI_THUE WHERE so_dien_thoai = :phone AND cccd = :cccd LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':cccd', $cccd);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $user['role'] = 'user'; // assign role
            return $user;
        }
        return false;
    }

    public function checkUserExists($phone, $cccd) {
        $query = "SELECT * FROM NGUOI_THUE WHERE so_dien_thoai = :phone OR cccd = :cccd LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':cccd', $cccd);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function register($fullName, $phone, $cccd) {
        $query = "INSERT INTO NGUOI_THUE (ho_ten, so_dien_thoai, cccd) VALUES (:fullName, :phone, :cccd)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fullName', $fullName);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':cccd', $cccd);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
}
?>
