<?php
/*
 * Lớp AdminController
 * Nhiệm vụ: Xử lý các logic liên quan đến trang quản trị (Admin Dashboard).
 */
class AdminController extends Controller {

    // Khởi tạo Controller, kiểm tra phân quyền (Authorization)
    public function __construct($db) {
        parent::__construct($db);
        // Nếu chưa đăng nhập hoặc quyền không phải là admin thì đẩy về trang Login
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /*
     * Action: dashboard()
     * Chức năng: Lấy dữ liệu thống kê tổng quan (phòng, người thuê, doanh thu) hiển thị ra trang chủ Admin.
     */
    public function dashboard() {
        // 1. Thống kê Phòng (Rooms)
        // Dùng CASE WHEN để đếm số phòng trống và số phòng đã thuê trong cùng 1 câu query
        $stmt = $this->conn->query("SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN trang_thai = 'Trong' THEN 1 ELSE 0 END) as trong,
            SUM(CASE WHEN trang_thai = 'Da thue' THEN 1 ELSE 0 END) as da_thue
            FROM PHONG");
        $room_stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Thống kê Người thuê (Tenants)
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM NGUOI_THUE");
        $tenant_stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. Thống kê Hóa đơn chưa thanh toán và Doanh thu dự kiến
        $stmt = $this->conn->query("SELECT COUNT(*) as total, SUM(tong_tien) as revenue FROM HOA_DON WHERE trang_thai = 'Chua thanh toan'");
        $invoice_stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4. Lấy danh sách 5 hợp đồng gần nhất (Mới tạo)
        $stmt = $this->conn->query("SELECT * FROM HOP_DONG ORDER BY ma_hop_dong DESC LIMIT 5");
        $recent_contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nạp view để hiển thị dữ liệu ra HTML bằng hàm render của Base Controller
        $this->render('admin/dashboard', [
            'room_stats' => $room_stats,
            'tenant_stats' => $tenant_stats,
            'invoice_stats' => $invoice_stats,
            'recent_contracts' => $recent_contracts
        ]);
    }
}
?>
