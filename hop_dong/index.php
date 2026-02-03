<?php
$path_to_root = '../';
require_once '../includes/header.php';
?>

<header>
    <h1>Quản lý Hợp Đồng</h1>
    <a href="create.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tạo hợp đồng mới</a>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã HĐ</th>
                    <th>Phòng</th>
                    <th>Người đứng tên</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Tiền cọc</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Join query to get Room and Tenant info
                // Note: Only displaying ONE tenant name for brevity or the first one found
                $sql = "SELECT hd.*, p.ma_phong, p.gia_thue, 
                        GROUP_CONCAT(nt.ho_ten SEPARATOR ', ') as tenants
                        FROM HOP_DONG hd
                        JOIN PHONG p ON hd.ma_phong = p.ma_phong
                        LEFT JOIN HOP_DONG_NGUOI_THUE hdnt ON hd.ma_hop_dong = hdnt.ma_hop_dong
                        LEFT JOIN NGUOI_THUE nt ON hdnt.ma_nguoi_thue = nt.ma_nguoi_thue
                        GROUP BY hd.ma_hop_dong
                        ORDER BY hd.ma_hop_dong DESC";
                        
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $status_class = $row['trang_thai'] == 'Dang thue' ? 'badge-success' : 'badge-danger';
                    echo "<tr>";
                    echo "<td>" . $row['ma_hop_dong'] . "</td>";
                    echo "<td>Phòng " . $row['ma_phong'] . "</td>";
                    echo "<td>" . ($row['tenants'] ?: 'N/A') . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['ngay_bat_dau'])) . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['ngay_ket_thuc'])) . "</td>";
                    echo "<td>" . formatMoney($row['tien_coc']) . "</td>";
                    echo "<td><span class='badge $status_class'>" . $row['trang_thai'] . "</span></td>";
                    echo "<td>";
                    if ($row['trang_thai'] == 'Dang thue') {
                        echo "<a href='terminate.php?id=" . $row['ma_hop_dong'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Bạn chắc chắn muốn kết thúc hợp đồng này?\")'>Kết thúc</a>";
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
