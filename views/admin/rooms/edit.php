<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Sửa Phòng #<?php echo htmlspecialchars($room['ma_phong']); ?></h1>
    <a href="index.php?controller=room&action=index" class="btn btn-primary"
        style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="dien_tich">Diện tích (m2)</label>
            <input type="number" step="0.01" id="dien_tich" name="dien_tich"
                value="<?php echo htmlspecialchars($room['dien_tich']); ?>" required>
        </div>

        <div class="form-group">
            <label for="gia_thue">Giá thuê (VNĐ)</label>
            <input type="number" id="gia_thue" name="gia_thue"
                value="<?php echo htmlspecialchars($room['gia_thue']); ?>" required>
        </div>

        <div class="form-group">
            <label for="trang_thai">Trạng thái</label>
            <select id="trang_thai" name="trang_thai">
                <option value="Trong" <?php echo $room['trang_thai'] == 'Trong' ? 'selected' : ''; ?>>Trống</option>
                <option value="Da thue" <?php echo $room['trang_thai'] == 'Da thue' ? 'selected' : ''; ?>>Đã thuê</option>
                <option value="Dang sua chua" <?php echo $room['trang_thai'] == 'Dang sua chua' ? 'selected' : ''; ?>>Đang sửa
                    chữa</option>
            </select>
        </div>

        <div class="form-group">
            <label for="hinh_anh">Cập nhật hình ảnh phòng (Bỏ qua nếu giữ nguyên)</label>
            <input type="file" id="hinh_anh" name="hinh_anh" accept="image/*">
            <?php if (!empty($room['hinh_anh'])): ?>
                <div style="margin-top: 10px;">
                    <img src="<?php echo htmlspecialchars($path_to_root . $room['hinh_anh']); ?>" alt="Current Image"
                        style="max-width: 200px; border-radius: 5px;">
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="mo_ta">Mô tả phòng</label>
            <textarea id="mo_ta" name="mo_ta" rows="4"
                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; background: rgba(0,0,0,0.2); color: white;"><?php echo isset($room['mo_ta']) ? htmlspecialchars($room['mo_ta']) : ''; ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>

