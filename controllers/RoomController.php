<?php
class RoomController {
    private $conn;

    public function __construct($db) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        $this->conn = $db;
    }

    public function index() {
        $stmt = $this->conn->query("SELECT * FROM PHONG ORDER BY ma_phong ASC");
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/admin/rooms/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dien_tich = $_POST['dien_tich'] ?? null;
            $gia_thue = $_POST['gia_thue'] ?? null;
            $mo_ta = $_POST['mo_ta'] ?? '';
            $hinh_anh = null;

            // Handle File Upload
            if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === UPLOAD_ERR_OK) {
                $allowed = [
                    "jpg" => "image/jpg",
                    "jpeg" => "image/jpeg",
                    "gif" => "image/gif",
                    "png" => "image/png"
                ];

                $filename = $_FILES['hinh_anh']['name'] ?? '';
                $filetype = $_FILES['hinh_anh']['type'] ?? '';
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (!array_key_exists($ext, $allowed) || !in_array($filetype, $allowed, true)) {
                    echo "<script>alert('Vui lòng chọn đúng định dạng ảnh (jpg, jpeg, png, gif).');</script>";
                } else {
                    $uploadDirFs = __DIR__ . '/../uploads/rooms/';
                    if (!file_exists($uploadDirFs)) {
                        mkdir($uploadDirFs, 0777, true);
                    }

                    $newFilename = uniqid('', true) . '-' . basename($filename);
                    if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadDirFs . $newFilename)) {
                        $hinh_anh = 'uploads/rooms/' . $newFilename; // relative path for DB/view
                    } else {
                        echo "<script>alert('Upload ảnh thất bại. Vui lòng thử lại.');</script>";
                    }
                }
            }

            try {
                $sql = "INSERT INTO PHONG (dien_tich, gia_thue, hinh_anh, mo_ta)
                        VALUES (:dien_tich, :gia_thue, :hinh_anh, :mo_ta)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':dien_tich', $dien_tich);
                $stmt->bindValue(':gia_thue', $gia_thue);
                $stmt->bindValue(':hinh_anh', $hinh_anh);
                $stmt->bindValue(':mo_ta', $mo_ta);

                if ($stmt->execute()) {
                    header("Location: index.php?controller=room&action=index");
                    exit;
                }

                echo "<script>alert('Có lỗi xảy ra!');</script>";
            } catch (PDOException $e) {
                echo "<script>alert('Lỗi: " . addslashes($e->getMessage()) . "');</script>";
            }
        }

        require_once 'views/admin/rooms/create.php';
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=room&action=index");
            exit;
        }

        $stmt = $this->conn->prepare("SELECT * FROM PHONG WHERE ma_phong = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            header("Location: index.php?controller=room&action=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dien_tich = $_POST['dien_tich'] ?? $room['dien_tich'];
            $gia_thue = $_POST['gia_thue'] ?? $room['gia_thue'];
            $trang_thai = $_POST['trang_thai'] ?? $room['trang_thai'];
            $mo_ta = $_POST['mo_ta'] ?? ($room['mo_ta'] ?? '');
            $hinh_anh = $room['hinh_anh'];

            // Handle File Upload (optional)
            if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === UPLOAD_ERR_OK) {
                $allowed = [
                    "jpg" => "image/jpg",
                    "jpeg" => "image/jpeg",
                    "gif" => "image/gif",
                    "png" => "image/png"
                ];

                $filename = $_FILES['hinh_anh']['name'] ?? '';
                $filetype = $_FILES['hinh_anh']['type'] ?? '';
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (!array_key_exists($ext, $allowed) || !in_array($filetype, $allowed, true)) {
                    echo "<script>alert('Vui lòng chọn đúng định dạng ảnh (jpg, jpeg, png, gif).');</script>";
                } else {
                    $uploadDirFs = __DIR__ . '/../uploads/rooms/';
                    if (!file_exists($uploadDirFs)) {
                        mkdir($uploadDirFs, 0777, true);
                    }

                    $newFilename = uniqid('', true) . '-' . basename($filename);
                    if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadDirFs . $newFilename)) {
                        $newRelPath = 'uploads/rooms/' . $newFilename;

                        // delete old file (if exists & local uploads path)
                        if (!empty($hinh_anh)) {
                            $oldFs = __DIR__ . '/../' . ltrim($hinh_anh, '/\\');
                            if (file_exists($oldFs)) {
                                @unlink($oldFs);
                            }
                        }

                        $hinh_anh = $newRelPath;
                    } else {
                        echo "<script>alert('Upload ảnh thất bại. Vui lòng thử lại.');</script>";
                    }
                }
            }

            try {
                $sql = "UPDATE PHONG 
                        SET dien_tich = :dien_tich,
                            gia_thue = :gia_thue,
                            trang_thai = :trang_thai,
                            hinh_anh = :hinh_anh,
                            mo_ta = :mo_ta
                        WHERE ma_phong = :id";
                $up = $this->conn->prepare($sql);
                $up->bindValue(':dien_tich', $dien_tich);
                $up->bindValue(':gia_thue', $gia_thue);
                $up->bindValue(':trang_thai', $trang_thai);
                $up->bindValue(':hinh_anh', $hinh_anh);
                $up->bindValue(':mo_ta', $mo_ta);
                $up->bindValue(':id', $id);

                if ($up->execute()) {
                    header("Location: index.php?controller=room&action=index");
                    exit;
                }

                echo "<script>alert('Có lỗi xảy ra!');</script>";
            } catch (PDOException $e) {
                echo "<script>alert('Lỗi: " . addslashes($e->getMessage()) . "');</script>";
            }
        }

        require_once 'views/admin/rooms/edit.php';
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=room&action=index");
            exit;
        }

        try {
            // fetch image path for cleanup
            $stmt = $this->conn->prepare("SELECT hinh_anh FROM PHONG WHERE ma_phong = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            $del = $this->conn->prepare("DELETE FROM PHONG WHERE ma_phong = :id");
            $del->bindParam(':id', $id);
            $del->execute();

            if (!empty($room['hinh_anh'])) {
                $oldFs = __DIR__ . '/../' . ltrim($room['hinh_anh'], '/\\');
                if (file_exists($oldFs)) {
                    @unlink($oldFs);
                }
            }
        } catch (PDOException $e) {
            echo "<script>alert('Không thể xóa phòng này vì đang có dữ liệu liên quan!');</script>";
            echo "<script>window.location.href='index.php?controller=room&action=index';</script>";
            return;
        }

        header("Location: index.php?controller=room&action=index");
        exit;
    }
}
?>
