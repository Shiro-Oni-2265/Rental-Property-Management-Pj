<?php
$path_to_root = '../';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dien_tich = $_POST['dien_tich'];
    $gia_thue = $_POST['gia_thue'];
    $mo_ta = isset($_POST['mo_ta']) ? $_POST['mo_ta'] : '';
    $hinh_anh = null;

    // Handle File Upload
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES['hinh_anh']['name'];
        $filetype = $_FILES['hinh_anh']['type'];
        $filesize = $_FILES['hinh_anh']['size'];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed))
            die("Error: Please select a valid file format.");

        // Verify MIME type
        if (in_array($filetype, $allowed)) {
            // Check whether file exists before uploading it
            $upload_dir = '../uploads/rooms/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $newFilename = uniqid() . '-' . basename($filename);
            move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $upload_dir . $newFilename);
            $hinh_anh = 'uploads/rooms/' . $newFilename; // Store relative path
        } else {
            echo "Error: There was a problem uploading your file. Please try again.";
        }
    }

    try {
        $sql = "CALL sp_add_phong(:dien_tich, :gia_thue, :hinh_anh, :mo_ta)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dien_tich', $dien_tich);
        $stmt->bindParam(':gia_thue', $gia_thue);
        $stmt->bindParam(':hinh_anh', $hinh_anh);
        $stmt->bindParam(':mo_ta', $mo_ta);

        if ($stmt->execute()) {
            echo "<script>alert('Thêm phòng thành công!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra!');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<header>
    <h1>Thêm Phòng Mới</h1>
    <a href="index.php" class="btn btn-primary"
        style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="dien_tich">Diện tích (m2)</label>
            <input type="number" step="0.01" id="dien_tich" name="dien_tich" required>
        </div>

        <div class="form-group">
            <label for="gia_thue">Giá thuê (VNĐ)</label>
            <input type="number" id="gia_thue" name="gia_thue" required>
        </div>

        <div class="form-group">
            <label for="hinh_anh">Hình ảnh phòng</label>
            <input type="file" id="hinh_anh" name="hinh_anh" accept="image/*">
        </div>

        <div class="form-group">
            <label for="mo_ta">Mô tả phòng</label>
            <textarea id="mo_ta" name="mo_ta" rows="4"
                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; background: rgba(0,0,0,0.2); color: white;"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Thêm Phòng</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>