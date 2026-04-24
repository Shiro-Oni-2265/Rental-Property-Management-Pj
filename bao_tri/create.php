<?php
$path_to_root = '../';
require_once '../includes/admin_guard.php';
require_once '../includes/header.php';

// Get Rooms
$rooms = $conn->query("SELECT ma_phong FROM PHONG")->fetchAll(PDO::FETCH_ASSOC);

// Handle GET params for autofill
$auto_ma_phong = $_GET['ma_phong'] ?? '';
$auto_desc = isset($_GET['desc']) ? urldecode($_GET['desc']) : '';
$ph_id = $_GET['ph_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_phong = $_POST['ma_phong'];
    $loai = $_POST['loai_bao_tri'];
    $chi_phi = $_POST['chi_phi'];
    $ngay = $_POST['ngay_bao_tri'];
    $posted_ph_id = $_POST['ph_id'] ?? '';

    try {
        $stmt = $conn->prepare("INSERT INTO BAO_TRI(ma_phong, loai_bao_tri, chi_phi, ngay_bao_tri) VALUES(:p, :l, :c, :n)");
        $stmt->bindParam(':p', $ma_phong);
        $stmt->bindParam(':l', $loai);
        $stmt->bindParam(':c', $chi_phi);
        $stmt->bindParam(':n', $ngay);
        $stmt->execute();
        
        // If it came from feedback, resolve it
        if (!empty($posted_ph_id)) {
            $stmt_resolve = $conn->prepare("UPDATE PHAN_HOI SET trang_thai = 'Da xu ly' WHERE ma_phan_hoi = :id");
            $stmt_resolve->execute([':id' => $posted_ph_id]);
            echo "<script>alert('Lưu lịch sử bảo trì và Phản hồi đã được xử lý!'); window.location.href='../phan_hoi/index.php';</script>";
            exit;
        }

        echo "<script>alert('Ghi nhận thành công!'); window.location.href='index.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<header>
    <h1>Ghi Nhận Bảo Trì</h1>
    <a href="index.php" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="">
        <?php if (!empty($ph_id)): ?>
            <input type="hidden" name="ph_id" value="<?php echo htmlspecialchars($ph_id); ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Phòng</label>
            <select name="ma_phong" required>
                <?php foreach ($rooms as $r): ?>
                    <option value="<?php echo $r['ma_phong']; ?>" <?php echo ($r['ma_phong'] == $auto_ma_phong) ? 'selected' : ''; ?>>Phòng <?php echo $r['ma_phong']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Nội dung bảo trì</label>
            <textarea name="loai_bao_tri" rows="3" required><?php echo htmlspecialchars($auto_desc); ?></textarea>
        </div>

        <div class="form-group">
            <label>Chi phí</label>
            <input type="number" name="chi_phi" required>
        </div>

        <div class="form-group">
            <label>Ngày bảo trì</label>
            <input type="date" name="ngay_bao_tri" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
