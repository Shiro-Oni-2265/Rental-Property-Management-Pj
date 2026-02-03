<?php
$path_to_root = '../';
require_once '../includes/header.php';

// Get Empty Rooms
$rooms_stmt = $conn->query("SELECT * FROM PHONG WHERE trang_thai = 'Trong'");
$rooms = $rooms_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get All Tenants
$tenants_stmt = $conn->query("SELECT * FROM NGUOI_THUE ORDER BY ho_ten ASC");
$tenants = $tenants_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_phong = $_POST['ma_phong'];
    $ma_nguoi_thue = $_POST['ma_nguoi_thue']; // Array
    $ngay_bd = $_POST['ngay_bd'];
    $ngay_kt = $_POST['ngay_kt'];
    $tien_coc = $_POST['tien_coc'];

    try {
        $conn->beginTransaction();

        // 1. Create Contract
        $sql = "CALL sp_create_hop_dong(:ma_phong, :ngay_bd, :ngay_kt, :tien_coc)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->bindParam(':ngay_bd', $ngay_bd);
        $stmt->bindParam(':ngay_kt', $ngay_kt);
        $stmt->bindParam(':tien_coc', $tien_coc);
        $stmt->execute();
        
        // Get the generated ID (Assuming auto_increment works and lastInsertId returns it, or query max)
        // Note: With stored procedure, lastInsertId might give 0 on some drivers unless SELECT LAST_INSERT_ID() is returned.
        // I will use a direct query to get MAX id or trust PDO.
        // A safer way is modifying the SP to return ID, but I can't modify DB easily.
        // I'll grab the last inserted ID from HOP_DONG.
        $stmt = $conn->query("SELECT MAX(ma_hop_dong) as id FROM HOP_DONG");
        $last_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

        // 2. Link Tenants
        $sql_link = "INSERT INTO HOP_DONG_NGUOI_THUE(ma_hop_dong, ma_nguoi_thue) VALUES (:ma_hd, :ma_nt)";
        $stmt_link = $conn->prepare($sql_link);
        
        foreach ($ma_nguoi_thue as $nt_id) {
            $stmt_link->bindParam(':ma_hd', $last_id);
            $stmt_link->bindParam(':ma_nt', $nt_id);
            $stmt_link->execute();
        }

        // Room status update is handled by formatting Trigger `trg_hop_dong_insert`.
        // So no need to manually update Room status.

        $conn->commit();
        echo "<script>alert('Tạo hợp đồng thành công!'); window.location.href='index.php';</script>";
        
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "<script>alert('Lỗi: " . $e->getMessage() . "');</script>";
    }
}
?>

<header>
    <h1>Tạo Hợp Đồng Mới</h1>
    <a href="index.php" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form method="POST" action="">
        <div class="form-group">
            <label for="ma_phong">Chọn Phòng (Trống)</label>
            <select id="ma_phong" name="ma_phong" required>
                <option value="">-- Chọn phòng --</option>
                <?php foreach ($rooms as $r): ?>
                    <option value="<?php echo $r['ma_phong']; ?>">
                        Phòng <?php echo $r['ma_phong']; ?> - <?php echo formatMoney($r['gia_thue']); ?>/tháng
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (empty($rooms)) echo "<p style='color: var(--danger); font-size: 0.8rem; margin-top: 5px;'>Không có phòng trống!</p>"; ?>
        </div>
        
        <div class="form-group">
            <label for="ma_nguoi_thue">Chọn Người Thuê (Giữ Ctrl để chọn nhiều)</label>
            <select id="ma_nguoi_thue" name="ma_nguoi_thue[]" multiple required style="height: 150px;">
                <?php foreach ($tenants as $t): ?>
                    <option value="<?php echo $t['ma_nguoi_thue']; ?>">
                        <?php echo $t['ho_ten']; ?> (<?php echo $t['cccd']; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label for="ngay_bd">Ngày bắt đầu</label>
                <input type="date" id="ngay_bd" name="ngay_bd" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div>
                <label for="ngay_kt">Ngày kết thúc</label>
                <input type="date" id="ngay_kt" name="ngay_kt" value="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="tien_coc">Tiền cọc</label>
            <input type="number" id="tien_coc" name="tien_coc" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Tạo Hợp Đồng</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
