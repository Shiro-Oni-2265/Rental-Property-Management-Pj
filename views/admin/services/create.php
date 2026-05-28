<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Thêm Dịch Vụ Mới</h1>
    <a href="index.php?controller=service&action=index" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="index.php?controller=service&action=create">
        <div class="form-group">
            <label for="ten_dich_vu">Tên dịch vụ</label>
            <input type="text" id="ten_dich_vu" name="ten_dich_vu" required>
        </div>
        
        <div class="form-group">
            <label for="don_gia">Đơn giá</label>
            <input type="number" id="don_gia" name="don_gia" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="don_vi">Đơn vị (ví dụ: kWh, m3, tháng...)</label>
            <input type="text" id="don_vi" name="don_vi" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Thêm Dịch Vụ</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
