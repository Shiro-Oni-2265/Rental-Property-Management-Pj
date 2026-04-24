<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    $toRoot = $path_to_root ?? './';
    header("Location: {$toRoot}index.php?controller=auth&action=login");
    exit;
}

