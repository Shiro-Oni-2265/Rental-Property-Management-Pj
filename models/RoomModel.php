<?php
/*
 * Lớp RoomModel
 * Nhiệm vụ: Tương tác với bảng PHONG trong Cơ sở dữ liệu.
 * Chứa các câu truy vấn liên quan đến việc lấy dữ liệu phòng trọ.
 */
class RoomModel
{
    private $conn; // Biến lưu trữ kết nối PDO

    // Hàm khởi tạo, nhận kết nối CSDL từ ngoài truyền vào
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /*
     * Lấy danh sách các phòng còn trống
     * Bảng: PHONG
     * Ý nghĩa query: Lấy tất cả cột (*), điều kiện phòng có trạng thái 'Trong', sắp xếp tăng dần theo mã phòng
     */
    public function getAvailableRooms()
    {
        $query = "SELECT * FROM PHONG WHERE trang_thai = 'Trong' ORDER BY ma_phong ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        // Trả về mảng chứa tất cả các dòng dữ liệu (FETCH_ASSOC: trả về dạng mảng key-value)
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Lấy danh sách tất cả các phòng (Bao gồm cả trống và đã thuê)
     * Thường dùng cho Admin để quản lý
     */
    public function getAllRooms()
    {
        $query = "SELECT * FROM PHONG ORDER BY ma_phong ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Lấy thông tin chi tiết của một phòng cụ thể theo ID
     * Tham số: $id (Mã phòng)
     */
    public function getRoomById($id)
    {
        $query = "SELECT * FROM PHONG WHERE ma_phong = :id";
        $stmt = $this->conn->prepare($query);
        
        // Bind parameter để chống SQL Injection
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // fetch() chỉ trả về 1 dòng dữ liệu duy nhất
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>