<?php
$path_to_root = '../';
require_once '../includes/header.php';
?>

<header>
    <h1>Quản lý Hóa Đơn</h1>
    <a href="create.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tạo hóa đơn</a>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã HĐ</th>
                    <th>Hợp đồng (Phòng)</th>
                    <th>Tháng/Năm</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT hd.*, p.ma_phong 
                        FROM HOA_DON hd
                        JOIN HOP_DONG h ON hd.ma_hop_dong = h.ma_hop_dong
                        JOIN PHONG p ON h.ma_phong = p.ma_phong
                        ORDER BY hd.nam DESC, hd.thang DESC, hd.ma_hoa_don DESC";
                        
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $status_class = $row['trang_thai'] == 'Da thanh toan' ? 'badge-success' : 'badge-warning';
                    echo "<tr>";
                    echo "<td>#" . $row['ma_hoa_don'] . "</td>";
                    echo "<td>HĐ #" . $row['ma_hop_dong'] . " (P." . $row['ma_phong'] . ")</td>";
                    echo "<td>" . $row['thang'] . "/" . $row['nam'] . "</td>";
                    echo "<td>" . formatMoney($row['tong_tien']) . "</td>";
                    echo "<td><span class='badge $status_class'>" . $row['trang_thai'] . "</span></td>";
                    echo "<td>
                            <a href='details.php?id=" . $row['ma_hoa_don'] . "' class='btn btn-sm btn-primary'>Chi tiết</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
