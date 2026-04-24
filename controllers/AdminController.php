<?php
class AdminController {
    private $conn;

    public function __construct($db) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        $this->conn = $db;
    }

    public function dashboard() {
        // 1. Rooms
        $stmt = $this->conn->query("SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN trang_thai = 'Trong' THEN 1 ELSE 0 END) as trong,
            SUM(CASE WHEN trang_thai = 'Da thue' THEN 1 ELSE 0 END) as da_thue
            FROM PHONG");
        $room_stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Tenants
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM NGUOI_THUE");
        $tenant_stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. Pending Invoices
        $stmt = $this->conn->query("SELECT COUNT(*) as total, SUM(tong_tien) as revenue FROM HOA_DON WHERE trang_thai = 'Chua thanh toan'");
        $invoice_stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Recent contracts
        $stmt = $this->conn->query("SELECT * FROM HOP_DONG ORDER BY ma_hop_dong DESC LIMIT 5");
        $recent_contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/admin/dashboard.php';
    }
}
?>
