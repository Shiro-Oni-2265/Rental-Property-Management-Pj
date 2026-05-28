<?php
/*
 * Lớp ServiceController (controllers/ServiceController.php)
 * Nhiệm vụ: Xử lý nghiệp vụ quản lý dịch vụ dành cho Admin.
 */
class ServiceController extends Controller {

    public function __construct($db) {
        parent::__construct($db);
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /**
     * Danh sách dịch vụ
     */
    public function index() {
        $serviceModel = new ServiceModel($this->conn);
        $services = $serviceModel->getAllServices();

        $this->render('admin/services/index', ['services' => $services]);
    }

    /**
     * Thêm mới dịch vụ
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['ten_dich_vu'] ?? '';
            $price = $_POST['don_gia'] ?? 0;
            $unit = $_POST['don_vi'] ?? '';

            if (!empty($name) && !empty($unit)) {
                $serviceModel = new ServiceModel($this->conn);
                if ($serviceModel->addService($name, $price, $unit)) {
                    echo "<script>alert('Thêm dịch vụ thành công!'); window.location.href='index.php?controller=service&action=index';</script>";
                    exit;
                } else {
                    echo "<script>alert('Có lỗi xảy ra!');</script>";
                }
            } else {
                echo "<script>alert('Vui lòng nhập đầy đủ thông tin!');</script>";
            }
        }

        $this->render('admin/services/create');
    }

    /**
     * Chỉnh sửa dịch vụ
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=service&action=index");
            exit;
        }

        $serviceModel = new ServiceModel($this->conn);
        $service = $serviceModel->getServiceById($id);

        if (!$service) {
            header("Location: index.php?controller=service&action=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['ten_dich_vu'] ?? '';
            $price = $_POST['don_gia'] ?? 0;
            $unit = $_POST['don_vi'] ?? '';

            if (!empty($name) && !empty($unit)) {
                if ($serviceModel->updateService($id, $name, $price, $unit)) {
                    echo "<script>alert('Cập nhật dịch vụ thành công!'); window.location.href='index.php?controller=service&action=index';</script>";
                    exit;
                } else {
                    echo "<script>alert('Có lỗi xảy ra!');</script>";
                }
            } else {
                echo "<script>alert('Vui lòng nhập đầy đủ thông tin!');</script>";
            }
        }

        $this->render('admin/services/edit', ['sv' => $service]);
    }

    /**
     * Xóa dịch vụ
     */
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $serviceModel = new ServiceModel($this->conn);
            try {
                if ($serviceModel->deleteService($id)) {
                    header("Location: index.php?controller=service&action=index");
                    exit;
                }
            } catch (PDOException $e) {
                echo "<script>alert('Không thể xóa dịch vụ này vì đang nằm trong hóa đơn của khách thuê!');</script>";
                echo "<script>window.location.href='index.php?controller=service&action=index';</script>";
                exit;
            }
        }
        header("Location: index.php?controller=service&action=index");
        exit;
    }
}
?>
