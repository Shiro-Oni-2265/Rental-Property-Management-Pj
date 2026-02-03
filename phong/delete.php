<?php
$path_to_root = '../';
require_once '../config/database.php';
require_once '../includes/functions.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $conn->prepare("DELETE FROM PHONG WHERE ma_phong = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
             echo "<script>alert('Xóa phòng thành công!'); window.location.href='index.php';</script>";
        } else {
             echo "<script>alert('Không thể xóa phòng này (có thể đang có hợp đồng)!'); window.location.href='index.php';</script>";
        }
    } catch (PDOException $e) {
        // More user friendly error if constraint violation
        if ($e->getCode() == '23000') {
             echo "<script>alert('Không thể xóa phòng này vì đang có dữ liệu liên quan (Hợp đồng, Bảo trì...)!'); window.location.href='index.php';</script>";
        } else {
             echo "Error: " . $e->getMessage();
        }
    }
} else {
    redirect('index.php');
}
?>
