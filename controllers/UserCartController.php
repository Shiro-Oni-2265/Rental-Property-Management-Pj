<?php
/*
 * Lớp UserCartController (controllers/UserCartController.php)
 * Nhiệm vụ: Quản lý giỏ hàng đặt phòng trọ của cư dân.
 */
class UserCartController extends Controller {
    private $roomModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->roomModel = new RoomModel($db);
        
        // Yêu cầu đăng nhập để truy cập giỏ hàng cá nhân
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    /**
     * Giao diện giỏ hàng
     */
    public function index() {
        $userId = $_SESSION['user_id'];
        $cart = $_SESSION['cart'][$userId] ?? [];

        $cartItems = [];
        $totalPrice = 0;

        foreach ($cart as $roomId => $quantity) {
            $room = $this->roomModel->getRoomById($roomId);
            if ($room) {
                $itemTotal = $room['gia_thue'] * $quantity;
                $totalPrice += $itemTotal;
                
                $room['quantity'] = $quantity;
                $room['item_total'] = $itemTotal;
                $cartItems[] = $room;
            }
        }

        $this->render('user/cart/index', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice
        ]);
    }

    /**
     * Thêm phòng vào giỏ hàng
     */
    public function add() {
        $userId = $_SESSION['user_id'];
        
        $roomId = $_GET['id'] ?? ($_POST['id'] ?? null);
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($quantity < 1) $quantity = 1;

        $actionType = $_POST['action_type'] ?? 'cart';

        if ($roomId) {
            if (!isset($_SESSION['cart'][$userId])) {
                $_SESSION['cart'][$userId] = [];
            }

            if (isset($_POST['quantity'])) {
                if (isset($_SESSION['cart'][$userId][$roomId])) {
                    $_SESSION['cart'][$userId][$roomId] += $quantity;
                } else {
                    $_SESSION['cart'][$userId][$roomId] = $quantity;
                }
            } else {
                if (isset($_SESSION['cart'][$userId][$roomId])) {
                    $_SESSION['cart'][$userId][$roomId]++; 
                } else {
                    $_SESSION['cart'][$userId][$roomId] = 1;
                }
            }
        }

        if ($actionType === 'checkout') {
            header('Location: index.php?controller=checkout&action=index&id=' . $roomId . '&qty=' . $_SESSION['cart'][$userId][$roomId]);
        } else {
            header('Location: index.php?controller=cart&action=index');
        }
        exit;
    }

    /**
     * Cập nhật số tháng thuê
     */
    public function update() {
        $userId = $_SESSION['user_id'];
        $roomId = $_POST['id'] ?? null;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        if ($roomId && isset($_SESSION['cart'][$userId][$roomId])) {
            if ($quantity >= 1) {
                $_SESSION['cart'][$userId][$roomId] = $quantity;
            }
        }
        header('Location: index.php?controller=cart&action=index');
        exit;
    }

    /**
     * Xóa phòng khỏi giỏ hàng
     */
    public function remove() {
        $userId = $_SESSION['user_id'];
        $roomId = $_GET['id'] ?? null;

        if ($roomId && isset($_SESSION['cart'][$userId][$roomId])) {
            unset($_SESSION['cart'][$userId][$roomId]);
        }

        header('Location: index.php?controller=cart&action=index');
        exit;
    }

    /**
     * Làm trống giỏ hàng
     */
    public function clear() {
        $userId = $_SESSION['user_id'];
        if (isset($_SESSION['cart'][$userId])) {
            unset($_SESSION['cart'][$userId]);
        }
        header('Location: index.php?controller=cart&action=index');
        exit;
    }
}
?>
