<?php
$path_to_root = '../';
require_once '../includes/admin_guard.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    
    // Check type of feedback
    $stmt = $conn->prepare("SELECT ma_nguoi_thue, loai, noi_dung FROM PHAN_HOI WHERE ma_phan_hoi = :id");
    $stmt->execute([':id' => $id]);
    $feedback = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($feedback && $feedback['loai'] === 'Yêu cầu bảo trì') {
        // Find the active room for this user
        $query = "SELECT HD.ma_phong 
                  FROM HOP_DONG_NGUOI_THUE HD_NT 
                  JOIN HOP_DONG HD ON HD_NT.ma_hop_dong = HD.ma_hop_dong 
                  WHERE HD_NT.ma_nguoi_thue = :uid AND HD.trang_thai = 'Dang thue' 
                  ORDER BY HD.ngay_bat_dau DESC LIMIT 1";
        $stmt_room = $conn->prepare($query);
        $stmt_room->execute([':uid' => $feedback['ma_nguoi_thue']]);
        $room = $stmt_room->fetch(PDO::FETCH_ASSOC);
        
        $ma_phong = $room ? $room['ma_phong'] : '';
        $desc = urlencode($feedback['noi_dung']);
        
        // Redirect to create bao tri directly
        header("Location: ../bao_tri/create.php?ph_id=" . $id . "&ma_phong=" . $ma_phong . "&desc=" . $desc);
        exit;
    } else {
        // Normal feedback, just resolve it
        $stmt_update = $conn->prepare("UPDATE PHAN_HOI SET trang_thai = 'Da xu ly' WHERE ma_phan_hoi = :id");
        $stmt_update->bindParam(':id', $id);
        $stmt_update->execute();
    }
}
redirect('index.php');
?>
