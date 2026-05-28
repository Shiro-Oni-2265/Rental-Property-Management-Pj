<?php
/*
 * Lớp CheckoutModel
 * Nhiệm vụ: Xử lý logic nghiệp vụ khi người thuê quyết định thuê phòng (Thanh toán / Chốt hợp đồng).
 * Sử dụng Transaction để đảm bảo tính toàn vẹn dữ liệu (Không bị lỗi nửa chừng).
 */
class CheckoutModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /*
     * Xử lý quy trình thuê phòng (Checkout)
     * Các bước: Tạo hợp đồng -> Liên kết người thuê -> Đăng ký dịch vụ -> Cập nhật trạng thái phòng
     */
    public function processCheckout($userId, $items, $ngay_bat_dau = null, $includeInternet = false) {
        try {
            // Bắt đầu một Transaction. Nếu có bất kỳ lỗi nào xảy ra ở các bước dưới,
            // toàn bộ thao tác sẽ bị hủy bỏ (Rollback) để tránh dữ liệu bị sai lệch.
            $this->conn->beginTransaction();

            // Nếu người dùng không chọn ngày bắt đầu, mặc định lấy ngày hôm nay
            $ngay_bat_dau_db = $ngay_bat_dau ? $ngay_bat_dau : date('Y-m-d');

            // Duyệt qua từng phòng mà người thuê muốn thuê
            foreach ($items as $item) {
                $roomId = $item['ma_phong'];
                $months = $item['quantity'] ?? 1; // Số tháng muốn thuê (Mặc định 1 tháng)

                // Khóa dòng dữ liệu (Row-level lock) bằng FOR UPDATE để tránh trường hợp
                // 2 người cùng click thuê 1 phòng cùng một lúc (Overbooking)
                $stmt = $this->conn->prepare("SELECT * FROM PHONG WHERE ma_phong = :id AND trang_thai = 'Trong' FOR UPDATE");
                $stmt->execute([':id' => $roomId]);
                $room = $stmt->fetch(PDO::FETCH_ASSOC);

                // Nếu phòng không tồn tại hoặc đã bị người khác thuê mất
                if (!$room) {
                    throw new Exception("Rất tiếc, phòng $roomId vừa mới được người khác thuê hoặc không còn trống.");
                }

                $tien_coc = $room['gia_thue']; // Tiền cọc được tính bằng 1 tháng tiền thuê

                // Bước 1: Tạo Hợp đồng mới (Tính ngày kết thúc bằng cách cộng thêm số tháng)
                $query = "INSERT INTO HOP_DONG (ma_phong, ngay_bat_dau, ngay_ket_thuc, tien_coc, trang_thai) 
                          VALUES (:ma_phong, :ngay_bat_dau, DATE_ADD(:ngay_bat_dau, INTERVAL :months MONTH), :tien_coc, 'Dang thue')";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([
                    ':ma_phong' => $roomId,
                    ':ngay_bat_dau' => $ngay_bat_dau_db,
                    ':months' => $months,
                    ':tien_coc' => $tien_coc
                ]);

                // Lấy mã hợp đồng vừa được tạo tự động
                $ma_hop_dong = $this->conn->lastInsertId();

                // Bước 2: Liên kết hợp đồng này với người thuê (Bảng trung gian n-n)
                $query2 = "INSERT INTO HOP_DONG_NGUOI_THUE (ma_hop_dong, ma_nguoi_thue) VALUES (:ma_hop_dong, :ma_nguoi_thue)";
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->execute([
                    ':ma_hop_dong' => $ma_hop_dong,
                    ':ma_nguoi_thue' => $userId
                ]);

                // Bước 3: Đăng ký các dịch vụ bắt buộc (1: Điện, 2: Nước)
                $queryServices = "INSERT INTO HOP_DONG_DICH_VU (ma_hop_dong, ma_dich_vu) VALUES (:ma_hd, :ma_dv)";
                $stmt3 = $this->conn->prepare($queryServices);
                $stmt3->execute([':ma_hd' => $ma_hop_dong, ':ma_dv' => 1]); // Điện
                $stmt3->execute([':ma_hd' => $ma_hop_dong, ':ma_dv' => 2]); // Nước

                // Đăng ký dịch vụ tùy chọn (3: Internet) nếu người dùng có tick chọn
                if ($includeInternet) {
                    $stmt3->execute([':ma_hd' => $ma_hop_dong, ':ma_dv' => 3]);
                }

                // Lưu ý: CSDL có cài đặt sẵn một Trigger tên là `trg_hop_dong_insert`.
                // Khi Insert vào bảng HOP_DONG, trigger sẽ tự động Update PHONG.trang_thai thành 'Da thue'.
            }

            // Nếu mọi thứ đều ổn định, Xác nhận lưu vào CSDL (Commit)
            $this->conn->commit();
            return ['success' => true];
        } catch (Exception $e) {
            // Nếu có lỗi (phòng bị cướp mất, lỗi DB,...), hủy toàn bộ thao tác (Rollback)
            $this->conn->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>
