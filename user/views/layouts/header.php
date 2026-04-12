<?php
$currentController = isset($_GET['controller']) ? $_GET['controller'] : 'home';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Manager - Thuê Phòng</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="site-header">
    <nav class="navbar">
        <a href="?controller=home" class="nav-brand">
            <i class="fa-solid fa-building-user"></i> PT Manager
        </a>
        
        <ul class="nav-links">
            <li><a href="?controller=home" class="<?= $currentController === 'home' ? 'active' : '' ?>">Trang chủ</a></li>
            <li><a href="?controller=room&action=list" class="<?= $currentController === 'room' ? 'active' : '' ?>">Danh sách phòng</a></li>
            <li><a href="?controller=home&action=contact">Liên hệ</a></li>
        </ul>
        
        <div class="nav-actions">
            <a href="?controller=cart" class="btn btn-outline"><i class="fa-solid fa-cart-shopping"></i> Giỏ hàng</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="?controller=account" class="btn btn-primary" style="background-color: transparent; color: var(--primary-color); border: 1px solid var(--primary-color);">
                    <i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['user_name']) ?>
                </a>
                <a href="?controller=auth&action=logout" class="btn btn-primary" style="background-color: #dc2626;"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
            <?php else: ?>
                <a href="?controller=auth&action=login" class="btn btn-primary"><i class="fa-solid fa-user"></i> Đăng nhập</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main class="container">
