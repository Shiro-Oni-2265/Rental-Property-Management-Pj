<?php
/*
 * Lớp AccountModel
 * Nhiệm vụ: Tương tác với CSDL cho các chức năng của tài khoản người thuê (xem thông tin, xem hợp đồng, gia hạn, phản hồi).
 */
class AccountModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /*
     * Lấy thông tin cá nhân của người thuê
     * Bảng: NGUOI_THUE
     * LIMIT 1: Chỉ lấy 1 bản ghi đầu tiên khớp điều kiện
     */
    public function getUserInfo($userId)
    {
        $query = "SELECT * FROM NGUOI_THUE WHERE ma_nguoi_thue = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
     * Lấy danh sách hợp đồng của một người thuê cụ thể
     * Bảng: HOP_DONG (HD), HOP_DONG_NGUOI_THUE (HD_NT), PHONG (P)
     * JOIN: Kết nối 3 bảng lại để lấy đầy đủ thông tin hợp đồng, phòng thuê tương ứng của user đó.
     */
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy tất cả hợp đồng
    }

    /*
     * Gia hạn hợp đồng
     * Tham số: $contractId (Mã hợp đồng), $userId (Mã người thuê), $months (Số tháng muốn gia hạn)
     */
    public function extendContract($contractId, $userId, $months)
    {
        // 1. Kiểm tra xem hợp đồng này có đúng là của user này và đang ở trạng thái 'Đang thuê' hay không
        $checkQuery = "SELECT HD.ma_hop_dong 
                       FROM HOP_DONG HD
                       JOIN HOP_DONG_NGUOI_THUE HDNT ON HD.ma_hop_dong = HDNT.ma_hop_dong
                       WHERE HD.ma_hop_dong = :cid AND HDNT.ma_nguoi_thue = :uid AND HD.trang_thai = 'Dang thue'";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->execute([':cid' => $contractId, ':uid' => $userId]);
        $contract = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Nếu hợp đồng hợp lệ thì thực hiện UPDATE ngày kết thúc (cộng thêm số tháng)
        if ($contract) {
            // Dùng DATE_ADD trong MySQL để cộng thêm khoảng thời gian (INTERVAL) vào ngày hiện tại
            $updateQuery = "UPDATE HOP_DONG SET ngay_ket_thuc = DATE_ADD(ngay_ket_thuc, INTERVAL :months MONTH) WHERE ma_hop_dong = :cid";
            $updateStmt = $this->conn->prepare($updateQuery);
            return $updateStmt->execute([':months' => (int) $months, ':cid' => $contractId]);
        }
        return false;
    }

    /*
     * Tạo phản hồi, báo cáo sự cố (vd: Hỏng điện, nước...)
     * Bảng: PHAN_HOI
     */
    public function createFeedback($userId, $type, $content)
    {
        // Mặc định phản hồi mới tạo sẽ có trạng thái là 'Chua xu ly' (Chưa xử lý)
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