<?php
$path_to_root = '../';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$id = $_GET['id'];

// Get current data
$stmt = $conn->prepare("SELECT * FROM PHONG WHERE ma_phong = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    echo "<script>alert('Phòng không tồn tại!'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dien_tich = $_POST['dien_tich'];
    $gia_thue = $_POST['gia_thue'];
    $trang_thai = $_POST['trang_thai'];
    $mo_ta = isset($_POST['mo_ta']) ? $_POST['mo_ta'] : '';
    $hinh_anh = $room['hinh_anh']; // default to current image

    // Handle File Upload
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES['hinh_anh']['name'];
        $filetype = $_FILES['hinh_anh']['type'];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed))
            die("Error: Please select a valid file format.");

        if (in_array($filetype, $allowed)) {
            $upload_dir = '../uploads/rooms/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $newFilename = uniqid() . '-' . basename($filename);
            if (move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $upload_dir . $newFilename)) {
                $hinh_anh = 'uploads/rooms/' . $newFilename; // Update with new image

                // Optionally, delete the old image file if needed
                if (!empty($room['hinh_anh']) && file_exists('../' . $room['hinh_anh'])) {
                    unlink('../' . $room['hinh_anh']);
                }
            }
        }
    }

    try {
        $sql = "UPDATE PHONG SET dien_tich = :dien_tich, gia_thue = :gia_thue, trang_thai = :trang_thai, hinh_anh = :hinh_anh, mo_ta = :mo_ta WHERE ma_phong = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dien_tich', $dien_tich);
        $stmt->bindParam(':gia_thue', $gia_thue);
        $stmt->bindParam(':trang_thai', $trang_thai);
        $stmt->bindParam(':hinh_anh', $hinh_anh);
        $stmt->bindParam(':mo_ta', $mo_ta);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật thành công!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra!');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<header>
    <h1>Sửa Phòng #<?php echo $id; ?></h1>
    <a href="index.php" class="btn btn-primary"
        style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="dien_tich">Diện tích (m2)</label>
            <input type="number" step="0.01" id="dien_tich" name="dien_tich" value="<?php echo $room['dien_tich']; ?>"
                required>
        </div>

        <div class="form-group">
            <label for="gia_thue">Giá thuê (VNĐ)</label>
            <input type="number" id="gia_thue" name="gia_thue" value="<?php echo $room['gia_thue']; ?>" required>
        </div>

        <div class="form-group">
            <label for="hinh_anh">Cập nhật hình ảnh phòng (Bỏ qua nếu giữ nguyên)</label>
            <input type="file" id="hinh_anh" name="hinh_anh" accept="image/*">
            <?php if (!empty($room['hinh_anh'])): ?>
                <div style="margin-top: 10px;">
                    <img src="../<?php echo htmlspecialchars($room['hinh_anh']); ?>" alt="Current Image"
                        style="max-width: 200px; border-radius: 5px;">
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="mo_ta">Mô tả phòng</label>
            <textarea id="mo_ta" name="mo_ta" rows="4"
                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; background: rgba(0,0,0,0.2); color: white;"><?php echo isset($room['mo_ta']) ? htmlspecialchars($room['mo_ta']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="trang_thai">Trạng thái</label>
            <select id="trang_thai" name="trang_thai">
                <option value="Trong" <?php echo $room['trang_thai'] == 'Trong' ? 'selected' : ''; ?>>Trống</option>
                <option value="Da thue" <?php echo $room['trang_thai'] == 'Da thue' ? 'selected' : ''; ?>>Đã thuê</option>
                <option value="Dang sua chua" <?php echo $room['trang_thai'] == 'Dang sua chua' ? 'selected' : ''; ?>>Đang
                    sửa chữa</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>