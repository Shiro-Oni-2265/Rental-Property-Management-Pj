<?php
/*
 * Lớp RoomController
 * Nhiệm vụ: Xử lý các thao tác Quản lý phòng trọ (CRUD: Create, Read, Update, Delete) dành cho Admin.
 */
class RoomController extends Controller {

    public function __construct($db) {
        parent::__construct($db);
        // Phân quyền: Kiểm tra xem người dùng hiện tại có phải là 'admin' hay không
        // Nếu không phải, lập tức đẩy về trang Đăng nhập
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /*
     * Action: index()
     * Chức năng: Lấy danh sách tất cả phòng trọ hiển thị lên bảng quản lý
     */
    public function index() {
        // Câu lệnh SELECT lấy tất cả các phòng
        $stmt = $this->conn->query("SELECT * FROM PHONG ORDER BY ma_phong ASC");
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nạp view và truyền biến $rooms sang để render HTML
        $this->render('admin/rooms/index', ['rooms' => $rooms]);
    }

    /*
     * Action: create()
     * Chức năng: Thêm mới một phòng trọ vào CSDL
     */
    public function create() {
        // Kiểm tra nếu người dùng đã ấn nút Submit (Phương thức POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nhận dữ liệu từ Form (sử dụng toán tử ?? để tránh lỗi Undefined)
            $dien_tich = $_POST['dien_tich'] ?? null;
            $gia_thue = $_POST['gia_thue'] ?? null;
            $mo_ta = $_POST['mo_ta'] ?? '';
            $hinh_anh = null;

            // Xử lý Upload Ảnh (Nếu có file được tải lên)
            if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === UPLOAD_ERR_OK) {
                // Danh sách các định dạng ảnh được phép tải lên
                $allowed = [
                    "jpg" => "image/jpg",
                    "jpeg" => "image/jpeg",
                    "gif" => "image/gif",
                    "png" => "image/png"
                ];

                $filename = $_FILES['hinh_anh']['name'] ?? '';
                $filetype = $_FILES['hinh_anh']['type'] ?? '';
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                // Kiểm tra định dạng file
                if (!array_key_exists($ext, $allowed) || !in_array($filetype, $allowed, true)) {
                    echo "<script>alert('Vui lòng chọn đúng định dạng ảnh (jpg, jpeg, png, gif).');</script>";
                } else {
                    // Thư mục lưu trữ ảnh (lưu trên server)
                    $uploadDirFs = __DIR__ . '/../uploads/rooms/';
                    if (!file_exists($uploadDirFs)) {
                        mkdir($uploadDirFs, 0777, true); // Tạo thư mục nếu chưa có
                    }

                    // Đổi tên file để không bị trùng lặp bằng hàm uniqid()
                    $newFilename = uniqid('', true) . '-' . basename($filename);
                    if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadDirFs . $newFilename)) {
                        $hinh_anh = 'uploads/rooms/' . $newFilename; // Đường dẫn tương đối lưu vào DB
                    } else {
                        echo "<script>alert('Upload ảnh thất bại. Vui lòng thử lại.');</script>";
                    }
                }
            }

            try {
                // Câu lệnh INSERT INTO thêm dữ liệu vào bảng PHONG
                $sql = "INSERT INTO PHONG (dien_tich, gia_thue, hinh_anh, mo_ta)
                        VALUES (:dien_tich, :gia_thue, :hinh_anh, :mo_ta)";
                $stmt = $this->conn->prepare($sql);
                // Bind params
                $stmt->bindValue(':dien_tich', $dien_tich);
                $stmt->bindValue(':gia_thue', $gia_thue);
                $stmt->bindValue(':hinh_anh', $hinh_anh);
                $stmt->bindValue(':mo_ta', $mo_ta);

                // Nếu thêm thành công thì quay lại trang danh sách phòng
                if ($stmt->execute()) {
                    header("Location: index.php?controller=room&action=index");
                    exit;
                }

                echo "<script>alert('Có lỗi xảy ra!');</script>";
            } catch (PDOException $e) {
                echo "<script>alert('Lỗi: " . addslashes($e->getMessage()) . "');</script>";
            }
        }

        // Hiển thị form tạo phòng
        $this->render('admin/rooms/create');
    }

    /*
     * Action: edit()
     * Chức năng: Chỉnh sửa thông tin phòng (Cập nhật diện tích, giá, mô tả, ảnh, trạng thái)
     */
    public function edit() {
        // Lấy mã phòng từ URL (index.php?controller=room&action=edit&id=...)
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=room&action=index");
            exit;
        }

        // Lấy thông tin phòng cũ để hiển thị ra form
        $stmt = $this->conn->prepare("SELECT * FROM PHONG WHERE ma_phong = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            header("Location: index.php?controller=room&action=index");
            exit;
        }

        // Nếu admin submit form (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nhận dữ liệu mới, nếu không nhập thì giữ nguyên dữ liệu cũ
            $dien_tich = $_POST['dien_tich'] ?? $room['dien_tich'];
            $gia_thue = $_POST['gia_thue'] ?? $room['gia_thue'];
            $trang_thai = $_POST['trang_thai'] ?? $room['trang_thai'];
            $mo_ta = $_POST['mo_ta'] ?? ($room['mo_ta'] ?? '');
            $hinh_anh = $room['hinh_anh']; // Mặc định giữ nguyên ảnh cũ

            // Xử lý File Upload (Chỉ chạy khi admin chọn ảnh mới)
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

                        // Xóa file ảnh cũ trên ổ cứng (tiết kiệm dung lượng server)
                        if (!empty($hinh_anh)) {
                            $oldFs = __DIR__ . '/../' . ltrim($hinh_anh, '/\\');
                            if (file_exists($oldFs)) {
                                @unlink($oldFs); // Xóa file
                            }
                        }

                        // Gán đường dẫn ảnh mới để lưu vào DB
                        $hinh_anh = $newRelPath;
                    } else {
                        echo "<script>alert('Upload ảnh thất bại. Vui lòng thử lại.');</script>";
                    }
                }
            }

            try {
                // Câu lệnh UPDATE cập nhật dữ liệu vào bảng PHONG
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

        $this->render('admin/rooms/edit', ['room' => $room]);
    }

    /*
     * Action: delete()
     * Chức năng: Xóa phòng khỏi cơ sở dữ liệu
     */
    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=room&action=index");
            exit;
        }

        try {
            // Lấy thông tin đường dẫn ảnh để xóa ảnh cứng trên server trước
            $stmt = $this->conn->prepare("SELECT hinh_anh FROM PHONG WHERE ma_phong = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            // Xóa dữ liệu trong DB (Bảng PHONG)
            // LƯU Ý: Ràng buộc khóa ngoại (Foreign Key Constraints) sẽ khiến query này bị lỗi (bắt Exception)
            // nếu phòng này đang nằm trong 1 hợp đồng nào đó đã tồn tại.
            $del = $this->conn->prepare("DELETE FROM PHONG WHERE ma_phong = :id");
            $del->bindParam(':id', $id);
            $del->execute();

            // Xóa file ảnh vật lý
            if (!empty($room['hinh_anh'])) {
                $oldFs = __DIR__ . '/../' . ltrim($room['hinh_anh'], '/\\');
                if (file_exists($oldFs)) {
                    @unlink($oldFs);
                }
            }
        } catch (PDOException $e) {
            // Nếu có dữ liệu liên quan (Hợp đồng, hóa đơn...), MySQL sẽ báo lỗi.
            // Bắt lỗi PDOException để báo cho admin biết không được phép xóa
            echo "<script>alert('Không thể xóa phòng này vì đang có dữ liệu liên quan!');</script>";
            echo "<script>window.location.href='index.php?controller=room&action=index';</script>";
            return;
        }

        header("Location: index.php?controller=room&action=index");
        exit;
    }
}
?>
