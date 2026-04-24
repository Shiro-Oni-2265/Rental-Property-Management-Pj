<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Quản lý Phòng</h1>
    <a href="index.php?controller=room&action=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm phòng mới</a>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Hình Ảnh</th>
                    <th>Diện tích (m2)</th>
                    <th>Giá thuê</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = 1; foreach ($rooms as $row): ?>
                    <?php
                    $status_class = $row['trang_thai'] == 'Trong' ? 'badge-success' : 'badge-danger';
                    $img_src = !empty($row['hinh_anh']) ? $row['hinh_anh'] : 'https://placehold.co/100x75?text=Room+' . $row['ma_phong'];
                    ?>
                    <tr>
                        <td><?php echo $stt++; ?></td>
                        <td><img src="<?php echo htmlspecialchars($img_src); ?>" alt="Room Image" style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;"></td>
                        <td><?php echo $row['dien_tich']; ?></td>
                        <td><?php echo formatMoney($row['gia_thue']); ?></td>
                        <td><span class="badge <?php echo $status_class; ?>"><?php echo $row['trang_thai']; ?></span></td>
                        <td>
                            <a href="index.php?controller=room&action=edit&id=<?php echo $row['ma_phong']; ?>" class="btn btn-sm btn-primary"><i class="fa-solid fa-edit"></i></a>
                            <a href="index.php?controller=room&action=delete&id=<?php echo $row['ma_phong']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($rooms)): ?>
                    <tr><td colspan="6" style="text-align: center;">Chưa có dữ liệu phòng.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
