<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Ghi Nhận Bảo Trì</h1>
    <a href="index.php?controller=maintenance&action=index" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="index.php?controller=maintenance&action=create">
        <?php if (!empty($ph_id)): ?>
            <input type="hidden" name="ph_id" value="<?php echo htmlspecialchars($ph_id); ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="ma_phong">Phòng</label>
            <select id="ma_phong" name="ma_phong" required>
                <option value="">-- Chọn phòng --</option>
                <?php foreach ($rooms as $r): ?>
                    <option value="<?php echo $r['ma_phong']; ?>" <?php echo ($r['ma_phong'] == $auto_ma_phong) ? 'selected' : ''; ?>>
                        Phòng <?php echo $r['ma_phong']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="loai_bao_tri">Nội dung bảo trì</label>
            <textarea id="loai_bao_tri" name="loai_bao_tri" rows="3" required><?php echo htmlspecialchars($auto_desc); ?></textarea>
        </div>

        <div class="form-group">
            <label for="chi_phi">Chi phí</label>
            <input type="number" id="chi_phi" name="chi_phi" required>
        </div>

        <div class="form-group">
            <label for="ngay_bao_tri">Ngày bảo trì</label>
            <input type="date" id="ngay_bao_tri" name="ngay_bao_tri" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Lưu ghi nhận</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
