<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Quản lý Dịch Vụ</h1>
    <a href="index.php?controller=service&action=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm dịch vụ</a>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã DV</th>
                    <th>Tên dịch vụ</th>
                    <th>Đơn giá</th>
                    <th>Đơn vị</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $row): ?>
                    <tr>
                        <td><?php echo $row['ma_dich_vu']; ?></td>
                        <td><?php echo htmlspecialchars($row['ten_dich_vu']); ?></td>
                        <td><?php echo formatMoney($row['don_gia']); ?></td>
                        <td><?php echo htmlspecialchars($row['don_vi']); ?></td>
                        <td>
                            <a href="index.php?controller=service&action=edit&id=<?php echo $row['ma_dich_vu']; ?>" class="btn btn-sm btn-primary"><i class="fa-solid fa-edit"></i></a>
                            <a href="index.php?controller=service&action=delete&id=<?php echo $row['ma_dich_vu']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa dịch vụ này?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($services)): ?>
                    <tr><td colspan="5" style="text-align: center;">Chưa có dịch vụ nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
