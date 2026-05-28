<?php
/*
 * Lớp Router
 * Nhiệm vụ: Đọc URL (phương thức GET) và gọi đúng Controller và Action tương ứng.
 * Ví dụ: index.php?controller=room&action=index sẽ gọi hàm index() của lớp RoomController.
 */
class Router {
    public function handleRequest($conn) {
        // Lấy tên Controller từ URL. Nếu không có, mặc định là 'admin'
        $controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'admin';
        
        // Lấy tên Action (hàm) từ URL. Nếu không có, mặc định là 'dashboard'
        $actionName = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

        // Xử lý mặc định dựa vào role (Quyền người dùng)
        // Nếu user đã đăng nhập nhưng không truyền controller/action trên URL
        if (!isset($_GET['controller']) && isset($_SESSION['user_role'])) {
            // Nếu là admin thì đưa vào màn admin, user thường thì vào trang chủ home
            $controllerName = $_SESSION['user_role'] === 'admin' ? 'admin' : 'user';
            $actionName = $_SESSION['user_role'] === 'admin' ? 'dashboard' : 'home';
        }

        // Khai báo các trang "Public" - Ai cũng có thể truy cập mà không cần đăng nhập
        $publicRoutes = [
            'auth' => ['login', 'authenticate', 'logout']
        ];

        // Kiểm tra xem trang đang truy cập có phải là public route không
        $isPublic = isset($publicRoutes[$controllerName]) && in_array($actionName, $publicRoutes[$controllerName]);
        
        // Xác thực (Authentication) & Phân quyền (Authorization)
        // Nếu không phải trang Public và người dùng chưa đăng nhập (không có session)
        if (!$isPublic && !isset($_SESSION['user_role'])) {
            // Chuyển hướng (Redirect) về trang Đăng nhập
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        // Bản đồ ánh xạ tên controller từ URL sang tên Class thực tế (tránh trùng lặp giữa Admin và User)
        $controllerMap = [
            'admin'       => 'AdminController',
            'auth'        => 'AuthController',
            'room'        => 'RoomController',
            'tenant'      => 'TenantController',
            'contract'    => 'ContractController',
            'invoice'     => 'InvoiceController',
            'service'     => 'ServiceController',
            'maintenance' => 'MaintenanceController',
            'feedback'    => 'FeedbackController',
            'user'        => 'UserController',
            'user_room'   => 'UserRoomController',
            'cart'        => 'UserCartController',
            'checkout'    => 'UserCheckoutController',
            'account'     => 'UserAccountController'
        ];

        // Lấy tên class tương ứng từ bản đồ. Nếu không có, mặc định sinh theo chuẩn ucfirst
        if (array_key_exists($controllerName, $controllerMap)) {
            $controllerClass = $controllerMap[$controllerName];
        } else {
            $controllerClass = ucfirst($controllerName) . 'Controller';
        }

        // Kiểm tra xem class Controller có tồn tại không (Autoloader sẽ tự động tìm và nạp file)
        if (class_exists($controllerClass)) {
            // Khởi tạo Controller và truyền kết nối CSDL (Dependency Injection)
            $controller = new $controllerClass($conn);
            
            // Kiểm tra xem Action (hàm) có tồn tại trong class Controller không
            if (method_exists($controller, $actionName)) {
                // Gọi action để xử lý request
                $controller->$actionName();
            } else {
                echo "<h1>404 - Không tìm thấy action '{$actionName}' trong '{$controllerClass}'</h1>";
            }
        } else {
            echo "<h1>404 - Không tìm thấy module '{$controllerName}' (Class '{$controllerClass}' không tồn tại)</h1>";
        }
    }
}
?>
