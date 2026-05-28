<?php
/*
 * Lớp UserAccountController (controllers/UserAccountController.php)
 * Nhiệm vụ: Xử lý quản lý tài khoản cư dân, gia hạn hợp đồng và gửi phản hồi lên Ban quản lý.
 */
class UserAccountController extends Controller {
    private $accountModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->accountModel = new AccountModel($db);

        // Yêu cầu đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    /**
     * Giao diện thông tin tài khoản & Hợp đồng cư dân
     */
    public function index() {
        $userId = $_SESSION['user_id'];
        $userInfo = $this->accountModel->getUserInfo($userId);

        if (!$userInfo) {
            session_unset();
            session_destroy();
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $contracts = $this->accountModel->getUserContracts($userId);

        $this->render('user/account/index', [
            'userInfo' => $userInfo,
            'contracts' => $contracts
        ]);
    }

    /**
     * Gia hạn hợp đồng trực tuyến
     */
    public function extend() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $contractId = $_POST['contract_id'] ?? null;
            $months = isset($_POST['months']) ? (int) $_POST['months'] : 1;

            if ($contractId && $months > 0) {
                $this->accountModel->extendContract($contractId, $userId, $months);
            }
        }
        header('Location: index.php?controller=account&action=index');
        exit;
    }

    /**
     * Tải ảnh định danh CCCD kiểm duyệt tài khoản
     */
    public function verify() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['cccd_front']) && isset($_FILES['cccd_back'])) {
                $_SESSION['verified'] = true;
                echo "<script>alert('Tải lên thành công! Hồ sơ của bạn đã được tiếp nhận và trong thời gian được duyệt.'); window.location.href='index.php?controller=account&action=index';</script>";
                exit;
            }
        }
        header('Location: index.php?controller=account&action=index');
        exit;
    }

    /**
     * Gửi phản hồi/báo cáo sự cố lên Ban quản lý
     */
    public function submitFeedback() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $type = $_POST['feedback_type'] ?? '';
            $content = trim($_POST['feedback_content'] ?? '');

            if (!empty($type) && !empty($content)) {
                $result = $this->accountModel->createFeedback($userId, $type, $content);
                if ($result) {
                    echo "<script>alert('Gửi yêu cầu thành công! Ban quản lý sẽ phản hồi cho bạn trong thời gian sớm nhất.'); window.location.href='index.php?controller=account&action=index';</script>";
                    exit;
                } else {
                    echo "<script>alert('Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại sau.'); window.location.href='index.php?controller=account&action=index';</script>";
                    exit;
                }
            }
        }
        header('Location: index.php?controller=account&action=index');
        exit;
    }
}
?>
