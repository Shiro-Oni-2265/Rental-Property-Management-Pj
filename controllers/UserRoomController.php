<?php
/*
 * Lớp UserRoomController (controllers/UserRoomController.php)
 * Nhiệm vụ: Xử lý xem danh sách phòng và chi tiết phòng dành cho Người thuê (Client).
 */
class UserRoomController extends Controller {
    private $roomModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->roomModel = new RoomModel($db);
    }

    /**
     * Hiển thị danh sách toàn bộ phòng trọ
     */
    public function list() {
        $rooms = $this->roomModel->getAllRooms();
        $this->render('user/rooms/list', ['rooms' => $rooms]);
    }

    /**
     * Hiển thị chi tiết một phòng trọ
     */
    public function detail() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "<div class='container'><h2>Mã phòng không hợp lệ.</h2><a href='index.php?controller=user_room&action=list' class='btn btn-primary'>Quay lại</a></div>";
            return;
        }

        $room = $this->roomModel->getRoomById($id);

        if (!$room) {
            echo "<div class='container'><h2>Không tìm thấy thông tin phòng.</h2><a href='index.php?controller=user_room&action=list' class='btn btn-primary'>Quay lại</a></div>";
            return;
        }

        $this->render('user/rooms/detail', ['room' => $room]);
    }
}
?>
