<?php
$path_to_root = '../';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM NGUOI_THUE WHERE ma_nguoi_thue = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$nt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$nt) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ho_ten = $_POST['ho_ten'];
    $sdt = $_POST['sdt'];
    $cccd = $_POST['cccd'];

    try {
        $sql = "UPDATE NGUOI_THUE SET ho_ten = :ho_ten, so_dien_thoai = :sdt, cccd = :cccd WHERE ma_nguoi_thue = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':cccd', $cccd);
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
    <h1>Sửa Thông Tin Người Thuê</h1>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="">
        <div class="form-group">
            <label for="ho_ten">Họ tên</label>
            <input type="text" id="ho_ten" name="ho_ten" value="<?php echo $nt['ho_ten']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="sdt">Số điện thoại</label>
            <input type="text" id="sdt" name="sdt" value="<?php echo $nt['so_dien_thoai']; ?>" required>
        </div>

        <div class="form-group">
            <label for="cccd">CCCD/CMND</label>
            <input type="text" id="cccd" name="cccd" value="<?php echo $nt['cccd']; ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
