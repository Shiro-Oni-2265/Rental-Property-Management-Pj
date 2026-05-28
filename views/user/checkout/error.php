<?php
$path_to_root = './';
require_once 'views/layouts/header.php';
?>

<div style="max-width: 600px; margin: 4rem auto; text-align: center; padding: 3rem 2rem; background: rgba(255, 255, 255, 0.05); border-radius: 1rem; backdrop-filter: blur(10px); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="width: 80px; height: 80px; background-color: rgba(239, 68, 68, 0.2); color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto 2rem;">
        <i class="fa-solid fa-xmark"></i>
    </div>
    
    <h2 style="color: #fca5a5; margin-bottom: 1rem;">Rất tiếc, đã xảy ra lỗi</h2>
    <p style="color: #e2e8f0; margin-bottom: 2rem; line-height: 1.6; font-size: 1.1rem;">
        <?php echo htmlspecialchars($error ?? 'Không thể hoàn tất quá trình đặt phòng. Vui lòng thử lại.'); ?>
    </p>

    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="index.php?controller=cart&action=index" class="btn btn-outline" style="background: transparent; border-color: rgba(255,255,255,0.2); color: white;">
            Quay lại Giỏ hàng
        </a>
        <a href="index.php" class="btn btn-primary">
            Về trang chủ
        </a>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
