<?php
$path_to_root = '../';
require_once '../includes/header.php';
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
                <?php
                $sql = "SELECT ph.*, nt.ho_ten 
                        FROM PHAN_HOI ph 
                        JOIN NGUOI_THUE nt ON ph.ma_nguoi_thue = nt.ma_nguoi_thue
                        ORDER BY ph.ma_phan_hoi DESC";
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['ma_phan_hoi'] . "</td>";
                    echo "<td>" . $row['ho_ten'] . "</td>";
                    echo "<td>" . $row['noi_dung'] . "</td>";
                    echo "<td>" . $row['loai'] . "</td>";
                    echo "<td>" . $row['trang_thai'] . "</td>";
                    echo "<td>";
                    if ($row['trang_thai'] != 'Da xu ly') {
                        echo "<a href='resolve.php?id=" . $row['ma_phan_hoi'] . "' class='btn btn-sm btn-primary'>Đánh dấu đã xử lý</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
