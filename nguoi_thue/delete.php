<?php
$path_to_root = '../';
require_once '../config/database.php';
require_once '../includes/functions.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $conn->prepare("DELETE FROM NGUOI_THUE WHERE ma_nguoi_thue = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
             echo "<script>alert('Xóa thành công!'); window.location.href='index.php';</script>";
        } else {
             echo "<script>alert('Không thể xóa người thuê này!'); window.location.href='index.php';</script>";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
             echo "<script>alert('Không thể xóa vì người này đang có hợp đồng hoặc hóa đơn!'); window.location.href='index.php';</script>";
        } else {
             echo "Error: " . $e->getMessage();
        }
    }
} else {
    redirect('index.php');
}
?>
