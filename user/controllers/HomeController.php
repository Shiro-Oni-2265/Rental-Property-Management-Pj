<?php
class HomeController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index()
    {
        require_once '../models/RoomModel.php';
        $roomModel = new RoomModel($this->conn);
        $allRooms = $roomModel->getAllRooms();
        $featuredRooms = array_slice($allRooms, 0, 3);

        require_once 'views/layouts/header.php';
        require_once 'views/home/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function contact()
    {
        require_once 'views/layouts/header.php';
        echo "<div style='text-align: center; padding: 50px;'><h2>Trang Liên hệ</h2><p>Đang xây dựng... Vui lòng gọi 1900 xxxx.</p></div>";
        require_once 'views/layouts/footer.php';
    }
}
?>