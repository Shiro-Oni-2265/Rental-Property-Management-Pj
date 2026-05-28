<?php
/*
 * Lớp ServiceModel (models/ServiceModel.php)
 * Nhiệm vụ: Tương tác với bảng DICH_VU trong Cơ sở dữ liệu.
 */
class ServiceModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy toàn bộ danh sách dịch vụ
     */
    public function getAllServices() {
        $query = "SELECT * FROM DICH_VU ORDER BY ma_dich_vu ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết dịch vụ theo ID
     */
    public function getServiceById($id) {
        $query = "SELECT * FROM DICH_VU WHERE ma_dich_vu = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm mới dịch vụ
     */
    public function addService($name, $price, $unit) {
        $query = "INSERT INTO DICH_VU(ten_dich_vu, don_gia, don_vi) VALUES(:name, :price, :unit)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':unit', $unit);
        return $stmt->execute();
    }

    /**
     * Cập nhật dịch vụ
     */
    public function updateService($id, $name, $price, $unit) {
        $query = "UPDATE DICH_VU SET ten_dich_vu = :name, don_gia = :price, don_vi = :unit WHERE ma_dich_vu = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':unit', $unit);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Xóa dịch vụ
     */
    public function deleteService($id) {
        $query = "DELETE FROM DICH_VU WHERE ma_dich_vu = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
