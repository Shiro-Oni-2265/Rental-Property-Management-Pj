<?php
/*
 * Lớp MaintenanceModel (models/MaintenanceModel.php)
 * Nhiệm vụ: Tương tác với bảng BAO_TRI trong Cơ sở dữ liệu.
 */
class MaintenanceModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy toàn bộ lịch sử bảo trì
     */
    public function getAllMaintenance() {
        $sql = "SELECT bt.*, p.ma_phong 
                FROM BAO_TRI bt 
                JOIN PHONG p ON bt.ma_phong = p.ma_phong
                ORDER BY bt.ngay_bao_tri DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ghi nhận bảo trì và tùy chọn giải quyết phản hồi liên quan
     */
    public function addMaintenance($ma_phong, $loai, $chi_phi, $ngay, $ph_id = null) {
        try {
            $this->conn->beginTransaction();

            // 1. Thêm lịch sử bảo trì
            $stmt = $this->conn->prepare("INSERT INTO BAO_TRI(ma_phong, loai_bao_tri, chi_phi, ngay_bao_tri) VALUES(:p, :l, :c, :n)");
            $stmt->bindParam(':p', $ma_phong);
            $stmt->bindParam(':l', $loai);
            $stmt->bindParam(':c', $chi_phi);
            $stmt->bindParam(':n', $ngay);
            $stmt->execute();

            // 2. Nếu có ID phản hồi đi kèm, cập nhật trạng thái Phản hồi thành 'Da xu ly'
            if (!empty($ph_id)) {
                $stmt_resolve = $this->conn->prepare("UPDATE PHAN_HOI SET trang_thai = 'Da xu ly' WHERE ma_phan_hoi = :id");
                $stmt_resolve->bindParam(':id', $ph_id);
                $stmt_resolve->execute();
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
?>
