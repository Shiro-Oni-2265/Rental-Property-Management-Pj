<?php
require_once '../models/AccountModel.php';

class AccountController
{
    private $accountModel;

    public function __construct($conn)
    {
        $this->accountModel = new AccountModel($conn);

        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth&action=login');
            exit;
        }
    }

    public function index()
    {
        $userId = $_SESSION['user_id'];

        $userInfo = $this->accountModel->getUserInfo($userId);

        if (!$userInfo) {
            session_unset();
            session_destroy();
            header('Location: ?controller=auth&action=login');
            exit;
        }

        $contracts = $this->accountModel->getUserContracts($userId);

        require_once 'views/layouts/header.php';
        require_once 'views/account/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function extend()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $contractId = $_POST['contract_id'] ?? null;
            $months = isset($_POST['months']) ? (int) $_POST['months'] : 1;

            if ($contractId && $months > 0) {
                // Determine whether operation succeeds, we can redirect either way 
                // in a simple implementation. The view will pull the latest end_date.
                $this->accountModel->extendContract($contractId, $userId, $months);
            }
        }
        header('Location: ?controller=account&action=index');
        exit;
    }

    public function verify()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['cccd_front']) && isset($_FILES['cccd_back'])) {
                $_SESSION['verified'] = true;
                echo "<script>alert('Tải lên thành công! Hồ sơ của bạn đã được tiếp nhận và trong thời gian được duyệt.'); window.location.href='?controller=account&action=index';</script>";
                exit;
            }
        }
        header('Location: ?controller=account&action=index');
        exit;
    }
}
?>