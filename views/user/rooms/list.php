<?php
$path_to_root = './';
require_once 'views/layouts/header.php';
?>

<style>
.room-list-view {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.room-card-horizontal {
    display: flex;
    flex-direction: row;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 1rem;
    backdrop-filter: blur(10px);
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}
.room-card-horizontal:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}
.room-card-horizontal .room-img {
    width: 350px;
    min-height: 100%;
    flex-shrink: 0;
}
.room-card-horizontal .room-info {
    padding: 1.5rem 2rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
@media (max-width: 768px) {
    .room-card-horizontal {
        flex-direction: column;
    }
    .room-card-horizontal .room-img {
        width: 100%;
        height: 250px;
    }
}
</style>

<div style="margin-bottom: 2rem;">
    <h1 style="margin-bottom: 0.5rem;">Danh sách Phòng trống</h1>
    <p style="color: var(--text-muted);">Tìm kiếm và lựa chọn phòng phù hợp nhất với bạn.</p>
</div>

<div class="room-list-view">
    <?php if (!empty($rooms)): ?>
        <?php foreach ($rooms as $room): ?>
            <div class="room-card-horizontal">
                <?php $img_src = !empty($room['hinh_anh']) ? htmlspecialchars($room['hinh_anh']) : 'https://placehold.co/400x250?text=Room+' . $room['ma_phong']; ?>
                <div class="room-img" style="background-image: url('<?php echo $img_src; ?>'); background-size: cover; background-position: center;">
                </div>
                <div class="room-info">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div>
                            <h3 class="room-title" style="color: white;">Phòng <?php echo htmlspecialchars($room['ma_phong']); ?></h3>
                            <div class="room-price" style="font-size: 1.25rem; font-weight: bold; color: var(--primary);"><?php echo formatMoney($room['gia_thue']); ?>/tháng</div>
                        </div>
                        <div class="room-details" style="margin-bottom: 0;">
                            <span style="background: rgba(255,255,255,0.1); color: #e2e8f0; padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-weight: 500;"><i class="fa-solid fa-ruler-combined"></i> <?php echo htmlspecialchars($room['dien_tich']); ?> m²</span>
                            <?php if ($room['trang_thai'] == 'Trong'): ?>
                                <span style="background: rgba(16, 185, 129, 0.2); color: #a7f3d0; padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-weight: 500;"><i class="fa-solid fa-check-circle"></i> Đang trống</span>
                            <?php else: ?>
                                <span style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-weight: 500;"><i class="fa-solid fa-times-circle"></i> Đã thuê</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <p style="color: #cbd5e1; font-size: 1rem; margin-top: 0.5rem; margin-bottom: 1.5rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; flex-grow: 1;">
                        <?php echo !empty($room['mo_ta']) ? htmlspecialchars($room['mo_ta']) : 'Phòng trọ sạch sẽ, thoáng mát, bao gồm các tiện nghi cơ bản. Môi trường an ninh tốt, thích hợp làm nơi nghỉ ngơi và sinh hoạt.'; ?>
                    </p>
                    
                    <div style="margin-top: auto; display: flex; justify-content: flex-end;">
                        <a href="index.php?controller=user_room&action=detail&id=<?php echo $room['ma_phong']; ?>" class="btn btn-primary"
                            style="padding: 0.75rem 2rem; border-radius: 0.5rem;">Xem chi tiết phòng</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; width: 100%; color: var(--text-muted); padding: 3rem; background: rgba(255,255,255,0.05); border-radius: 0.5rem; border: 1px solid rgba(255,255,255,0.1);">Hiện không có phòng nào trống.</p>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
