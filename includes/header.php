<?php
if (!isset($path_to_root)) $path_to_root = './';
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
    
    <ul class="nav-links">
        <li>
            <a href="<?php echo $path_to_root; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' && dirname($_SERVER['PHP_SELF']) == '/KTX_Website' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?php echo $path_to_root; ?>phong/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/phong/') !== false ? 'active' : ''; ?>">
                <i class="fa-solid fa-door-open"></i> Quản lý Phòng
            </a>
        </li>
        <li>
            <a href="<?php echo $path_to_root; ?>nguoi_thue/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/nguoi_thue/') !== false ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i> Người thuê
            </a>
        </li>
        <li>
            <a href="<?php echo $path_to_root; ?>hop_dong/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/hop_dong/') !== false ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-contract"></i> Hợp đồng
            </a>
        </li>
        <li>
            <a href="<?php echo $path_to_root; ?>dich_vu/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/dich_vu/') !== false ? 'active' : ''; ?>">
                <i class="fa-solid fa-bolt"></i> Dịch vụ
            </a>
        </li>
        <li>
            <a href="<?php echo $path_to_root; ?>hoa_don/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/hoa_don/') !== false ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i> Hóa đơn
            </a>
        </li>
        <li>
            <a href="<?php echo $path_to_root; ?>phan_hoi/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/phan_hoi/') !== false ? 'active' : ''; ?>">
                <i class="fa-solid fa-comments"></i> Phản hồi
            </a>
        </li>
        <li>
            <a href="<?php echo $path_to_root; ?>bao_tri/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/bao_tri/') !== false ? 'active' : ''; ?>">
                <i class="fa-solid fa-screwdriver-wrench"></i> Bảo trì
            </a>
        </li>
    </ul>
</div>

<div class="main-content">
