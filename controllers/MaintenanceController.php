<?php
/*
 * Lớp MaintenanceController (controllers/MaintenanceController.php)
 * Nhiệm vụ: Xử lý nghiệp vụ quản lý lịch sử bảo trì dành cho Admin.
 */
class MaintenanceController extends Controller {

    public function __construct($db) {
        parent::__construct($db);
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /**
     * Lịch sử bảo trì
     */
    public function index() {
        $maintenanceModel = new MaintenanceModel($this->conn);
        $logs = $maintenanceModel->getAllMaintenance();

        $this->render('admin/maintenance/index', ['logs' => $logs]);
    }

    /**
     * Ghi nhận bảo trì
     */
    public function create() {
        $roomModel = new RoomModel($this->conn);
        $rooms = $roomModel->getAllRooms();

        // Xử lý tự động điền (autofill) khi đi từ phản hồi của khách thuê
        $auto_ma_phong = $_GET['ma_phong'] ?? '';
        $auto_desc = isset($_GET['desc']) ? urldecode($_GET['desc']) : '';
        $ph_id = $_GET['ph_id'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_phong = $_POST['ma_phong'] ?? '';
            $loai = $_POST['loai_bao_tri'] ?? '';
            $chi_phi = $_POST['chi_phi'] ?? 0;
            $ngay = $_POST['ngay_bao_tri'] ?? '';
            $posted_ph_id = $_POST['ph_id'] ?? null;

            if (!empty($ma_phong) && !empty($loai) && !empty($ngay)) {
                $maintenanceModel = new MaintenanceModel($this->conn);
                try {
                    if ($maintenanceModel->addMaintenance($ma_phong, $loai, $chi_phi, $ngay, $posted_ph_id)) {
                        if (!empty($posted_ph_id)) {
                            echo "<script>alert('Lưu lịch sử bảo trì và Phản hồi đã được xử lý!'); window.location.href='index.php?controller=feedback&action=index';</script>";
                        } else {
                            echo "<script>alert('Ghi nhận bảo trì thành công!'); window.location.href='index.php?controller=maintenance&action=index';</script>";
                        }
                        exit;
                    }
                } catch (PDOException $e) {
                    echo "<script>alert('Lỗi: " . addslashes($e->getMessage()) . "');</script>";
                }
            } else {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin!');</script>";
            }
        }

        $this->render('admin/maintenance/create', [
            'rooms' => $rooms,
            'auto_ma_phong' => $auto_ma_phong,
            'auto_desc' => $auto_desc,
            'ph_id' => $ph_id
        ]);
    }
}
?>
