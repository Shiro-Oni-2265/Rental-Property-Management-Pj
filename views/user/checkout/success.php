<?php
$path_to_root = './';
require_once 'views/layouts/header.php';
?>

<div style="max-width: 600px; margin: 4rem auto; text-align: center; padding: 3rem 2rem; background: rgba(255, 255, 255, 0.05); border-radius: 1rem; backdrop-filter: blur(10px); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="width: 80px; height: 80px; background-color: rgba(16, 185, 129, 0.2); color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto 2rem;">
        <i class="fa-solid fa-check"></i>
    </div>
    
    <h2 style="color: #a7f3d0; margin-bottom: 1rem;">Đặt phòng thành công!</h2>
    <p style="color: #e2e8f0; margin-bottom: 2rem; line-height: 1.6;">
        Hóa đơn và hợp đồng dự kiến đã được tạo thành công trên hệ thống. 
        <br>Vui lòng đến Ban Quản Lý KTX trong vòng 24h để hoàn tất thủ tục thanh toán tiền cọc, nhận phòng và ký hợp đồng chính thức.
    </p>

    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="index.php?controller=account&action=index" class="btn btn-outline" style="background: transparent; border-color: rgba(255,255,255,0.2); color: white;">
            Xem tài khoản
        </a>
        <a href="index.php" class="btn btn-primary">
            Về trang chủ
        </a>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
