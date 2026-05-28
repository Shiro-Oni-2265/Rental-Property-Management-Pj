<?php
/*
 * Lớp UserCheckoutController (controllers/UserCheckoutController.php)
 * Nhiệm vụ: Xử lý hóa đơn thuê phòng, chốt hợp đồng từ phía Cư dân (User).
 */
class UserCheckoutController extends Controller {
    private $checkoutModel;
    private $roomModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->checkoutModel = new CheckoutModel($db);
        $this->roomModel = new RoomModel($db);
        
        // Yêu cầu đăng nhập để thanh toán/tạo hợp đồng
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    /**
     * Giao diện xác nhận thông tin thuê phòng (Checkout)
     */
    public function index() {
        $userId = $_SESSION['user_id'];
        $singleRoomId = $_GET['id'] ?? null;

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
                $totalDeposit += $room['gia_thue']; // Cọc 1 tháng tiền nhà
                $checkoutItems[] = $room;
            }
        } else {
            // Lấy từ giỏ hàng
            $cart = $_SESSION['cart'][$userId] ?? [];
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

        if (empty($checkoutItems)) {
            $error = "Không có phòng nào hợp lệ để đặt (có thể phòng đã được thuê bởi người khác hoặc giỏ hàng trống).";
            $this->render('user/checkout/error', ['error' => $error]);
        } else {
            $this->render('user/checkout/index', [
                'checkoutItems' => $checkoutItems,
                'totalPrice' => $totalPrice,
                'totalDeposit' => $totalDeposit,
                'source' => $source
            ]);
        }
    }

    /**
     * Xử lý xác nhận đặt phòng (gửi yêu cầu CSDL)
     */
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=user_room&action=list');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $source = $_POST['source'] ?? 'cart';
        $itemsJson = $_POST['items_data'] ?? '[]';
        $itemsData = json_decode($itemsJson, true);
        $ngay_bat_dau = $_POST['ngay_bat_dau'] ?? date('Y-m-d');
        
        // Nhận tùy chọn lắp đặt internet
        $includeInternet = isset($_POST['include_internet']) && $_POST['include_internet'] == '1';

        if (!$itemsData || empty($itemsData)) {
            header('Location: index.php?controller=cart&action=index');
            exit;
        }

        $result = $this->checkoutModel->processCheckout($userId, $itemsData, $ngay_bat_dau, $includeInternet);

        if ($result['success']) {
            if ($source === 'cart' && isset($_SESSION['cart'][$userId])) {
                unset($_SESSION['cart'][$userId]);
            }
            header('Location: index.php?controller=checkout&action=success');
            exit;
        } else {
            $error = $result['error'];
            $this->render('user/checkout/error', ['error' => $error]);
        }
    }

    /**
     * Màn hình báo thành công
     */
    public function success() {
        $this->render('user/checkout/success');
    }
}
?>
