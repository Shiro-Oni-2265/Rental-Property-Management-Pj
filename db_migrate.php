<?php
$conn = new PDO("mysql:host=localhost;dbname=quan_ly_phong_tro;charset=utf8", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $sql = "CREATE TABLE IF NOT EXISTS HOP_DONG_DICH_VU (
        ma_hop_dong INT,
        ma_dich_vu INT,
        ngay_dang_ky DATE DEFAULT CURRENT_DATE,
        PRIMARY KEY (ma_hop_dong, ma_dich_vu),
        FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong) ON DELETE CASCADE,
        FOREIGN KEY (ma_dich_vu) REFERENCES DICH_VU(ma_dich_vu) ON DELETE CASCADE
    );";
    $conn->exec($sql);
    echo "Tạo bảng HOP_DONG_DICH_VU thành công.\n";
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>
