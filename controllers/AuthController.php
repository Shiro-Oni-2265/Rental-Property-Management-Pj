<?php
class AuthController extends Controller {
    private $authModel; // Biến gọi đến Model xử lý CSDL phần Đăng nhập

    public function __construct($db) {
        parent::__construct($db);
        // Khởi tạo AuthModel
        $this->authModel = new AuthModel($db);
    }

    /*
     * Action: login()
     * Chức năng: Hiển thị giao diện form đăng nhập
     */
    public function login() {
        // Nếu user đã đăng nhập rồi (có session role), thì điều hướng luôn vào trong, không bắt đăng nhập lại
        if (isset($_SESSION['user_role'])) {
            $this->redirectBasedOnRole();
        }
        
        // Nhận thông báo lỗi từ session (nếu đăng nhập sai ở lần trước)
        $error = '';
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']); // Xóa lỗi đi để chỉ hiện 1 lần
        }
        
        $this->render('auth/login', ['error' => $error]);
    }

    /*
     * Action: authenticate()
     * Chức năng: Nhận dữ liệu từ form (POST) và kiểm tra với CSDL
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone = $_POST['phone'] ?? '';
            $cccd = $_POST['cccd'] ?? '';

            // Kiểm tra dữ liệu đầu vào (Validation)
            if (empty($phone) || empty($cccd)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ Số điện thoại và CCCD/Mật khẩu";
                header("Location: index.php?controller=auth&action=login");
                exit;
            }

            // Gọi Model để check trong Database
            $user = $this->authModel->login($phone, $cccd);

            // Nếu thông tin đúng
            if ($user) {
                // Lưu thông tin vào Session để giữ trạng thái đăng nhập
                $_SESSION['user_id'] = $user['ma_nguoi_thue'];
                $_SESSION['user_name'] = $user['ho_ten'];
                $_SESSION['user_role'] = $user['role'];
                
                // Chuyển hướng người dùng dựa theo chức vụ
                $this->redirectBasedOnRole();
            } else {
                // Đăng nhập sai
                $_SESSION['error'] = "Số điện thoại hoặc CCCD/Mật khẩu không đúng!";
                header("Location: index.php?controller=auth&action=login");
                exit;
            }
        }
    }

    /*
     * Action: logout()
     * Chức năng: Đăng xuất (Xóa Session)
     */
    public function logout() {
        session_destroy(); // Hủy toàn bộ session
        header("Location: index.php?controller=auth&action=login");
        exit;
    }

    /*
     * Action: register()
     * Chức năng: Cho phép người thuê tự đăng ký tài khoản online
     */
    public function register() {
        if (isset($_SESSION['user_role'])) {
            $this->redirectBasedOnRole();
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = $_POST['fullname'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $cccd = $_POST['cccd'] ?? '';

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

        $this->render('auth/register', [
            'error' => $error,
            'success' => $success
        ]);
    }

    /*
     * Hàm hỗ trợ: Điều hướng trang tùy theo Role của user hiện tại
     */
    private function redirectBasedOnRole() {
        if ($_SESSION['user_role'] === 'admin') {
            header("Location: index.php?controller=admin&action=dashboard"); // Admin -> Dashboard
        } else {
            header("Location: index.php?controller=user&action=home");       // User -> Trang chủ của user
        }
        exit;
    }
}
?>
