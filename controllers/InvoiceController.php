<?php
/*
 * Lớp InvoiceController (controllers/InvoiceController.php)
 * Nhiệm vụ: Xử lý nghiệp vụ quản lý hóa đơn hàng tháng dành cho Admin.
 */
class InvoiceController extends Controller {

    public function __construct($db) {
        parent::__construct($db);
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /**
     * Danh sách hóa đơn
     */
    public function index() {
        $invoiceModel = new InvoiceModel($this->conn);
        $invoices = $invoiceModel->getAllInvoices();

        $this->render('admin/invoices/index', ['invoices' => $invoices]);
    }

    /**
     * Tạo hóa đơn mới
     */
    public function create() {
        // Lấy danh sách hợp đồng đang hoạt động
        $contracts_stmt = $this->conn->query("SELECT h.ma_hop_dong, p.ma_phong, n.ho_ten 
                                        FROM HOP_DONG h 
                                        JOIN PHONG p ON h.ma_phong = p.ma_phong
                                        LEFT JOIN HOP_DONG_NGUOI_THUE hdnt ON h.ma_hop_dong = hdnt.ma_hop_dong
                                        LEFT JOIN NGUOI_THUE n ON hdnt.ma_nguoi_thue = n.ma_nguoi_thue
                                        WHERE h.trang_thai = 'Dang thue'
                                        GROUP BY h.ma_hop_dong");
        $contracts = $contracts_stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_hop_dong = $_POST['ma_hop_dong'] ?? '';
            $thang = $_POST['thang'] ?? '';
            $nam = $_POST['nam'] ?? '';

            if (!empty($ma_hop_dong) && !empty($thang) && !empty($nam)) {
                $invoiceModel = new InvoiceModel($this->conn);
                try {
                    $invoiceId = $invoiceModel->createInvoice($ma_hop_dong, $thang, $nam);
                    if ($invoiceId) {
                        echo "<script>alert('Tạo hóa đơn thành công!'); window.location.href='index.php?controller=invoice&action=index';</script>";
                        exit;
                    }
                } catch (PDOException $e) {
                    echo "<script>alert('Lỗi: " . addslashes($e->getMessage()) . "');</script>";
                }
            } else {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin!');</script>";
            }
        }

        $this->render('admin/invoices/create', ['contracts' => $contracts]);
    }

    /**
     * Xem và chỉnh sửa chi tiết hóa đơn
     */
    public function details() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=invoice&action=index");
            exit;
        }

        $invoiceModel = new InvoiceModel($this->conn);
        $invoice = $invoiceModel->getInvoiceById($id);

        if (!$invoice) {
            header("Location: index.php?controller=invoice&action=index");
            exit;
        }

        // Xử lý Thêm Dịch Vụ mới vào hóa đơn (POST)
        if (isset($_POST['add_service'])) {
            $ma_dv = $_POST['ma_dich_vu'] ?? '';
            $so_luong = $_POST['so_luong'] ?? 1;

            if (!empty($ma_dv) && !empty($so_luong)) {
                if ($invoiceModel->addServiceToInvoice($id, $ma_dv, $so_luong)) {
                    header("Location: index.php?controller=invoice&action=details&id=$id");
                    exit;
                }
            }
        }

        // Xử lý Xác nhận Đã Thanh Toán (POST)
        if (isset($_POST['pay'])) {
            if ($invoiceModel->payInvoice($id)) {
                header("Location: index.php?controller=invoice&action=details&id=$id");
                exit;
            }
        }

        // Xử lý Thêm tiền phòng thủ công (POST)
        if (isset($_POST['add_room_price'])) {
            $invoiceModel->addRoomPriceLineItem($id, $invoice['gia_thue']);
            header("Location: index.php?controller=invoice&action=details&id=$id");
            exit;
        }

        // Lấy danh sách chi tiết (line items)
        $details = $invoiceModel->getInvoiceDetails($id);

        // Lấy danh sách dịch vụ cho Form lựa chọn
        $services_stmt = $this->conn->query("SELECT * FROM DICH_VU ORDER BY ten_dich_vu ASC");
        $services = $services_stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/invoices/details', [
            'id' => $id,
            'invoice' => $invoice,
            'details' => $details,
            'services' => $services
        ]);
    }
}
?>
