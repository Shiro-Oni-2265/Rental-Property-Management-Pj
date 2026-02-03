<?php
$path_to_root = '../';
require_once '../config/database.php';
require_once '../includes/functions.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("UPDATE PHAN_HOI SET trang_thai = 'Da xu ly' WHERE ma_phan_hoi = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}
redirect('index.php');
?>
