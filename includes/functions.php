<?php
function formatMoney($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function ensureServiceExists(PDO $conn, string $serviceName, float $unitPrice, string $unit): int {
    $stmt = $conn->prepare("SELECT ma_dich_vu FROM DICH_VU WHERE ten_dich_vu = :name LIMIT 1");
    $stmt->bindValue(':name', $serviceName);
    $stmt->execute();
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existing && isset($existing['ma_dich_vu'])) {
        return (int)$existing['ma_dich_vu'];
    }

    $ins = $conn->prepare("INSERT INTO DICH_VU(ten_dich_vu, don_gia, don_vi) VALUES(:t, :g, :d)");
    $ins->bindValue(':t', $serviceName);
    $ins->bindValue(':g', $unitPrice);
    $ins->bindValue(':d', $unit);
    $ins->execute();

    return (int)$conn->lastInsertId();
}

// Function to get active class for sidebar
function is_active($currect_page){
  $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
  $url = end($url_array);  
  if($currect_page == $url){
      return 'active'; //class name in css 
  } 
  return '';
}
?>
