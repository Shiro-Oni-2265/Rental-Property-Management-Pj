<?php
class CheckoutModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function processCheckout($userId, $items, $ngay_bat_dau = null, $includeInternet = false) {
        try {
            $this->conn->beginTransaction();

            $ngay_bat_dau_db = $ngay_bat_dau ? $ngay_bat_dau : date('Y-m-d');

            foreach ($items as $item) {
                $roomId = $item['ma_phong'];
                $months = $item['quantity'] ?? 1;

                // Lock row to prevent overbooking
                $stmt = $this->conn->prepare("SELECT * FROM PHONG WHERE ma_phong = :id AND trang_thai = 'Trong' FOR UPDATE");
                $stmt->execute([':id' => $roomId]);
                $room = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$room) {
                    throw new Exception("Rất tiếc, phòng $roomId vừa mới được người khác thuê hoặc không còn trống.");
                }

                $tien_coc = $room['gia_thue']; // Deposit equals 1 month rent

                // Calculate end date based on duration using the provided start date
                $query = "INSERT INTO HOP_DONG (ma_phong, ngay_bat_dau, ngay_ket_thuc, tien_coc, trang_thai) 
                          VALUES (:ma_phong, :ngay_bat_dau, DATE_ADD(:ngay_bat_dau, INTERVAL :months MONTH), :tien_coc, 'Dang thue')";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([
                    ':ma_phong' => $roomId,
                    ':ngay_bat_dau' => $ngay_bat_dau_db,
                    ':months' => $months,
                    ':tien_coc' => $tien_coc
                ]);

                $ma_hop_dong = $this->conn->lastInsertId();

                // Associate the contract with the user
                $query2 = "INSERT INTO HOP_DONG_NGUOI_THUE (ma_hop_dong, ma_nguoi_thue) VALUES (:ma_hop_dong, :ma_nguoi_thue)";
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->execute([
                    ':ma_hop_dong' => $ma_hop_dong,
                    ':ma_nguoi_thue' => $userId
                ]);

                // Register mandatory services (1: Điện, 2: Nước)
                $queryServices = "INSERT INTO HOP_DONG_DICH_VU (ma_hop_dong, ma_dich_vu) VALUES (:ma_hd, :ma_dv)";
                $stmt3 = $this->conn->prepare($queryServices);
                $stmt3->execute([':ma_hd' => $ma_hop_dong, ':ma_dv' => 1]); // Điện
                $stmt3->execute([':ma_hd' => $ma_hop_dong, ':ma_dv' => 2]); // Nước

                // Register optional service (3: Internet) if selected
                if ($includeInternet) {
                    $stmt3->execute([':ma_hd' => $ma_hop_dong, ':ma_dv' => 3]);
                }

                // The trigger trg_hop_dong_insert updates PHONG.trang_thai to 'Da thue'
            }

            $this->conn->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>
