<?php
$path_to_root = '../';
require_once '../includes/header.php';

if (!isset($_GET['id'])) redirect('index.php');
$id = $_GET['id'];

// Get Invoice Info
$stmt = $conn->prepare("SELECT hd.*, h.ma_phong, p.gia_thue 
                        FROM HOA_DON hd 
                        JOIN HOP_DONG h ON hd.ma_hop_dong = h.ma_hop_dong
                        JOIN PHONG p ON h.ma_phong = p.ma_phong
                        WHERE hd.ma_hoa_don = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) redirect('index.php');

// Handle Add Service info
if (isset($_POST['add_service'])) {
    $ma_dv = $_POST['ma_dich_vu'];
    $so_luong = $_POST['so_luong'];
    
    // Get Service Price
    $dv_stmt = $conn->prepare("SELECT don_gia FROM DICH_VU WHERE ma_dich_vu = :id");
    $dv_stmt->bindParam(':id', $ma_dv);
    $dv_stmt->execute();
    $dv = $dv_stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($dv) {
        $thanh_tien = $dv['don_gia'] * $so_luong;
        
        // Insert Detail
        $ins_stmt = $conn->prepare("INSERT INTO CHI_TIET_HOA_DON(ma_hoa_don, ma_dich_vu, so_luong, thanh_tien) VALUES (:hd, :dv, :sl, :tt)");
        $ins_stmt->bindParam(':hd', $id);
        $ins_stmt->bindParam(':dv', $ma_dv);
        $ins_stmt->bindParam(':sl', $so_luong);
        $ins_stmt->bindParam(':tt', $thanh_tien);
        $ins_stmt->execute();
        
        // Update Total
        updateInvoiceTotal($conn, $id);
        
        // Refresh
        echo "<script>window.location.href='details.php?id=$id';</script>";
    }
}

// Handle Payment
if (isset($_POST['pay'])) {
    $up_stmt = $conn->prepare("UPDATE HOA_DON SET trang_thai = 'Da thanh toan' WHERE ma_hoa_don = :id");
    $up_stmt->bindParam(':id', $id);
    $up_stmt->execute();
    echo "<script>window.location.href='details.php?id=$id';</script>";
}

// Handle Add Room Price (Special Case)
if (isset($_POST['add_room_price'])) {
     // Check if 'Tien phong' service exists? Or just add it as a manual entry?
     // Schema requires link to DICH_VU.
     // So we must have a service called 'Tien phong'.
     // Look for it or ask user to create it.
     // For now, I will assume user works with Services manually.
     // Or I can insert a fake service record if not exists? No, better not mess.
     echo "<script>alert('Vui lòng thêm dịch vụ Tiền Phòng vào danh sách dịch vụ trước!');</script>";
}

function updateInvoiceTotal($conn, $id) {
    $sum_stmt = $conn->prepare("SELECT SUM(thanh_tien) as total FROM CHI_TIET_HOA_DON WHERE ma_hoa_don = :id");
    $sum_stmt->bindParam(':id', $id);
    $sum_stmt->execute();
    $total = $sum_stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    
    $up_stmt = $conn->prepare("UPDATE HOA_DON SET tong_tien = :total WHERE ma_hoa_don = :id");
    $up_stmt->bindParam(':total', $total);
    $up_stmt->bindParam(':id', $id);
    $up_stmt->execute();
}

// Get Details
$details_stmt = $conn->prepare("SELECT ct.*, dv.ten_dich_vu, dv.don_vi, dv.don_gia 
                                FROM CHI_TIET_HOA_DON ct 
                                JOIN DICH_VU dv ON ct.ma_dich_vu = dv.ma_dich_vu 
                                WHERE ct.ma_hoa_don = :id");
$details_stmt->bindParam(':id', $id);
$details_stmt->execute();

// Get Services List for Form
$services = $conn->query("SELECT * FROM DICH_VU")->fetchAll(PDO::FETCH_ASSOC);

?>

<header>
    <h1>Chi Tiết Hóa Đơn #<?php echo $id; ?></h1>
    <a href="index.php" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card-grid" style="grid-template-columns: 2fr 1fr;">
    <!-- Left: Details -->
    <div class="card">
        <h3>Các khoản thu</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Dịch vụ</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Đơn vị</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $details_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['ten_dich_vu']; ?></td>
                        <td><?php echo formatMoney($row['don_gia']); ?></td>
                        <td><?php echo $row['so_luong']; ?></td>
                        <td><?php echo $row['don_vi']; ?></td>
                        <td><?php echo formatMoney($row['thanh_tien']); ?></td>
                        <td>
                            <!-- Delete detail button logic could go here -->
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <tr style="font-weight: bold; background: rgba(255,255,255,0.05);">
                        <td colspan="4" style="text-align: right;">TỔNG CỘNG:</td>
                        <td colspan="2" style="font-size: 1.2rem; color: var(--success);"><?php echo formatMoney($invoice['tong_tien']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <?php if ($invoice['trang_thai'] != 'Da thanh toan'): ?>
        <form method="POST" style="margin-top: 1rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
             <h4>Thêm dịch vụ</h4>
             <div style="display: flex; gap: 1rem; align-items: flex-end;">
                 <div class="form-group" style="flex: 2; margin-bottom: 0;">
                     <label>Dịch vụ</label>
                     <select name="ma_dich_vu" required>
                         <?php foreach ($services as $s): ?>
                         <option value="<?php echo $s['ma_dich_vu']; ?>">
                             <?php echo $s['ten_dich_vu']; ?> (<?php echo formatMoney($s['don_gia']); ?>/<?php echo $s['don_vi']; ?>)
                         </option>
                         <?php endforeach; ?>
                     </select>
                 </div>
                 <div class="form-group" style="flex: 1; margin-bottom: 0;">
                     <label>Số lượng</label>
                     <input type="number" name="so_luong" step="0.01" value="1" required>
                 </div>
                 <button type="submit" name="add_service" class="btn btn-primary" style="margin-bottom: 0;">Thêm</button>
             </div>
        </form>
        
        <div style="margin-top: 2rem; text-align: right;">
            <form method="POST">
                <button type="submit" name="pay" class="btn btn-success" style="background: var(--success); color: white;" onclick="return confirm('Xác nhận thanh toán?')">
                    <i class="fa-solid fa-check"></i> Xác nhận Đã Thanh Toán
                </button>
            </form>
        </div>
        <?php else: ?>
            <div style="margin-top: 1rem; text-align: center; color: var(--success); font-weight: bold; font-size: 1.2rem; border: 2px dashed var(--success); padding: 1rem; border-radius: 8px;">
                ĐÃ THANH TOÁN
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Right: Info -->
    <div class="card">
        <h3>Thông tin</h3>
        <p style="margin-bottom: 0.5rem; color: var(--text-muted);">Hợp đồng:</p>
        <div style="font-size: 1.1rem; margin-bottom: 1.5rem;">#<?php echo $invoice['ma_hop_dong']; ?></div>
        
        <p style="margin-bottom: 0.5rem; color: var(--text-muted);">Phòng:</p>
        <div style="font-size: 1.1rem; margin-bottom: 1.5rem;">Phòng <?php echo $invoice['ma_phong']; ?></div>
        
        <p style="margin-bottom: 0.5rem; color: var(--text-muted);">Giá phòng:</p>
        <div style="font-size: 1.1rem; margin-bottom: 1.5rem;"><?php echo formatMoney($invoice['gia_thue']); ?></div>
        
        <p style="margin-bottom: 0.5rem; color: var(--text-muted);">Tháng/Năm:</p>
        <div style="font-size: 1.1rem; margin-bottom: 1.5rem;"><?php echo $invoice['thang'] . '/' . $invoice['nam']; ?></div>
        
        <div class="badge <?php echo $invoice['trang_thai'] == 'Da thanh toan' ? 'badge-success' : 'badge-warning'; ?>" style="font-size: 1rem; display: inline-block;">
            <?php echo $invoice['trang_thai']; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
