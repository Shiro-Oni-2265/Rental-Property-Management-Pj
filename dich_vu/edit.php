<?php
$path_to_root = '../';
require_once '../includes/header.php';
if (!isset($_GET['id'])) redirect('index.php');
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM DICH_VU WHERE ma_dich_vu = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$dv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dv) redirect('index.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = $_POST['ten_dich_vu'];
    $gia = $_POST['don_gia'];
    $don_vi = $_POST['don_vi'];

    try {
        $stmt = $conn->prepare("UPDATE DICH_VU SET ten_dich_vu=:t, don_gia=:g, don_vi=:d WHERE ma_dich_vu=:id");
        $stmt->bindParam(':t', $ten);
        $stmt->bindParam(':g', $gia);
        $stmt->bindParam(':d', $don_vi);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật thành công!'); window.location.href='index.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<header>
    <h1>Sửa Dịch Vụ</h1>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="">
        <div class="form-group">
            <label>Tên dịch vụ</label>
            <input type="text" name="ten_dich_vu" value="<?php echo $dv['ten_dich_vu']; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Đơn giá</label>
            <input type="number" name="don_gia" value="<?php echo $dv['don_gia']; ?>" required>
        </div>

        <div class="form-group">
            <label>Đơn vị tính</label>
            <input type="text" name="don_vi" value="<?php echo $dv['don_vi']; ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
