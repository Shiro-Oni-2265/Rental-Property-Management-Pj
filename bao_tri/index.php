<?php
$path_to_root = '../';
require_once '../includes/header.php';
?>

<header>
    <h1>Lịch Sử Bảo Trì</h1>
    <a href="create.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Ghi nhận bảo trì</a>
</header>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Phòng</th>
                    <th>Nội dung</th>
                    <th>Chi phí</th>
                    <th>Ngày</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT bt.*, p.ma_phong 
                        FROM BAO_TRI bt 
                        JOIN PHONG p ON bt.ma_phong = p.ma_phong
                        ORDER BY bt.ngay_bao_tri DESC";
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['ma_bao_tri'] . "</td>";
                    echo "<td>Phòng " . $row['ma_phong'] . "</td>";
                    echo "<td>" . $row['loai_bao_tri'] . "</td>";
                    echo "<td>" . formatMoney($row['chi_phi']) . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['ngay_bao_tri'])) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
