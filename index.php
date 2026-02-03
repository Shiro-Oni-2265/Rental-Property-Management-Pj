<?php
$path_to_root = './';
require_once 'includes/header.php';

// Fetch Statistics
// 1. Rooms
$stmt = $conn->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN trang_thai = 'Trong' THEN 1 ELSE 0 END) as trong,
    SUM(CASE WHEN trang_thai = 'Da thue' THEN 1 ELSE 0 END) as da_thue
    FROM PHONG");
$room_stats = $stmt->fetch(PDO::FETCH_ASSOC);

// 2. Tenants
$stmt = $conn->query("SELECT COUNT(*) as total FROM NGUOI_THUE");
$tenant_stats = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Pending Invoices
$stmt = $conn->query("SELECT COUNT(*) as total, SUM(tong_tien) as revenue FROM HOA_DON WHERE trang_thai = 'Chua thanh toan'");
$invoice_stats = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<header>
    <h1>Dashboard</h1>
</header>

<div class="card-grid">
    <div class="card stat-card">
        <h3>Tổng số phòng</h3>
        <i class="fa-solid fa-door-closed icon"></i>
        <div class="value"><?php echo $room_stats['total']; ?></div>
        <div style="font-size: 0.9rem; color: var(--text-muted); margin-top: 5px;">
            <span style="color: var(--success)"><?php echo $room_stats['trong']; ?> Trống</span> | 
            <span style="color: var(--danger)"><?php echo $room_stats['da_thue']; ?> Đã thuê</span>
        </div>
    </div>
    
    <div class="card stat-card">
        <h3>Số người thuê</h3>
        <i class="fa-solid fa-users icon"></i>
        <div class="value"><?php echo $tenant_stats['total']; ?></div>
    </div>
    
    <div class="card stat-card">
        <h3>Hóa đơn chưa thu</h3>
        <i class="fa-solid fa-file-invoice-dollar icon"></i>
        <div class="value"><?php echo $invoice_stats['total']; ?></div>
        <div style="font-size: 0.9rem; color: var(--warning); margin-top: 5px;">
            Ước tính: <?php echo formatMoney($invoice_stats['revenue'] ?? 0); ?>
        </div>
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3>Hoạt động gần đây (Hợp đồng mới)</h3>
        <a href="hop_dong/index.php" class="btn btn-sm btn-primary">Xem tất cả</a>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã HĐ</th>
                    <th>Phòng</th>
                    <th>Ngày bắt đầu</th>
                    <th>Tiền cọc</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM HOP_DONG ORDER BY ma_hop_dong DESC LIMIT 5");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>#" . $row['ma_hop_dong'] . "</td>";
                    echo "<td>Phòng " . $row['ma_phong'] . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['ngay_bat_dau'])) . "</td>";
                    echo "<td>" . formatMoney($row['tien_coc']) . "</td>";
                    echo "<td><span class='badge badge-primary'>" . $row['trang_thai'] . "</span></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
