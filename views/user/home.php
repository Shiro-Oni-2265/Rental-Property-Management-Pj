<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Xin chào, <?php echo htmlspecialchars($userInfo['ho_ten']); ?></h1>
    <a href="index.php?controller=auth&action=logout" class="btn btn-sm btn-danger">Đăng xuất</a>
</header>

<div class="card">
    <h3>Thông tin hợp đồng của bạn</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Phòng</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Tiền cọc</th>
                    <th>Trạng thái hợp đồng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contracts as $contract): ?>
                    <tr>
                        <td>Phòng <?php echo $contract['ma_phong']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($contract['ngay_bat_dau'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($contract['ngay_ket_thuc'])); ?></td>
                        <td><?php echo formatMoney($contract['tien_coc']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $contract['trang_thai'] === 'Dang thue' ? 'success' : 'default'; ?>">
                                <?php echo $contract['trang_thai']; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($contracts)): ?>
                    <tr><td colspan="5" style="text-align: center;">Bạn chưa có hợp đồng phòng nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
