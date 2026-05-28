<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Phản Hồi / Khiếu Nại</h1>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Người gửi</th>
                    <th>Nội dung</th>
                    <th>Loại</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $row): ?>
                    <tr>
                        <td><?php echo $row['ma_phan_hoi']; ?></td>
                        <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                        <td><?php echo htmlspecialchars($row['noi_dung']); ?></td>
                        <td><?php echo htmlspecialchars($row['loai']); ?></td>
                        <td>
                            <?php if ($row['trang_thai'] == 'Da xu ly'): ?>
                                <span class="badge badge-success"><?php echo $row['trang_thai']; ?></span>
                            <?php else: ?>
                                <span class="badge badge-warning"><?php echo $row['trang_thai']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['trang_thai'] != 'Da xu ly'): ?>
                                <a href="index.php?controller=feedback&action=resolve&id=<?php echo $row['ma_phan_hoi']; ?>" class="btn btn-sm btn-primary">Đánh dấu đã xử lý</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($feedbacks)): ?>
                    <tr><td colspan="6" style="text-align: center;">Chưa có phản hồi nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
