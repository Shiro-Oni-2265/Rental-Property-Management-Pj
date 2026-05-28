<?php
/*
 * Lớp ContractController (controllers/ContractController.php)
 * Nhiệm vụ: Quản lý nghiệp vụ Hợp đồng thuê trọ dành cho Admin.
 */
class ContractController extends Controller {

    public function __construct($db) {
        parent::__construct($db);
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /**
     * Hiển thị danh sách hợp đồng
     */
    public function index() {
        $contractModel = new ContractModel($this->conn);
        $contracts = $contractModel->getAllContracts();

        $this->render('admin/contracts/index', ['contracts' => $contracts]);
    }

    /**
     * Tạo hợp đồng thuê trọ mới
     */
    public function create() {
        $roomModel = new RoomModel($this->conn);
        $rooms = $roomModel->getAvailableRooms();

        $tenantModel = new TenantModel($this->conn);
        $tenants = $tenantModel->getAllTenants();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_phong = $_POST['ma_phong'] ?? '';
            $ma_nguoi_thue = $_POST['ma_nguoi_thue'] ?? [];
            $ngay_bd = $_POST['ngay_bd'] ?? '';
            $ngay_kt = $_POST['ngay_kt'] ?? '';
            $tien_coc = $_POST['tien_coc'] ?? '';

            if (!empty($ma_phong) && !empty($ma_nguoi_thue) && !empty($ngay_bd) && !empty($ngay_kt) && !empty($tien_coc)) {
                $contractModel = new ContractModel($this->conn);
                try {
                    if ($contractModel->createContract($ma_phong, $ma_nguoi_thue, $ngay_bd, $ngay_kt, $tien_coc)) {
                        echo "<script>alert('Tạo hợp đồng thành công!'); window.location.href='index.php?controller=contract&action=index';</script>";
                        exit;
                    }
                } catch (PDOException $e) {
                    echo "<script>alert('Lỗi: " . addslashes($e->getMessage()) . "');</script>";
                }
            } else {
                echo "<script>alert('Vui lòng nhập đầy đủ thông tin!');</script>";
            }
        }

        $this->render('admin/contracts/create', [
            'rooms' => $rooms,
            'tenants' => $tenants
        ]);
    }

    /**
     * Kết thúc hợp đồng
     */
    public function terminate() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $contractModel = new ContractModel($this->conn);
            if ($contractModel->terminateContract($id)) {
                echo "<script>alert('Kết thúc hợp đồng thành công!'); window.location.href='index.php?controller=contract&action=index';</script>";
                exit;
            } else {
                echo "<script>alert('Có lỗi xảy ra!'); window.location.href='index.php?controller=contract&action=index';</script>";
                exit;
            }
        }
        header("Location: index.php?controller=contract&action=index");
        exit;
    }
}
?>
