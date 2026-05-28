<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Tạo Hợp Đồng Mới</h1>
    <a href="index.php?controller=contract&action=index" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form method="POST" action="index.php?controller=contract&action=create">
        <div class="form-group">
            <label for="ma_phong">Chọn Phòng (Trống)</label>
            <select id="ma_phong" name="ma_phong" required>
                <option value="">-- Chọn phòng --</option>
                <?php foreach ($rooms as $r): ?>
                    <option value="<?php echo $r['ma_phong']; ?>">
                        Phòng <?php echo $r['ma_phong']; ?> - <?php echo formatMoney($r['gia_thue']); ?>/tháng
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (empty($rooms)) echo "<p style='color: var(--danger); font-size: 0.8rem; margin-top: 5px;'>Không có phòng trống!</p>"; ?>
        </div>
        
        <div class="form-group">
            <label for="ma_nguoi_thue">Chọn Người Thuê (Giữ Ctrl để chọn nhiều)</label>
            <select id="ma_nguoi_thue" name="ma_nguoi_thue[]" multiple required style="height: 150px;">
                <?php foreach ($tenants as $t): ?>
                    <option value="<?php echo $t['ma_nguoi_thue']; ?>">
                        <?php echo htmlspecialchars($t['ho_ten']); ?> (<?php echo htmlspecialchars($t['cccd']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label for="ngay_bd">Ngày bắt đầu</label>
                <input type="date" id="ngay_bd" name="ngay_bd" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div>
                <label for="ngay_kt">Ngày kết thúc</label>
                <input type="date" id="ngay_kt" name="ngay_kt" value="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="tien_coc">Tiền cọc</label>
            <input type="number" id="tien_coc" name="tien_coc" required>
        </div>
        
        <button type="submit" class="btn btn-primary" <?php if (empty($rooms)) echo "disabled"; ?>>Tạo Hợp Đồng</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
