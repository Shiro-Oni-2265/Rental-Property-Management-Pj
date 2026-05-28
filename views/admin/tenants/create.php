<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Thêm Người Thuê Mới</h1>
    <a href="index.php?controller=tenant&action=index" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="index.php?controller=tenant&action=create">
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

<?php require_once 'includes/footer.php'; ?>
