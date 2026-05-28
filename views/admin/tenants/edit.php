<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Sửa Thông Tin Người Thuê</h1>
    <a href="index.php?controller=tenant&action=index" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="index.php?controller=tenant&action=edit&id=<?php echo $nt['ma_nguoi_thue']; ?>">
        <div class="form-group">
            <label for="ho_ten">Họ tên</label>
            <input type="text" id="ho_ten" name="ho_ten" value="<?php echo htmlspecialchars($nt['ho_ten']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="sdt">Số điện thoại</label>
            <input type="text" id="sdt" name="sdt" value="<?php echo htmlspecialchars($nt['so_dien_thoai']); ?>" required>
        </div>

        <div class="form-group">
            <label for="cccd">CCCD/CMND</label>
            <input type="text" id="cccd" name="cccd" value="<?php echo htmlspecialchars($nt['cccd']); ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
