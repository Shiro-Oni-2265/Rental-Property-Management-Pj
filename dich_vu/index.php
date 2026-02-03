<?php
$path_to_root = '../';
require_once '../includes/header.php';
?>

<header>
    <h1>Quản lý Dịch Vụ</h1>
    <a href="create.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm dịch vụ</a>
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
                <?php
                $stmt = $conn->query("SELECT * FROM DICH_VU ORDER BY ma_dich_vu ASC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['ma_dich_vu'] . "</td>";
                    echo "<td>" . $row['ten_dich_vu'] . "</td>";
                    echo "<td>" . formatMoney($row['don_gia']) . "</td>";
                    echo "<td>" . $row['don_vi'] . "</td>";
                    echo "<td>
                            <a href='edit.php?id=" . $row['ma_dich_vu'] . "' class='btn btn-sm btn-primary'><i class='fa-solid fa-edit'></i></a>
                            <a href='delete.php?id=" . $row['ma_dich_vu'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Xóa dịch vụ này?\")'><i class='fa-solid fa-trash'></i></a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
