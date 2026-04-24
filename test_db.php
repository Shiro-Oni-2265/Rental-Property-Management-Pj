<?php
$conn = new PDO("mysql:host=localhost;dbname=quan_ly_phong_tro;charset=utf8", "root", "");
$stmt = $conn->query("SELECT * FROM DICH_VU");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
