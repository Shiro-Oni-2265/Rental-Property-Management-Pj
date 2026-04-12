<?php
require_once '../models/RoomModel.php';

class CartController {
    private $roomModel;

    public function __construct($conn) {
        $this->roomModel = new RoomModel($conn);
        
        // Ensure user is logged in
        // Require login from user to access personal cart
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth&action=login');
            exit;
        }
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $cart = isset($_SESSION['cart'][$userId]) ? $_SESSION['cart'][$userId] : [];

        $cartItems = [];
        $totalPrice = 0;

        foreach ($cart as $roomId => $quantity) {
            $room = $this->roomModel->getRoomById($roomId);
            if ($room) {
                // Item total calculated based on months (quantity)
                $itemTotal = $room['gia_thue'] * $quantity;
                $totalPrice += $itemTotal;
                
                $room['quantity'] = $quantity;
                $room['item_total'] = $itemTotal;
                $cartItems[] = $room;
            }
        }

        require_once 'views/layouts/header.php';
        require_once 'views/cart/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function add() {
        $userId = $_SESSION['user_id'];
        
        // Support both GET (from list page) and POST (from detail page form)
        $roomId = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : null);
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($quantity < 1) $quantity = 1;

        $actionType = isset($_POST['action_type']) ? $_POST['action_type'] : 'cart';

        if ($roomId) {
            if (!isset($_SESSION['cart'][$userId])) {
                $_SESSION['cart'][$userId] = [];
            }

            // Always prioritize the explicitly provided quantity from the form
            // Or increment by 1 if coming from regular "Add to Cart" GET request
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
            header('Location: ?controller=checkout&action=index&id=' . $roomId . '&qty=' . $_SESSION['cart'][$userId][$roomId]);
        } else {
            header('Location: ?controller=cart&action=index');
        }
        exit;
    }

    public function update() {
        $userId = $_SESSION['user_id'];
        $roomId = isset($_POST['id']) ? $_POST['id'] : null;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        if ($roomId && isset($_SESSION['cart'][$userId][$roomId])) {
            if ($quantity >= 1) {
                $_SESSION['cart'][$userId][$roomId] = $quantity;
            }
        }
        header('Location: ?controller=cart&action=index');
        exit;
    }

    public function remove() {
        $userId = $_SESSION['user_id'];
        $roomId = isset($_GET['id']) ? $_GET['id'] : null;

        if ($roomId && isset($_SESSION['cart'][$userId][$roomId])) {
            unset($_SESSION['cart'][$userId][$roomId]);
        }

        header('Location: ?controller=cart&action=index');
        exit;
    }

    public function clear() {
        $userId = $_SESSION['user_id'];
        if (isset($_SESSION['cart'][$userId])) {
            unset($_SESSION['cart'][$userId]);
        }
        header('Location: ?controller=cart&action=index');
        exit;
    }
}
?>
