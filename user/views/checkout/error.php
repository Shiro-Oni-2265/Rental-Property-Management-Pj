<div style="max-width: 600px; margin: 4rem auto; text-align: center; padding: 3rem 2rem; background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #fecaca;">
    <div style="width: 80px; height: 80px; background-color: #fee2e2; color: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto 2rem;">
        <i class="fa-solid fa-xmark"></i>
    </div>
    
    <h2 style="color: #991b1b; margin-bottom: 1rem;">Rất tiếc, đã xảy ra lỗi</h2>
    <p style="color: var(--text-color); margin-bottom: 2rem; line-height: 1.6; font-size: 1.1rem;">
        <?= htmlspecialchars($error ?? 'Không thể hoàn tất quá trình đặt phòng. Vui lòng thử lại.') ?>
    </p>

    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="?controller=cart" class="btn btn-outline">
            Quay lại Giỏ hàng
        </a>
        <a href="?controller=home" class="btn btn-primary">
            Về trang chủ
        </a>
    </div>
</div>
