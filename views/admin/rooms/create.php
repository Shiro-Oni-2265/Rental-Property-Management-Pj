<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Thêm Phòng Mới</h1>
    <a href="index.php?controller=room&action=index" class="btn btn-primary"
        style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="dien_tich">Diện tích (m2)</label>
            <input type="number" step="0.01" id="dien_tich" name="dien_tich" required>
        </div>

        <div class="form-group">
            <label for="gia_thue">Giá thuê (VNĐ)</label>
            <input type="number" id="gia_thue" name="gia_thue" required>
        </div>

        <div class="form-group">
            <label for="hinh_anh">Hình ảnh phòng</label>
            <input type="file" id="hinh_anh" name="hinh_anh" accept="image/*">
        </div>

        <div class="form-group">
            <label for="mo_ta">Mô tả phòng</label>
            <textarea id="mo_ta" name="mo_ta" rows="4"
                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; background: rgba(0,0,0,0.2); color: white;"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Thêm Phòng</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>

