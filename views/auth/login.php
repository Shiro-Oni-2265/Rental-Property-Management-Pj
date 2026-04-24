<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống - PT Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: var(--bg-color);
        }
        .login-card {
            background-color: var(--card-bg);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        .login-card h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            background-color: var(--bg-color);
            color: var(--text-color);
            border-radius: 4px;
        }
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 1rem;
        }
        .alert {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .alert-error {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--danger);
            border: 1px solid var(--danger);
        }
        .helper-text {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>PT Manager</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="index.php?controller=auth&action=authenticate" method="POST">
            <div class="form-group">
                <label for="phone">Số điện thoại (hoặc Tên đăng nhập admin)</label>
                <input type="text" id="phone" name="phone" class="form-control" required placeholder="Nhập số điện thoại...">
            </div>
            <div class="form-group">
                <label for="cccd">CCCD (hoặc Mật khẩu admin)</label>
                <input type="password" id="cccd" name="cccd" class="form-control" required placeholder="Nhập số CCCD...">
            </div>
            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>
        <div class="helper-text">
            * Khách thuê đăng nhập bằng SĐT và CCCD<br>
            * Tài khoản Admin mặc định: admin / admin123
        </div>
    </div>
</body>
</html>
