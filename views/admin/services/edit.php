<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Chỉnh Sửa Dịch Vụ</h1>
    <a href="index.php?controller=service&action=index" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="index.php?controller=service&action=edit&id=<?php echo $sv['ma_dich_vu']; ?>">
        <div class="form-group">
            <label for="ten_dich_vu">Tên dịch vụ</label>
            <input type="text" id="ten_dich_vu" name="ten_dich_vu" value="<?php echo htmlspecialchars($sv['ten_dich_vu']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="don_gia">Đơn giá</label>
            <input type="number" id="don_gia" name="don_gia" step="0.01" value="<?php echo htmlspecialchars($sv['don_gia']); ?>" required>
        </div>

        <div class="form-group">
            <label for="don_vi">Đơn vị (ví dụ: kWh, m3, tháng...)</label>
            <input type="text" id="don_vi" name="don_vi" value="<?php echo htmlspecialchars($sv['don_vi']); ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
