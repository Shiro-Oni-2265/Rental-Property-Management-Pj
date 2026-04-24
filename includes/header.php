<?php
if (!isset($path_to_root))
    $path_to_root = './';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: " . $path_to_root . "login.php");
    exit();
}

require_once $path_to_root . 'config/database.php';
require_once $path_to_root . 'includes/functions.php';

$db = new Database();
$conn = $db->getConnection();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Manager - Quản lý Phòng Trọ</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $path_to_root; ?>assets/css/style.css">
</head>

<body>

    <div class="sidebar">
        <a href="<?php echo $path_to_root; ?>index.php" class="brand">
            <i class="fa-solid fa-building-user"></i> PT Manager
        </a>
        
        <!-- Render Admin Sidebar if role is admin -->
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <ul class="nav-links">
            <li>
                <a href="<?php echo $path_to_root; ?>index.php?controller=admin&action=dashboard" class="<?php echo isset($_GET['controller']) && $_GET['controller'] == 'admin' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?php echo $path_to_root; ?>index.php?controller=room&action=index" class="<?php echo isset($_GET['controller']) && $_GET['controller'] == 'room' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-door-open"></i> Quản lý Phòng
                </a>
            </li>
            <li>
                <a href="<?php echo $path_to_root; ?>nguoi_thue/index.php" class="">
                    <i class="fa-solid fa-users"></i> Quản lý Người thuê
                </a>
            </li>
            <li>
                <a href="<?php echo $path_to_root; ?>hop_dong/index.php" class="">
                    <i class="fa-solid fa-file-contract"></i> Quản lý Hợp đồng
                </a>
            </li>
            <li>
                <a href="<?php echo $path_to_root; ?>hoa_don/index.php" class="">
                    <i class="fa-solid fa-receipt"></i> Quản lý Hóa đơn
                </a>
            </li>
            <li>
                <a href="<?php echo $path_to_root; ?>dich_vu/index.php" class="">
                    <i class="fa-solid fa-plug-circle-bolt"></i> Dịch vụ (Điện/Nước...)
                </a>
            </li>
            <li>
                <a href="<?php echo $path_to_root; ?>bao_tri/index.php" class="">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Bảo trì
                </a>
            </li>
            <li>
                <a href="<?php echo $path_to_root; ?>phan_hoi/index.php" class="">
                    <i class="fa-solid fa-comment-dots"></i> Phản hồi
                </a>
            </li>
            <li>
                <a href="<?php echo $path_to_root; ?>index.php?controller=auth&action=logout" class="" style="color: var(--danger); margin-top: auto;">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </li>
        </ul>
        <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user'): ?>
        <ul class="nav-links">
            <li>
                <a href="<?php echo $path_to_root; ?>index.php?controller=user&action=home" class="<?php echo isset($_GET['controller']) && $_GET['controller'] == 'user' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-house-user"></i> Hợp đồng của tôi
                </a>
            </li>
            <li>
                <!-- Bạn có thể thêm các chức năng khác cho người thuê ở đây -->
                <a href="<?php echo $path_to_root; ?>user/index.php">
                    <i class="fa-solid fa-search"></i> Xem phòng trống
                </a>
            </li>
            <li>
                <a href="<?php echo $path_to_root; ?>index.php?controller=auth&action=logout" class="" style="color: var(--danger); margin-top: auto;">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </li>
        </ul>
        <?php endif; ?>
    </div>

    <div class="main-content">