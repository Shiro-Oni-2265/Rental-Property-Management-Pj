<?php
/*
 * Lớp TenantController (controllers/TenantController.php)
 * Nhiệm vụ: Xử lý nghiệp vụ quản lý người thuê (CRUD) dành cho Admin.
 */
class TenantController extends Controller {

    public function __construct($db) {
        parent::__construct($db);
        // Kiểm tra quyền Admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /**
     * Danh sách người thuê
     */
    public function index() {
        $tenantModel = new TenantModel($this->conn);
        $tenants = $tenantModel->getAllTenants();

        $this->render('admin/tenants/index', ['tenants' => $tenants]);
    }

    /**
     * Thêm người thuê mới
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho_ten = $_POST['ho_ten'] ?? '';
            $sdt = $_POST['sdt'] ?? '';
            $cccd = $_POST['cccd'] ?? '';

            if (!empty($ho_ten) && !empty($sdt) && !empty($cccd)) {
                $tenantModel = new TenantModel($this->conn);
                if ($tenantModel->addTenant($ho_ten, $sdt, $cccd)) {
                    echo "<script>alert('Thêm người thuê thành công!'); window.location.href='index.php?controller=tenant&action=index';</script>";
                    exit;
                } else {
                    echo "<script>alert('Có lỗi xảy ra khi lưu trữ!');</script>";
                }
            } else {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin!');</script>";
            }
        }

        $this->render('admin/tenants/create');
    }

    /**
     * Sửa thông tin người thuê
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=tenant&action=index");
            exit;
        }

        $tenantModel = new TenantModel($this->conn);
        $tenant = $tenantModel->getTenantById($id);

        if (!$tenant) {
            header("Location: index.php?controller=tenant&action=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho_ten = $_POST['ho_ten'] ?? '';
            $sdt = $_POST['sdt'] ?? '';
            $cccd = $_POST['cccd'] ?? '';

            if (!empty($ho_ten) && !empty($sdt) && !empty($cccd)) {
                if ($tenantModel->updateTenant($id, $ho_ten, $sdt, $cccd)) {
                    echo "<script>alert('Cập nhật thành công!'); window.location.href='index.php?controller=tenant&action=index';</script>";
                    exit;
                } else {
                    echo "<script>alert('Có lỗi xảy ra khi cập nhật!');</script>";
                }
            } else {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin!');</script>";
            }
        }

        $this->render('admin/tenants/edit', ['nt' => $tenant]);
    }

    /**
     * Xóa người thuê
     */
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $tenantModel = new TenantModel($this->conn);
            try {
                if ($tenantModel->deleteTenant($id)) {
                    header("Location: index.php?controller=tenant&action=index");
                    exit;
                }
            } catch (PDOException $e) {
                echo "<script>alert('Không thể xóa khách thuê này vì đang có dữ liệu hóa đơn/hợp đồng liên quan!');</script>";
                echo "<script>window.location.href='index.php?controller=tenant&action=index';</script>";
                exit;
            }
        }
        header("Location: index.php?controller=tenant&action=index");
        exit;
    }
}
?>
