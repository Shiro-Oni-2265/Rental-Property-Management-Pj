<?php
/*
 * Lớp UserController (controllers/UserController.php)
 * Nhiệm vụ: Quản lý các hành động dành riêng cho người dùng/người thuê (Cả công khai và bảo mật).
 */
class UserController extends Controller {
    private $accountModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->accountModel = new AccountModel($db);
    }

    /**
     * Action: index() - Public
     * Chức năng: Trang giới thiệu & Danh sách 3 phòng trống nổi bật cho khách vãng lai/người dùng chưa đăng nhập.
     */
    public function index() {
        $roomModel = new RoomModel($this->conn);
        $allRooms = $roomModel->getAllRooms();
        $featuredRooms = array_slice($allRooms, 0, 3);

        $this->render('user/index', [
            'featuredRooms' => $featuredRooms
        ]);
    }

    /**
     * Action: home() - Secured
     * Chức năng: Hiển thị trang quản lý hợp đồng cá nhân của người thuê đã đăng nhập.
     */
    public function home() {
        // Phân quyền: Yêu cầu đăng nhập là user thường
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $userInfo = $this->accountModel->getUserInfo($userId);
        $contracts = $this->accountModel->getUserContracts($userId);

        $this->render('user/home', [
            'userInfo' => $userInfo,
            'contracts' => $contracts
        ]);
    }
}
?>
