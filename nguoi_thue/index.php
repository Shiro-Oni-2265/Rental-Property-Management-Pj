<?php
$path_to_root = '../';
require_once '../includes/header.php';
?>

<header>
    <h1>Quản lý Người Thuê</h1>
    <a href="create.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm người thuê</a>
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
                <?php
                $stmt = $conn->query("SELECT * FROM NGUOI_THUE ORDER BY ma_nguoi_thue DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['ma_nguoi_thue'] . "</td>";
                    echo "<td>" . $row['ho_ten'] . "</td>";
                    echo "<td>" . $row['so_dien_thoai'] . "</td>";
                    echo "<td>" . $row['cccd'] . "</td>";
                    echo "<td>
                            <a href='edit.php?id=" . $row['ma_nguoi_thue'] . "' class='btn btn-sm btn-primary'><i class='fa-solid fa-edit'></i></a>
                            <a href='delete.php?id=" . $row['ma_nguoi_thue'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Bạn có chắc chắn cho người này ra đảo?\")'><i class='fa-solid fa-trash'></i></a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
