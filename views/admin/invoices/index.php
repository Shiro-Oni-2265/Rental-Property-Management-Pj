<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Quản lý Hóa Đơn</h1>
    <a href="index.php?controller=invoice&action=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tạo hóa đơn</a>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã HĐ</th>
                    <th>Hợp đồng (Phòng)</th>
                    <th>Tháng/Năm</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $row): ?>
                    <?php $status_class = $row['trang_thai'] == 'Da thanh toan' ? 'badge-success' : 'badge-warning'; ?>
                    <tr>
                        <td>#<?php echo $row['ma_hoa_don']; ?></td>
                        <td>HĐ #<?php echo $row['ma_hop_dong']; ?> (P.<?php echo $row['ma_phong']; ?>)</td>
                        <td><?php echo $row['thang'] . '/' . $row['nam']; ?></td>
                        <td><?php echo formatMoney($row['tong_tien']); ?></td>
                        <td><span class="badge <?php echo $status_class; ?>"><?php echo $row['trang_thai']; ?></span></td>
                        <td>
                            <a href="index.php?controller=invoice&action=details&id=<?php echo $row['ma_hoa_don']; ?>" class="btn btn-sm btn-primary">Chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($invoices)): ?>
                    <tr><td colspan="6" style="text-align: center;">Chưa có hóa đơn nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
