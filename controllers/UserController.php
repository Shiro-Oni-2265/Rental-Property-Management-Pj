<?php
require_once 'models/AccountModel.php';

class UserController {
    private $conn;
    private $accountModel;

    public function __construct($db) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        $this->conn = $db;
        $this->accountModel = new AccountModel($db);
    }

    public function home() {
        $userId = $_SESSION['user_id'];
        $userInfo = $this->accountModel->getUserInfo($userId);
        $contracts = $this->accountModel->getUserContracts($userId);
        
        require_once 'views/user/home.php';
    }
}
?>
