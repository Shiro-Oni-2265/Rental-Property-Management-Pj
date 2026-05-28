<?php
// File này chỉ đóng vai trò như một Alias (Đường dẫn phụ)
// Trỏ về file Database.php thực sự trong thư mục core/
// Thường dùng để tương thích ngược với các đoạn code cũ (legacy code)
require_once __DIR__ . '/../core/Database.php';
