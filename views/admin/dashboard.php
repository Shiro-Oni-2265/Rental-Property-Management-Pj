<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Dashboard</h1>
</header>

<div class="card-grid">
    <div class="card stat-card">
        <h3>Tổng số phòng</h3>
        <i class="fa-solid fa-door-closed icon"></i>
        <div class="value"><?php echo $room_stats['total'] ?? 0; ?></div>
        <div style="font-size: 0.9rem; color: var(--text-muted); margin-top: 5px;">
            <span style="color: var(--success)"><?php echo $room_stats['trong'] ?? 0; ?> Trống</span> | 
            <span style="color: var(--danger)"><?php echo $room_stats['da_thue'] ?? 0; ?> Đã thuê</span>
        </div>
    </div>
    
    <div class="card stat-card">
        <h3>Số người thuê</h3>
        <i class="fa-solid fa-users icon"></i>
        <div class="value"><?php echo $tenant_stats['total'] ?? 0; ?></div>
    </div>
    
    <div class="card stat-card">
        <h3>Hóa đơn chưa thu</h3>
        <i class="fa-solid fa-file-invoice-dollar icon"></i>
        <div class="value"><?php echo $invoice_stats['total'] ?? 0; ?></div>
        <div style="font-size: 0.9rem; color: var(--warning); margin-top: 5px;">
            Ước tính: <?php echo formatMoney($invoice_stats['revenue'] ?? 0); ?>
        </div>
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3>Hoạt động gần đây (Hợp đồng mới)</h3>
        <a href="index.php?controller=contract&action=index" class="btn btn-sm btn-primary">Xem tất cả</a>
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
                <?php foreach ($recent_contracts as $row): ?>
                    <tr>
                        <td>#<?php echo $row['ma_hop_dong']; ?></td>
                        <td>Phòng <?php echo $row['ma_phong']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['ngay_bat_dau'])); ?></td>
                        <td><?php echo formatMoney($row['tien_coc']); ?></td>
                        <td><span class='badge badge-primary'><?php echo $row['trang_thai']; ?></span></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($recent_contracts)): ?>
                    <tr><td colspan="5" style="text-align: center;">Chưa có hợp đồng nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
