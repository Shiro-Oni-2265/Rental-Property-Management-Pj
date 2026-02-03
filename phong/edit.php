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

    try {
        $sql = "UPDATE PHONG SET dien_tich = :dien_tich, gia_thue = :gia_thue, trang_thai = :trang_thai WHERE ma_phong = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dien_tich', $dien_tich);
        $stmt->bindParam(':gia_thue', $gia_thue);
        $stmt->bindParam(':trang_thai', $trang_thai);
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
    <a href="index.php" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="">
        <div class="form-group">
            <label for="dien_tich">Diện tích (m2)</label>
            <input type="number" step="0.01" id="dien_tich" name="dien_tich" value="<?php echo $room['dien_tich']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="gia_thue">Giá thuê (VNĐ)</label>
            <input type="number" id="gia_thue" name="gia_thue" value="<?php echo $room['gia_thue']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="trang_thai">Trạng thái</label>
            <select id="trang_thai" name="trang_thai">
                <option value="Trong" <?php echo $room['trang_thai'] == 'Trong' ? 'selected' : ''; ?>>Trống</option>
                <option value="Da thue" <?php echo $room['trang_thai'] == 'Da thue' ? 'selected' : ''; ?>>Đã thuê</option>
                <option value="Dang sua chua" <?php echo $room['trang_thai'] == 'Dang sua chua' ? 'selected' : ''; ?>>Đang sửa chữa</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
