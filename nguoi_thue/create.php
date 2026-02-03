<?php
$path_to_root = '../';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ho_ten = $_POST['ho_ten'];
    $sdt = $_POST['sdt'];
    $cccd = $_POST['cccd'];

    try {
        $sql = "CALL sp_add_nguoi_thue(:ho_ten, :sdt, :cccd)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':cccd', $cccd);
        
        if ($stmt->execute()) {
            echo "<script>alert('Thêm người thuê thành công!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra!');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<header>
    <h1>Thêm Người Thuê Mới</h1>
    <a href="index.php" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="">
        <div class="form-group">
            <label for="ho_ten">Họ tên</label>
            <input type="text" id="ho_ten" name="ho_ten" required>
        </div>
        
        <div class="form-group">
            <label for="sdt">Số điện thoại</label>
            <input type="text" id="sdt" name="sdt" required>
        </div>

        <div class="form-group">
            <label for="cccd">CCCD/CMND</label>
            <input type="text" id="cccd" name="cccd" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Thêm Người Thuê</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
