<?php
/*
 * Lớp ContractModel (models/ContractModel.php)
 * Nhiệm vụ: Tương tác với bảng HOP_DONG và các bảng liên kết liên quan.
 */
class ContractModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy danh sách hợp đồng
     */
    public function getAllContracts() {
        $sql = "SELECT hd.*, p.ma_phong, p.gia_thue, 
                GROUP_CONCAT(nt.ho_ten SEPARATOR ', ') as tenants
                FROM HOP_DONG hd
                JOIN PHONG p ON hd.ma_phong = p.ma_phong
                LEFT JOIN HOP_DONG_NGUOI_THUE hdnt ON hd.ma_hop_dong = hdnt.ma_hop_dong
                LEFT JOIN NGUOI_THUE nt ON hdnt.ma_nguoi_thue = nt.ma_nguoi_thue
                GROUP BY hd.ma_hop_dong
                ORDER BY hd.ma_hop_dong DESC";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo hợp đồng thuê trọ mới kèm theo liên kết các khách thuê
     */
    public function createContract($ma_phong, $ma_nguoi_thue, $ngay_bd, $ngay_kt, $tien_coc) {
        try {
            $this->conn->beginTransaction();

            // 1. Chèn hợp đồng mới
            $query = "INSERT INTO HOP_DONG (ma_phong, ngay_bat_dau, ngay_ket_thuc, tien_coc, trang_thai) 
                      VALUES (:ma_phong, :ngay_bd, :ngay_kt, :tien_coc, 'Dang thue')";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ma_phong', $ma_phong);
            $stmt->bindParam(':ngay_bd', $ngay_bd);
            $stmt->bindParam(':ngay_kt', $ngay_kt);
            $stmt->bindParam(':tien_coc', $tien_coc);
            $stmt->execute();
            $contract_id = (int)$this->conn->lastInsertId();

            // 2. Liên kết các khách thuê với hợp đồng này
            $query_link = "INSERT INTO HOP_DONG_NGUOI_THUE (ma_hop_dong, ma_nguoi_thue) VALUES (:ma_hd, :ma_nt)";
            $stmt_link = $this->conn->prepare($query_link);
            
            foreach ($ma_nguoi_thue as $nt_id) {
                $stmt_link->bindParam(':ma_hd', $contract_id);
                $stmt_link->bindParam(':ma_nt', $nt_id);
                $stmt_link->execute();
            }

            // 3. Cập nhật trạng thái phòng thành 'Da thue'
            $query_room = "UPDATE PHONG SET trang_thai = 'Da thue' WHERE ma_phong = :id";
            $stmt_room = $this->conn->prepare($query_room);
            $stmt_room->bindParam(':id', $ma_phong);
            $stmt_room->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Hủy/Kết thúc hợp đồng
     */
    public function terminateContract($id) {
        // Cập nhật trạng thái hợp đồng thành 'Huy'. 
        // Trigger trg_hop_dong_update trong MySQL tự động trả trạng thái phòng về 'Trong'
        $query = "UPDATE HOP_DONG SET trang_thai = 'Huy' WHERE ma_hop_dong = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
