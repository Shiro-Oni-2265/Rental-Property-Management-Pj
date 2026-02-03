<?php
$path_to_root = '../';
require_once '../config/database.php';
require_once '../includes/functions.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("DELETE FROM DICH_VU WHERE ma_dich_vu = :id");
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
             redirect('index.php');
        }
    } catch (PDOException $e) {
        echo "<script>alert('Không thể xóa dịch vụ này vì đã được sử dụng trong hóa đơn!'); window.location.href='index.php';</script>";
    }
}
?>
