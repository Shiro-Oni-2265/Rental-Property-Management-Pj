<?php
$currentController = $_GET['controller'] ?? 'user';
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
        <a href="index.php?controller=user&action=index" class="nav-brand">
            <i class="fa-solid fa-building-user"></i> PT Manager
        </a>
        
        <ul class="nav-links">
            <li><a href="index.php?controller=user&action=index" class="<?php echo ($currentController === 'user' && ($_GET['action'] ?? '') !== 'home') ? 'active' : ''; ?>">Trang chủ</a></li>
            <li><a href="index.php?controller=user_room&action=list" class="<?php echo $currentController === 'user_room' ? 'active' : ''; ?>">Danh sách phòng</a></li>
            <li><a href="index.php?controller=user&action=index#about">Liên hệ</a></li>
        </ul>
        
        <div class="nav-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?controller=cart&action=index" class="btn btn-outline <?php echo $currentController === 'cart' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng
                </a>
                <a href="index.php?controller=account&action=index" class="btn btn-primary <?php echo $currentController === 'account' ? 'active' : ''; ?>" style="background-color: transparent; color: var(--primary); border: 1px solid var(--primary);">
                    <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </a>
                <a href="index.php?controller=auth&action=logout" class="btn btn-primary" style="background-color: #dc2626; border-color: #dc2626;"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
            <?php else: ?>
                <a href="index.php?controller=auth&action=login" class="btn btn-primary"><i class="fa-solid fa-user"></i> Đăng nhập</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main class="container">
