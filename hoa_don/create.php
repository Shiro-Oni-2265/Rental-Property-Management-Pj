<?php
$path_to_root = '../';
require_once '../includes/header.php';

// Get Active Contracts
$contracts_stmt = $conn->query("SELECT h.ma_hop_dong, p.ma_phong, n.ho_ten 
                                FROM HOP_DONG h 
                                JOIN PHONG p ON h.ma_phong = p.ma_phong
                                LEFT JOIN HOP_DONG_NGUOI_THUE hdnt ON h.ma_hop_dong = hdnt.ma_hop_dong
                                LEFT JOIN NGUOI_THUE n ON hdnt.ma_nguoi_thue = n.ma_nguoi_thue
                                WHERE h.trang_thai = 'Dang thue'
                                GROUP BY h.ma_hop_dong");
$contracts = $contracts_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_hop_dong = $_POST['ma_hop_dong'];
    $thang = $_POST['thang'];
    $nam = $_POST['nam'];

    try {
        $sql = "CALL sp_create_hoa_don(:ma_hd, :thang, :nam)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ma_hd', $ma_hop_dong);
        $stmt->bindParam(':thang', $thang);
        $stmt->bindParam(':nam', $nam);
        
        if ($stmt->execute()) {
            echo "<script>alert('Tạo hóa đơn thành công!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Lỗi!');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<header>
    <h1>Tạo Hóa Đơn Mới</h1>
    <a href="index.php" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="">
        <div class="form-group">
            <label for="ma_hop_dong">Chọn Hợp Đồng</label>
            <select id="ma_hop_dong" name="ma_hop_dong" required>
                <?php foreach ($contracts as $c): ?>
                    <option value="<?php echo $c['ma_hop_dong']; ?>">
                        #<?php echo $c['ma_hop_dong']; ?> - Phòng <?php echo $c['ma_phong']; ?> (<?php echo $c['ho_ten']; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label for="thang">Tháng</label>
                <input type="number" id="thang" name="thang" min="1" max="12" value="<?php echo date('m'); ?>" required>
            </div>
            <div>
                <label for="nam">Năm</label>
                <input type="number" id="nam" name="nam" min="2000" value="<?php echo date('Y'); ?>" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Tạo Hóa Đơn</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
