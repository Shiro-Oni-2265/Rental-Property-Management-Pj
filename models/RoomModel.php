<?php
class RoomModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAvailableRooms()
    {
        $query = "SELECT * FROM PHONG WHERE trang_thai = 'Trong' ORDER BY ma_phong ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRooms()
    {
        $query = "SELECT * FROM PHONG ORDER BY ma_phong ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomById($id)
    {
        $query = "SELECT * FROM PHONG WHERE ma_phong = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>