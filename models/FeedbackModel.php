<?php
/*
 * Lớp FeedbackModel (models/FeedbackModel.php)
 * Nhiệm vụ: Tương tác với bảng PHAN_HOI trong Cơ sở dữ liệu.
 */
class FeedbackModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy toàn bộ phản hồi
     */
    public function getAllFeedbacks() {
        $sql = "SELECT ph.*, nt.ho_ten 
                FROM PHAN_HOI ph 
                JOIN NGUOI_THUE nt ON ph.ma_nguoi_thue = nt.ma_nguoi_thue
                ORDER BY ph.ma_phan_hoi DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết phản hồi theo ID
     */
    public function getFeedbackById($id) {
        $stmt = $this->conn->prepare("SELECT ma_nguoi_thue, loai, noi_dung, trang_thai FROM PHAN_HOI WHERE ma_phan_hoi = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Đánh dấu phản hồi đã xử lý
     */
    public function resolveFeedback($id) {
        $stmt_update = $this->conn->prepare("UPDATE PHAN_HOI SET trang_thai = 'Da xu ly' WHERE ma_phan_hoi = :id");
        $stmt_update->bindParam(':id', $id);
        return $stmt_update->execute();
    }

    /**
     * Tìm phòng đang thuê hoạt động của người dùng
     */
    public function findActiveRoomForUser($userId) {
        $query = "SELECT HD.ma_phong 
                  FROM HOP_DONG_NGUOI_THUE HD_NT 
                  JOIN HOP_DONG HD ON HD_NT.ma_hop_dong = HD.ma_hop_dong 
                  WHERE HD_NT.ma_nguoi_thue = :uid AND HD.trang_thai = 'Dang thue' 
                  ORDER BY HD.ngay_bat_dau DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uid' => $userId]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        return $room ? $room['ma_phong'] : '';
    }
}
?>
