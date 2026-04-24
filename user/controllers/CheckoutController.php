<?php
require_once '../models/CheckoutModel.php';
require_once '../models/RoomModel.php';

class CheckoutController {
    private $checkoutModel;
    private $roomModel;

    public function __construct($conn) {
        $this->checkoutModel = new CheckoutModel($conn);
        $this->roomModel = new RoomModel($conn);
        
        // Ensure user is logged in to perform checkout
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth&action=login');
            exit;
        }
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $singleRoomId = isset($_GET['id']) ? $_GET['id'] : null;

        $checkoutItems = [];
        $totalPrice = 0;
        $totalDeposit = 0;
        $source = 'cart';

        if ($singleRoomId) {
            $source = 'single';
            $qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
            if ($qty < 1) $qty = 1;
            
            $room = $this->roomModel->getRoomById($singleRoomId);
            if ($room && $room['trang_thai'] === 'Trong') {
                $room['quantity'] = $qty; 
                $room['item_total'] = $room['gia_thue'] * $qty;
                $totalPrice += $room['item_total'];
                $totalDeposit += $room['gia_thue']; // Deposit 1 month
                $checkoutItems[] = $room;
            }
        } else {
            // Loading from cart
            $cart = isset($_SESSION['cart'][$userId]) ? $_SESSION['cart'][$userId] : [];
            foreach ($cart as $roomId => $quantity) {
                $room = $this->roomModel->getRoomById($roomId);
                if ($room && $room['trang_thai'] === 'Trong') {
                    $itemTotal = $room['gia_thue'] * $quantity;
                    $totalPrice += $itemTotal;
                    $totalDeposit += $room['gia_thue']; 
                    
                    $room['quantity'] = $quantity;
                    $room['item_total'] = $itemTotal;
                    $checkoutItems[] = $room;
                }
            }
        }

        require_once 'views/layouts/header.php';
        if (empty($checkoutItems)) {
            $error = "Không có phòng nào hợp lệ để đặt (có thể phòng đã được thuê bởi người khác hoặc giỏ hàng trống).";
            require_once 'views/checkout/error.php';
        } else {
            require_once 'views/checkout/index.php';
        }
        require_once 'views/layouts/footer.php';
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?controller=home');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $source = $_POST['source'] ?? 'cart';
        $itemsJson = $_POST['items_data'] ?? '[]';
        $itemsData = json_decode($itemsJson, true);
        $ngay_bat_dau = $_POST['ngay_bat_dau'] ?? date('Y-m-d');
        
        // Capture internet option choice
        $includeInternet = isset($_POST['include_internet']) && $_POST['include_internet'] == '1';

        if (!$itemsData || empty($itemsData)) {
            header('Location: ?controller=cart');
            exit;
        }

        $result = $this->checkoutModel->processCheckout($userId, $itemsData, $ngay_bat_dau, $includeInternet);

        if ($result['success']) {
            if ($source === 'cart' && isset($_SESSION['cart'][$userId])) {
                unset($_SESSION['cart'][$userId]);
            }
            header('Location: ?controller=checkout&action=success');
            exit;
        } else {
            $error = $result['error'];
            require_once 'views/layouts/header.php';
            require_once 'views/checkout/error.php';
            require_once 'views/layouts/footer.php';
        }
    }

    public function success() {
        require_once 'views/layouts/header.php';
        require_once 'views/checkout/success.php';
        require_once 'views/layouts/footer.php';
    }
}
?>
