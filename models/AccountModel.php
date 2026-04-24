<?php
class AccountModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUserInfo($userId)
    {
        $query = "SELECT * FROM NGUOI_THUE WHERE ma_nguoi_thue = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserContracts($userId)
    {
        $query = "
            SELECT 
                HD.*, 
                P.dien_tich, 
                P.gia_thue, 
                P.trang_thai as trang_thai_phong
            FROM HOP_DONG HD
            JOIN HOP_DONG_NGUOI_THUE HD_NT ON HD.ma_hop_dong = HD_NT.ma_hop_dong
            JOIN PHONG P ON HD.ma_phong = P.ma_phong
            WHERE HD_NT.ma_nguoi_thue = :id
            ORDER BY HD.ngay_bat_dau DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function extendContract($contractId, $userId, $months)
    {
        $checkQuery = "SELECT HD.ma_hop_dong 
                       FROM HOP_DONG HD
                       JOIN HOP_DONG_NGUOI_THUE HDNT ON HD.ma_hop_dong = HDNT.ma_hop_dong
                       WHERE HD.ma_hop_dong = :cid AND HDNT.ma_nguoi_thue = :uid AND HD.trang_thai = 'Dang thue'";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->execute([':cid' => $contractId, ':uid' => $userId]);
        $contract = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($contract) {
            $updateQuery = "UPDATE HOP_DONG SET ngay_ket_thuc = DATE_ADD(ngay_ket_thuc, INTERVAL :months MONTH) WHERE ma_hop_dong = :cid";
            $updateStmt = $this->conn->prepare($updateQuery);
            return $updateStmt->execute([':months' => (int) $months, ':cid' => $contractId]);
        }
        return false;
    }

    public function createFeedback($userId, $type, $content)
    {
        $query = "INSERT INTO PHAN_HOI (ma_nguoi_thue, noi_dung, loai, trang_thai) VALUES (:uid, :content, :type, 'Chua xu ly')";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':uid' => $userId,
            ':content' => $content,
            ':type' => $type
        ]);
    }
}
?>