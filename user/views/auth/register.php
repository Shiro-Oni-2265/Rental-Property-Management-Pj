<div class="auth-container"
    style="max-width: 450px; margin: 4rem auto; padding: 2rem; background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid var(--border-color);">
    <h2 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Tạo Tài Khoản Cư Dân</h2>

    <?php if (!empty($error)): ?>
        <div
            style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem; font-size: 0.875rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div
            style="background-color: #dcfce7; color: #15803d; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem; font-size: 0.875rem; text-align: center;">
            <i class="fa-solid fa-circle-check" style="margin-bottom: 0.5rem; font-size: 1.5rem; display: block;"></i>
            <?= htmlspecialchars($success) ?>
            <div style="margin-top: 1rem;">
                <a href="?controller=auth&action=login" class="btn btn-primary" style="display: inline-block;">Đến trang
                    Đăng nhập ngay</a>
            </div>
        </div>
    <?php else: ?>
        <form action="?controller=auth&action=register" method="POST"
            style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <label for="fullname" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Họ và tên</label>
                <input type="text" id="fullname" name="fullname"
                    pattern="^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]{2,50}$"
                    title="Họ tên cấu thành từ chữ cái và dấu cách (2-50 kí tự)." required
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; box-sizing: border-box;"
                    placeholder="VD: Nguyễn Văn A..."
                    value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>">
            </div>

            <div>
                <label for="phone" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Số điện thoại</label>
                <input type="text" id="phone" name="phone" pattern="^(0[3|5|7|8|9])+([0-9]{8})$"
                    title="Số điện thoại gồm 10 chữ số, bắt đầu bằng 03, 05, 07, 08, hoặc 09." required
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; box-sizing: border-box;"
                    placeholder="SĐT đăng nhập..."
                    value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
            </div>

            <div>
                <label for="cccd" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Số CMND / CCCD</label>
                <input type="text" id="cccd" name="cccd" pattern="^([0-9]{9}|[0-9]{12})$"
                    title="CCCD/CMND gồm 9 hoặc 12 chữ số." required
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; box-sizing: border-box;"
                    placeholder="Dùng làm Mật khẩu đăng nhập..."
                    value="<?= isset($_POST['cccd']) ? htmlspecialchars($_POST['cccd']) : '' ?>">
                <div style="font-size: 0.75rem; color: var(--text-light); margin-top: 0.25rem;">(Vui lòng nhập chính xác để
                    làm hợp đồng thuê phòng sau này)</div>
            </div>

            <button type="submit" class="btn btn-primary"
                style="margin-top: 1rem; width: 100%; background-color: #10b981;">Đăng ký Tài khoản</button>

            <div style="text-align: center; margin-top: 1.5rem; font-size: 0.875rem;">
                Đã có tài khoản? <a href="?controller=auth&action=login"
                    style="color: var(--primary-color); font-weight: 500; text-decoration: none;">Đăng nhập</a>
            </div>
        </form>
    <?php endif; ?>
</div>