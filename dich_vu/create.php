<?php
$path_to_root = '../';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = $_POST['ten_dich_vu'];
    $gia = $_POST['don_gia'];
    $don_vi = $_POST['don_vi'];

    try {
        $stmt = $conn->prepare("INSERT INTO DICH_VU(ten_dich_vu, don_gia, don_vi) VALUES(:t, :g, :d)");
        $stmt->bindParam(':t', $ten);
        $stmt->bindParam(':g', $gia);
        $stmt->bindParam(':d', $don_vi);
        
        if ($stmt->execute()) {
            echo "<script>alert('Thêm thành công!'); window.location.href='index.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<header>
    <h1>Thêm Dịch Vụ Mới</h1>
    <a href="index.php" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="">
        <div class="form-group">
            <label>Tên dịch vụ</label>
            <input type="text" name="ten_dich_vu" required placeholder="Ví dụ: Điện, Nước, Internet">
        </div>
        
        <div class="form-group">
            <label>Đơn giá</label>
            <input type="number" name="don_gia" required>
        </div>

        <div class="form-group">
            <label>Đơn vị tính</label>
            <input type="text" name="don_vi" required placeholder="Ví dụ: kWh, m3, tháng">
        </div>
        
        <button type="submit" class="btn btn-primary">Thêm Dịch Vụ</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
