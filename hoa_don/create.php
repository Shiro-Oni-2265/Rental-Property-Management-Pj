<?php
$path_to_root = '../';
require_once '../includes/admin_guard.php';
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
        // Create invoice (avoid stored procedure mismatch)
        $stmt = $conn->prepare("INSERT INTO HOA_DON(ma_hop_dong, thang, nam, tong_tien, trang_thai)
                                VALUES(:ma_hd, :thang, :nam, 0, 'Chua thanh toan')");
        $stmt->bindValue(':ma_hd', $ma_hop_dong);
        $stmt->bindValue(':thang', $thang);
        $stmt->bindValue(':nam', $nam);
        $stmt->execute();

        $invoiceId = (int)$conn->lastInsertId();

        // Auto-add: TIỀN PHÒNG line item (based on room rent)
        $rentStmt = $conn->prepare("SELECT p.gia_thue
                                    FROM HOP_DONG h
                                    JOIN PHONG p ON h.ma_phong = p.ma_phong
                                    WHERE h.ma_hop_dong = :id");
        $rentStmt->bindValue(':id', $ma_hop_dong);
        $rentStmt->execute();
        $rent = $rentStmt->fetch(PDO::FETCH_ASSOC);
        $gia_thue = $rent['gia_thue'] ?? 0;

        // Ensure baseline services exist
        $tienPhongDvId = ensureServiceExists($conn, 'Tiền phòng', 0, 'tháng');
        ensureServiceExists($conn, 'Điện', 3500, 'kWh');
        ensureServiceExists($conn, 'Nước', 15000, 'm3');

        $ins = $conn->prepare("INSERT INTO CHI_TIET_HOA_DON(ma_hoa_don, ma_dich_vu, so_luong, thanh_tien)
                               VALUES(:hd, :dv, 1, :tt)");
        $ins->bindValue(':hd', $invoiceId);
        $ins->bindValue(':dv', $tienPhongDvId);
        $ins->bindValue(':tt', $gia_thue);
        $ins->execute();

        // Update total
        $sum = $conn->prepare("SELECT SUM(thanh_tien) as total FROM CHI_TIET_HOA_DON WHERE ma_hoa_don = :id");
        $sum->bindValue(':id', $invoiceId);
        $sum->execute();
        $total = $sum->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $up = $conn->prepare("UPDATE HOA_DON SET tong_tien = :total WHERE ma_hoa_don = :id");
        $up->bindValue(':total', $total);
        $up->bindValue(':id', $invoiceId);
        $up->execute();

        echo "<script>alert('Tạo hóa đơn thành công!'); window.location.href='index.php';</script>";
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
