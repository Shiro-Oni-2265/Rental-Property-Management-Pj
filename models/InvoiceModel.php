<?php
/*
 * Lớp InvoiceModel (models/InvoiceModel.php)
 * Nhiệm vụ: Tương tác với bảng HOA_DON và CHI_TIET_HOA_DON trong Cơ sở dữ liệu.
 */
class InvoiceModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy toàn bộ danh sách hóa đơn
     */
    public function getAllInvoices() {
        $sql = "SELECT hd.*, p.ma_phong 
                FROM HOA_DON hd
                JOIN HOP_DONG h ON hd.ma_hop_dong = h.ma_hop_dong
                JOIN PHONG p ON h.ma_phong = p.ma_phong
                ORDER BY hd.nam DESC, hd.thang DESC, hd.ma_hoa_don DESC";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết hóa đơn theo ID
     */
    public function getInvoiceById($id) {
        $sql = "SELECT hd.*, h.ma_phong, p.gia_thue 
                FROM HOA_DON hd 
                JOIN HOP_DONG h ON hd.ma_hop_dong = h.ma_hop_dong
                JOIN PHONG p ON h.ma_phong = p.ma_phong
                WHERE hd.ma_hoa_don = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Khởi tạo hóa đơn mới hàng tháng
     */
    public function createInvoice($ma_hop_dong, $thang, $nam) {
        try {
            $this->conn->beginTransaction();

            // 1. Tạo hóa đơn gốc
            $stmt = $this->conn->prepare("INSERT INTO HOA_DON(ma_hop_dong, thang, nam, tong_tien, trang_thai)
                                    VALUES(:ma_hd, :thang, :nam, 0, 'Chua thanh toan')");
            $stmt->bindValue(':ma_hd', $ma_hop_dong);
            $stmt->bindValue(':thang', $thang);
            $stmt->bindValue(':nam', $nam);
            $stmt->execute();
            $invoiceId = (int)$this->conn->lastInsertId();

            // 2. Lấy giá thuê phòng cũ
            $rentStmt = $this->conn->prepare("SELECT p.gia_thue
                                        FROM HOP_DONG h
                                        JOIN PHONG p ON h.ma_phong = p.ma_phong
                                        WHERE h.ma_hop_dong = :id");
            $rentStmt->bindValue(':id', $ma_hop_dong);
            $rentStmt->execute();
            $rent = $rentStmt->fetch(PDO::FETCH_ASSOC);
            $gia_thue = $rent['gia_thue'] ?? 0;

            // 3. Đảm bảo dịch vụ Tiền Phòng tồn tại
            $tienPhongDvId = $this->ensureServiceExists('Tiền phòng', 0, 'tháng');
            // Cài sẵn đơn giá điện nước mặc định nếu chưa có
            $this->ensureServiceExists('Điện', 3500, 'kWh');
            $this->ensureServiceExists('Nước', 15000, 'm3');

            // 4. Thêm chi tiết hóa đơn (Tiền Phòng)
            $ins = $this->conn->prepare("INSERT INTO CHI_TIET_HOA_DON(ma_hoa_don, ma_dich_vu, so_luong, thanh_tien)
                                   VALUES(:hd, :dv, 1, :tt)");
            $ins->bindValue(':hd', $invoiceId);
            $ins->bindValue(':dv', $tienPhongDvId);
            $ins->bindValue(':tt', $gia_thue);
            $ins->execute();

            // 5. Cập nhật lại tổng tiền hóa đơn
            $this->updateInvoiceTotalInternal($invoiceId);

            $this->conn->commit();
            return $invoiceId;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Thêm dịch vụ vào hóa đơn
     */
    public function addServiceToInvoice($invoiceId, $ma_dv, $so_luong) {
        // Lấy đơn giá dịch vụ
        $dv_stmt = $this->conn->prepare("SELECT don_gia FROM DICH_VU WHERE ma_dich_vu = :id");
        $dv_stmt->bindParam(':id', $ma_dv);
        $dv_stmt->execute();
        $dv = $dv_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($dv) {
            $thanh_tien = $dv['don_gia'] * $so_luong;
            
            // Chèn dòng chi tiết
            $ins_stmt = $this->conn->prepare("INSERT INTO CHI_TIET_HOA_DON(ma_hoa_don, ma_dich_vu, so_luong, thanh_tien) VALUES (:hd, :dv, :sl, :tt)");
            $ins_stmt->bindParam(':hd', $invoiceId);
            $ins_stmt->bindParam(':dv', $ma_dv);
            $ins_stmt->bindParam(':sl', $so_luong);
            $ins_stmt->bindParam(':tt', $thanh_tien);
            $ins_stmt->execute();
            
            // Cập nhật lại tổng tiền hóa đơn
            $this->updateInvoiceTotalInternal($invoiceId);
            return true;
        }
        return false;
    }

    /**
     * Thanh toán hóa đơn
     */
    public function payInvoice($id) {
        $up_stmt = $this->conn->prepare("UPDATE HOA_DON SET trang_thai = 'Da thanh toan' WHERE ma_hoa_don = :id");
        $up_stmt->bindParam(':id', $id);
        return $up_stmt->execute();
    }

    /**
     * Thêm tiền phòng (thủ công nếu chưa có)
     */
    public function addRoomPriceLineItem($invoiceId, $gia_thue) {
        $tienPhongDvId = $this->ensureServiceExists('Tiền phòng', 0, 'tháng');
        $ins_stmt = $this->conn->prepare("INSERT INTO CHI_TIET_HOA_DON(ma_hoa_don, ma_dich_vu, so_luong, thanh_tien) VALUES (:hd, :dv, 1, :tt)");
        $ins_stmt->bindParam(':hd', $invoiceId);
        $ins_stmt->bindParam(':dv', $tienPhongDvId);
        $ins_stmt->bindParam(':tt', $gia_thue);
        $ins_stmt->execute();
        
        $this->updateInvoiceTotalInternal($invoiceId);
    }

    /**
     * Lấy danh sách các khoản thu của hóa đơn
     */
    public function getInvoiceDetails($invoiceId) {
        $details_stmt = $this->conn->prepare("SELECT ct.*, dv.ten_dich_vu, dv.don_vi, dv.don_gia 
                                        FROM CHI_TIET_HOA_DON ct 
                                        JOIN DICH_VU dv ON ct.ma_dich_vu = dv.ma_dich_vu 
                                        WHERE ct.ma_hoa_don = :id");
        $details_stmt->bindParam(':id', $invoiceId);
        $details_stmt->execute();
        return $details_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật tổng tiền hóa đơn từ các dòng chi tiết
     */
    private function updateInvoiceTotalInternal($invoiceId) {
        $sum_stmt = $this->conn->prepare("SELECT SUM(thanh_tien) as total FROM CHI_TIET_HOA_DON WHERE ma_hoa_don = :id");
        $sum_stmt->bindParam(':id', $invoiceId);
        $sum_stmt->execute();
        $total = $sum_stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        $up_stmt = $this->conn->prepare("UPDATE HOA_DON SET tong_tien = :total WHERE ma_hoa_don = :id");
        $up_stmt->bindParam(':total', $total);
        $up_stmt->bindParam(':id', $invoiceId);
        $up_stmt->execute();
    }

    /**
     * Hàm hỗ trợ kiểm tra dịch vụ đã tồn tại chưa, nếu chưa thì tạo
     */
    private function ensureServiceExists($serviceName, $unitPrice, $unit) {
        $stmt = $this->conn->prepare("SELECT ma_dich_vu FROM DICH_VU WHERE ten_dich_vu = :name LIMIT 1");
        $stmt->bindValue(':name', $serviceName);
        $stmt->execute();
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing && isset($existing['ma_dich_vu'])) {
            return (int)$existing['ma_dich_vu'];
        }

        $ins = $this->conn->prepare("INSERT INTO DICH_VU(ten_dich_vu, don_gia, don_vi) VALUES(:t, :g, :d)");
        $ins->bindValue(':t', $serviceName);
        $ins->bindValue(':g', $unitPrice);
        $ins->bindValue(':d', $unit);
        $ins->execute();

        return (int)$this->conn->lastInsertId();
    }
}
?>
