<?php
session_start();
$error = '';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $cccd = $_POST['cccd'] ?? '';

    // Xác nhận CCCD: phải là 9 hoặc 12 số
    if (!preg_match('/^([0-9]{9}|[0-9]{12})$/', $cccd)) {
        $error = 'CCCD/CMND không hợp lệ. Phải bao gồm 9 hoặc 12 chữ số.';
    // Hardcode tài khoản admin (yêu cầu CCCD để đăng nhập)
    } elseif ($username === 'admin' && $cccd === '001234567890') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Tên tài khoản hoặc số CCCD không chính xác!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Quản trị - PT Manager</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --bg-body: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: var(--bg-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Abstract Background Elements */
        .bg-shape {
            position: absolute;
            filter: blur(100px);
            opacity: 0.5;
            z-index: 0;
            animation: pulse 10s infinite alternate;
        }

        .bg-shape-1 {
            width: 400px;
            height: 400px;
            background: #3b82f6;
            top: -100px;
            left: -100px;
            border-radius: 50%;
        }

        .bg-shape-2 {
            width: 500px;
            height: 500px;
            background: #8b5cf6;
            bottom: -150px;
            right: -100px;
            border-radius: 50%;
            animation-delay: -5s;
        }

        .bg-shape-3 {
            width: 300px;
            height: 300px;
            background: #ec4899;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
        }

        @keyframes pulse {
            0% {
                transform: scale(1) translate(0, 0);
                opacity: 0.3;
            }

            50% {
                transform: scale(1.2) translate(50px, -50px);
                opacity: 0.6;
            }

            100% {
                transform: scale(0.9) translate(-50px, 50px);
                opacity: 0.3;
            }
        }

        /* Glassmorphism Card */
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            z-index: 10;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .login-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #94a3b8;
            font-size: 0.95rem;
            font-weight: 300;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            color: #e2e8f0;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control:focus {
            background: rgba(15, 23, 42, 0.6);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .form-control:focus+.input-icon {
            color: var(--primary);
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 1rem;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(99, 102, 241, 0.6);
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border-left: 4px solid #ef4444;
            color: #fca5a5;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }
        }

        .login-footer {
            margin-top: 2rem;
            text-align: center;
            color: #64748b;
            font-size: 0.85rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            border-radius: 9999px;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>

    <!-- Animated Abstract Backgrounds -->
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>
    <div class="bg-shape bg-shape-3"></div>

    <div class="login-card">
        <div class="logo-container">
            <span class="role-badge">Administrator</span>
            <br>
            <i class="fa-solid fa-building-shield logo-icon"></i>
            <h1 class="login-title">PT Manager</h1>
            <p class="login-subtitle">Hệ thống quản lý phòng trọ cao cấp</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">Tài khoản quản trị viên</label>
                <div class="input-group">
                    <input type="text" name="username" class="form-control" value="admin" required
                        placeholder="Nhập tên tài khoản...">
                    <i class="fa-solid fa-user-shield input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Xác nhận Số CMND / CCCD</label>
                <div class="input-group">
                    <input type="password" name="cccd" class="form-control" value="001234567890" pattern="^([0-9]{9}|[0-9]{12})$" title="CCCD/CMND gồm 9 hoặc 12 chữ số" required
                        placeholder="Nhập CCCD (9 hoặc 12 số)...">
                    <i class="fa-solid fa-id-card input-icon"></i>
                </div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.5rem; padding-left: 0.5rem;">
                    (Dùng CCCD định danh để đăng nhập bảo mật)
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Đăng Nhập <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i>
            </button>
        </form>

        <div class="login-footer">
            &copy;
            <?php echo date('Y'); ?> PT Manager. Bảo mật tuyệt đối.
        </div>
    </div>

</body>

</html>