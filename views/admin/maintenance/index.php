<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Lịch Sử Bảo Trì</h1>
    <a href="index.php?controller=maintenance&action=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Ghi nhận bảo trì</a>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Phòng</th>
                    <th>Nội dung</th>
                    <th>Chi phí</th>
                    <th>Ngày</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $row): ?>
                    <tr>
                        <td><?php echo $row['ma_bao_tri']; ?></td>
                        <td>Phòng <?php echo $row['ma_phong']; ?></td>
                        <td><?php echo htmlspecialchars($row['loai_bao_tri']); ?></td>
                        <td><?php echo formatMoney($row['chi_phi']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['ngay_bao_tri'])); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="5" style="text-align: center;">Chưa có lịch sử bảo trì nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
