<?php
require_once '../models/RoomModel.php';

class RoomController
{
    private $roomModel;

    public function __construct($conn)
    {
        $this->roomModel = new RoomModel($conn);
    }

    public function list()
    {
        $rooms = $this->roomModel->getAllRooms();

        require_once 'views/layouts/header.php';
        require_once 'views/room/list.php';
        require_once 'views/layouts/footer.php';
    }

    public function detail()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if (!$id) {
            echo "<div class='container'><h2>Mã phòng không hợp lệ.</h2><a href='?controller=room&action=list' class='btn btn-primary'>Quay lại</a></div>";
            return;
        }

        $room = $this->roomModel->getRoomById($id);

        if (!$room) {
            echo "<div class='container'><h2>Không tìm thấy thông tin phòng.</h2><a href='?controller=room&action=list' class='btn btn-primary'>Quay lại</a></div>";
            return;
        }

        require_once 'views/layouts/header.php';
        require_once 'views/room/detail.php';
        require_once 'views/layouts/footer.php';
    }
}
?>