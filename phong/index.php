<?php
$path_to_root = '../';
require_once '../includes/admin_guard.php';
require_once '../includes/header.php';
?>

<header>
    <h1>Quản lý Phòng</h1>
    <a href="create.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm phòng mới</a>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                    <th>Mã Phòng</th>
                    <th>Hình Ảnh</th>
                    <th>Diện tích (m2)</th>
                    <th>Giá thuê</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM PHONG ORDER BY ma_phong ASC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $status_class = $row['trang_thai'] == 'Trong' ? 'badge-success' : 'badge-danger';
                    
                    $img_src = !empty($row['hinh_anh']) ? '../' . $row['hinh_anh'] : 'https://placehold.co/100x75?text=Room+' . $row['ma_phong'];
                    
                    echo "<tr>";
                    echo "<td>" . $row['ma_phong'] . "</td>";
                    echo "<td><img src='" . htmlspecialchars($img_src) . "' alt='Room Image' style='width: 80px; height: 60px; object-fit: cover; border-radius: 4px;'></td>";
                    echo "<td>" . $row['dien_tich'] . "</td>";
                    echo "<td>" . formatMoney($row['gia_thue']) . "</td>";
                    echo "<td><span class='badge $status_class'>" . $row['trang_thai'] . "</span></td>";
                    echo "<td>
                            <a href='edit.php?id=" . $row['ma_phong'] . "' class='btn btn-sm btn-primary'><i class='fa-solid fa-edit'></i></a>
                            <a href='delete.php?id=" . $row['ma_phong'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa phòng này?\")'><i class='fa-solid fa-trash'></i></a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
