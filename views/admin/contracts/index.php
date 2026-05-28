<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Quản lý Hợp Đồng</h1>
    <a href="index.php?controller=contract&action=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tạo hợp đồng mới</a>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã HĐ</th>
                    <th>Phòng</th>
                    <th>Người đứng tên</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Tiền cọc</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contracts as $row): ?>
                    <?php $status_class = $row['trang_thai'] == 'Dang thue' ? 'badge-success' : 'badge-danger'; ?>
                    <tr>
                        <td><?php echo $row['ma_hop_dong']; ?></td>
                        <td>Phòng <?php echo $row['ma_phong']; ?></td>
                        <td><?php echo htmlspecialchars($row['tenants'] ?: 'N/A'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['ngay_bat_dau'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['ngay_ket_thuc'])); ?></td>
                        <td><?php echo formatMoney($row['tien_coc']); ?></td>
                        <td><span class="badge <?php echo $status_class; ?>"><?php echo $row['trang_thai']; ?></span></td>
                        <td>
                            <?php if ($row['trang_thai'] == 'Dang thue'): ?>
                                <a href="index.php?controller=contract&action=terminate&id=<?php echo $row['ma_hop_dong']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn kết thúc hợp đồng này?')">Kết thúc</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($contracts)): ?>
                    <tr><td colspan="8" style="text-align: center;">Chưa có hợp đồng nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
