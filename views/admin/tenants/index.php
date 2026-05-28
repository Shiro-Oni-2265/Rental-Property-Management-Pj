<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Quản lý Người Thuê</h1>
    <a href="index.php?controller=tenant&action=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm người thuê</a>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Họ tên</th>
                    <th>SĐT</th>
                    <th>CCCD</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tenants as $row): ?>
                    <tr>
                        <td><?php echo $row['ma_nguoi_thue']; ?></td>
                        <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                        <td><?php echo htmlspecialchars($row['so_dien_thoai']); ?></td>
                        <td><?php echo htmlspecialchars($row['cccd']); ?></td>
                        <td>
                            <a href="index.php?controller=tenant&action=edit&id=<?php echo $row['ma_nguoi_thue']; ?>" class="btn btn-sm btn-primary"><i class="fa-solid fa-edit"></i></a>
                            <a href="index.php?controller=tenant&action=delete&id=<?php echo $row['ma_nguoi_thue']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa khách thuê này?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($tenants)): ?>
                    <tr><td colspan="5" style="text-align: center;">Chưa có khách thuê nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
