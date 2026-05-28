<?php
/*
 * Lớp FeedbackController (controllers/FeedbackController.php)
 * Nhiệm vụ: Xử lý nghiệp vụ xem phản hồi và xử lý phản hồi dành cho Admin.
 */
class FeedbackController extends Controller {

    public function __construct($db) {
        parent::__construct($db);
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /**
     * Giao diện danh sách phản hồi của người thuê
     */
    public function index() {
        $feedbackModel = new FeedbackModel($this->conn);
        $feedbacks = $feedbackModel->getAllFeedbacks();

        $this->render('admin/feedbacks/index', ['feedbacks' => $feedbacks]);
    }

    /**
     * Xử lý giải quyết phản hồi
     */
    public function resolve() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $feedbackModel = new FeedbackModel($this->conn);
            $feedback = $feedbackModel->getFeedbackById($id);

            if ($feedback) {
                if ($feedback['loai'] === 'Yêu cầu bảo trì') {
                    // Truy tìm phòng tương ứng đang thuê để chuyển hướng sang tạo phiếu Bảo trì
                    $ma_phong = $feedbackModel->findActiveRoomForUser($feedback['ma_nguoi_thue']);
                    $desc = urlencode($feedback['noi_dung']);
                    
                    // Chuyển hướng sang module Bảo trì với thông số tự điền
                    header("Location: index.php?controller=maintenance&action=create&ph_id=" . $id . "&ma_phong=" . $ma_phong . "&desc=" . $desc);
                    exit;
                } else {
                    // Phản hồi thường ➔ chỉ cần cập nhật trạng thái
                    if ($feedbackModel->resolveFeedback($id)) {
                        echo "<script>alert('Giải quyết phản hồi thành công!'); window.location.href='index.php?controller=feedback&action=index';</script>";
                        exit;
                    }
                }
            }
        }
        header("Location: index.php?controller=feedback&action=index");
        exit;
    }
}
?>
