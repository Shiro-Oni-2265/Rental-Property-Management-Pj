<?php
require_once 'models/AuthModel.php';

class AuthController {
    private $authModel;

    public function __construct($db) {
        $this->authModel = new AuthModel($db);
    }

    public function login() {
        if (isset($_SESSION['user_role'])) {
            $this->redirectBasedOnRole();
        }
        
        $error = '';
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        
        require_once 'views/auth/login.php';
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone = $_POST['phone'] ?? '';
            $cccd = $_POST['cccd'] ?? '';

            if (empty($phone) || empty($cccd)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ Số điện thoại và CCCD/Mật khẩu";
                header("Location: index.php?controller=auth&action=login");
                exit;
            }

            $user = $this->authModel->login($phone, $cccd);

            if ($user) {
                $_SESSION['user_id'] = $user['ma_nguoi_thue'];
                $_SESSION['user_name'] = $user['ho_ten'];
                $_SESSION['user_role'] = $user['role'];
                
                $this->redirectBasedOnRole();
            } else {
                $_SESSION['error'] = "Số điện thoại hoặc CCCD/Mật khẩu không đúng!";
                header("Location: index.php?controller=auth&action=login");
                exit;
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit;
    }

    private function redirectBasedOnRole() {
        if ($_SESSION['user_role'] === 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
        } else {
            header("Location: index.php?controller=user&action=home");
        }
        exit;
    }
}
?>
