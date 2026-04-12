<?php
require_once '../models/AuthModel.php';

class AuthController
{
    private $authModel;

    public function __construct($conn)
    {
        $this->authModel = new AuthModel($conn);
    }

    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ?controller=account&action=index');
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone = $_POST['phone'] ?? '';
            $cccd = $_POST['cccd'] ?? '';

            if (empty($phone) || empty($cccd)) {
                $error = 'Vui lòng nhập đầy đủ Số điện thoại và CCCD.';
            } elseif (!preg_match('/^(0[3|5|7|8|9])+([0-9]{8})$/', $phone)) {
                $error = 'Số điện thoại không hợp lệ. Phải bắt đầu bằng 03, 05, 07, 08, 09 và đủ 10 số.';
            } elseif (!preg_match('/^([0-9]{9}|[0-9]{12})$/', $cccd)) {
                $error = 'CCCD/CMND không hợp lệ. Phải bao gồm 9 hoặc 12 chữ số.';
            } else {
                $user = $this->authModel->login($phone, $cccd);
                if ($user) {
                    $_SESSION['user_id'] = $user['ma_nguoi_thue'];
                    $_SESSION['user_name'] = $user['ho_ten'];

                    header('Location: ?controller=home&action=index');
                    exit;
                } else {
                    $error = 'Số điện thoại hoặc CCCD không đúng!';
                }
            }
        }

        require_once 'views/layouts/header.php';
        require_once 'views/auth/login.php';
        require_once 'views/layouts/footer.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ?controller=home&action=index');
        exit;
    }

    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ?controller=account&action=index');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = $_POST['fullname'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $cccd = $_POST['cccd'] ?? '';

            // Basic trim
            $fullName = trim($fullName);

            if (empty($fullName) || empty($phone) || empty($cccd)) {
                $error = 'Vui lòng nhập đầy đủ Họ tên, Số điện thoại và CCCD.';
            } elseif (!preg_match('/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]{2,50}$/', $fullName)) {
                $error = 'Họ tên không hợp lệ. Họ tên chỉ được chứa chữ cái và dấu cách, dài từ 2-50 ký tự.';
            } elseif (!preg_match('/^(0[3|5|7|8|9])+([0-9]{8})$/', $phone)) {
                $error = 'Số điện thoại không hợp lệ. Phải bắt đầu bằng 03, 05, 07, 08, 09 và đủ 10 số.';
            } elseif (!preg_match('/^([0-9]{9}|[0-9]{12})$/', $cccd)) {
                $error = 'CCCD/CMND không hợp lệ. Phải bao gồm 9 hoặc 12 chữ số.';
            } else {
                if ($this->authModel->checkUserExists($phone, $cccd)) {
                    $error = 'Số điện thoại hoặc CCCD đã được đăng ký trên hệ thống!';
                } else {
                    $userId = $this->authModel->register($fullName, $phone, $cccd);
                    if ($userId) {
                        $success = 'Đăng ký thành công! Bạn hiện có thể đăng nhập bằng Số điện thoại và CCCD vừa tạo.';
                    } else {
                        $error = 'Đã xảy ra lỗi, không thể cập nhật CSDL. Vui lòng thử lại sau.';
                    }
                }
            }
        }

        require_once 'views/layouts/header.php';
        require_once 'views/auth/register.php';
        require_once 'views/layouts/footer.php';
    }
}
?>