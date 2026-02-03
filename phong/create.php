<?php
$path_to_root = '../';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dien_tich = $_POST['dien_tich'];
    $gia_thue = $_POST['gia_thue'];

    try {
        $sql = "CALL sp_add_phong(:dien_tich, :gia_thue)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dien_tich', $dien_tich);
        $stmt->bindParam(':gia_thue', $gia_thue);
        
        if ($stmt->execute()) {
            echo "<script>alert('Thêm phòng thành công!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra!');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<header>
    <h1>Thêm Phòng Mới</h1>
    <a href="index.php" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="">
        <div class="form-group">
            <label for="dien_tich">Diện tích (m2)</label>
            <input type="number" step="0.01" id="dien_tich" name="dien_tich" required>
        </div>
        
        <div class="form-group">
            <label for="gia_thue">Giá thuê (VNĐ)</label>
            <input type="number" id="gia_thue" name="gia_thue" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Thêm Phòng</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
