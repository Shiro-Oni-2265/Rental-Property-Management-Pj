<?php
/*
 * Lớp TenantModel (models/TenantModel.php)
 * Nhiệm vụ: Tương tác với bảng NGUOI_THUE trong Cơ sở dữ liệu.
 */
class TenantModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy danh sách tất cả người thuê
     */
    public function getAllTenants() {
        $query = "SELECT * FROM NGUOI_THUE ORDER BY ma_nguoi_thue DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết một người thuê theo ID
     */
    public function getTenantById($id) {
        $query = "SELECT * FROM NGUOI_THUE WHERE ma_nguoi_thue = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm mới người thuê
     */
    public function addTenant($ho_ten, $sdt, $cccd) {
        $query = "INSERT INTO NGUOI_THUE(ho_ten, so_dien_thoai, cccd) VALUES(:ho_ten, :sdt, :cccd)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':cccd', $cccd);
        return $stmt->execute();
    }

    /**
     * Cập nhật thông tin người thuê
     */
    public function updateTenant($id, $ho_ten, $sdt, $cccd) {
        $query = "UPDATE NGUOI_THUE SET ho_ten = :ho_ten, so_dien_thoai = :sdt, cccd = :cccd WHERE ma_nguoi_thue = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':cccd', $cccd);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Xóa người thuê
     */
    public function deleteTenant($id) {
        $query = "DELETE FROM NGUOI_THUE WHERE ma_nguoi_thue = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
