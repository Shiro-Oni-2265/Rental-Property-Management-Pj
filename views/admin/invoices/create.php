<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Tạo Hóa Đơn Mới</h1>
    <a href="index.php?controller=invoice&action=index" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="index.php?controller=invoice&action=create">
        <div class="form-group">
            <label for="ma_hop_dong">Chọn Hợp Đồng</label>
            <select id="ma_hop_dong" name="ma_hop_dong" required>
                <option value="">-- Chọn hợp đồng --</option>
                <?php foreach ($contracts as $c): ?>
                    <option value="<?php echo $c['ma_hop_dong']; ?>">
                        #<?php echo $c['ma_hop_dong']; ?> - Phòng <?php echo $c['ma_phong']; ?> (<?php echo htmlspecialchars($c['ho_ten'] ?: 'N/A'); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (empty($contracts)) echo "<p style='color: var(--danger); font-size: 0.8rem; margin-top: 5px;'>Không có hợp đồng đang thuê nào hoạt động!</p>"; ?>
        </div>
        
        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label for="thang">Tháng</label>
                <input type="number" id="thang" name="thang" min="1" max="12" value="<?php echo date('m'); ?>" required>
            </div>
            <div>
                <label for="nam">Năm</label>
                <input type="number" id="nam" name="nam" min="2000" value="<?php echo date('Y'); ?>" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary" <?php if (empty($contracts)) echo "disabled"; ?>>Tạo Hóa Đơn</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
