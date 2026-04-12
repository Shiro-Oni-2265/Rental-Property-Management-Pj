<div class="auth-container"
    style="max-width: 400px; margin: 4rem auto; padding: 2rem; background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid var(--border-color);">
    <h2 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Đăng nhập Cư Dân</h2>

    <?php if (!empty($error)): ?>
        <div
            style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem; font-size: 0.875rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="?controller=auth&action=login" method="POST"
        style="display: flex; flex-direction: column; gap: 1rem;">
        <div>
            <label for="phone" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Số điện thoại</label>
            <input type="text" id="phone" name="phone" pattern="^(0[3|5|7|8|9])+([0-9]{8})$"
                title="Số điện thoại gồm 10 chữ số, bắt đầu bằng 03, 05, 07, 08, hoặc 09." required
                style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; box-sizing: border-box;"
                placeholder="Nhập số điện thoại...">
        </div>

        <div>
            <label for="cccd" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Số CMND / CCCD</label>
            <input type="password" id="cccd" name="cccd" pattern="^([0-9]{9}|[0-9]{12})$"
                title="CCCD/CMND gồm 9 hoặc 12 chữ số." required
                style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; box-sizing: border-box;"
                placeholder="Dùng CCCD làm mật khẩu...">
            <div style="font-size: 0.75rem; color: var(--text-light); margin-top: 0.25rem;">(Vui lòng nhập số CCCD đã
                đăng ký lúc làm hợp đồng)</div>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">Đăng nhập</button>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.875rem;">
            Bạn chưa có tài khoản? <a href="?controller=auth&action=register"
                style="color: var(--primary-color); font-weight: 500; text-decoration: none;">Đăng ký ngay</a>
        </div>
    </form>
</div>