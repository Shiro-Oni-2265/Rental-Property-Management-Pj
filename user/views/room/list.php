<style>
.room-list-view {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.room-card-horizontal {
    display: flex;
    flex-direction: row;
    background: white;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    border: 1px solid var(--border-color);
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
    <p style="color: var(--text-light);">Tìm kiếm và lựa chọn phòng phù hợp nhất với bạn.</p>
</div>

<div class="room-list-view">
    <?php if (!empty($rooms)): ?>
        <?php foreach ($rooms as $room): ?>
            <div class="room-card-horizontal">
                <?php $img_src = !empty($room['hinh_anh']) ? '../' . htmlspecialchars($room['hinh_anh']) : 'https://placehold.co/400x250?text=Room+' . $room['ma_phong']; ?>
                <div class="room-img"
                    style="background-image: url('<?= $img_src ?>'); background-size: cover; background-position: center;">
                </div>
                <div class="room-info">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div>
                            <h3 class="room-title">Phòng <?= htmlspecialchars($room['ma_phong']) ?></h3>
                            <div class="room-price"><?= number_format($room['gia_thue'], 0, ',', '.') ?> VNĐ/tháng</div>
                        </div>
                        <div class="room-details" style="margin-bottom: 0;">
                            <span style="background: #f1f5f9; padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-weight: 500;"><i class="fa-solid fa-ruler-combined"></i> <?= htmlspecialchars($room['dien_tich']) ?> m²</span>
                            <?php if ($room['trang_thai'] == 'Trong'): ?>
                                <span style="background: #dcfce7; color: #166534; padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-weight: 500;"><i class="fa-solid fa-check-circle"></i> Đang trống</span>
                            <?php else: ?>
                                <span style="background: #fee2e2; color: #b91c1c; padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-weight: 500;"><i class="fa-solid fa-times-circle"></i> Đã thuê</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <p style="color: #475569; font-size: 1rem; margin-top: 0.5rem; margin-bottom: 1.5rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; flex-grow: 1;">
                        <?= !empty($room['mo_ta']) ? htmlspecialchars($room['mo_ta']) : 'Phòng trọ sạch sẽ, thoáng mát, bao gồm các tiện nghi cơ bản. Môi trường an ninh tốt, thích hợp làm nơi nghỉ ngơi và sinh hoạt.' ?>
                    </p>
                    
                    <div style="margin-top: auto; display: flex; justify-content: flex-end;">
                        <a href="?controller=room&action=detail&id=<?= $room['ma_phong'] ?>" class="btn btn-primary"
                            style="padding: 0.75rem 2rem; border-radius: 0.5rem;">Xem chi tiết phòng</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; width: 100%; color: var(--text-light); padding: 3rem; background: white; border-radius: 0.5rem; border: 1px solid var(--border-color);">Hiện không có phòng nào trống.</p>
    <?php endif; ?>
</div>