<?php
$path_to_root = '../';
require_once '../config/database.php';
require_once '../includes/functions.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $conn->prepare("UPDATE HOP_DONG SET trang_thai = 'Huy' WHERE ma_hop_dong = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
             // Trigger trg_hop_dong_update will clean up Room status automatically
             echo "<script>alert('Kết thúc hợp đồng thành công!'); window.location.href='index.php';</script>";
        } else {
             echo "<script>alert('Lỗi!'); window.location.href='index.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    redirect('index.php');
}
?>
