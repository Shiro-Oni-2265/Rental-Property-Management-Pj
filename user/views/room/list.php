<div style="margin-bottom: 2rem;">
    <h1 style="margin-bottom: 0.5rem;">Danh sách Phòng trống</h1>
    <p style="color: var(--text-light);">Tìm kiếm và lựa chọn phòng phù hợp nhất với bạn.</p>
</div>

<div class="room-grid">
    <?php if (!empty($rooms)): ?>
        <?php foreach ($rooms as $room): ?>
            <div class="room-card">
                <?php $img_src = !empty($room['hinh_anh']) ? '../' . htmlspecialchars($room['hinh_anh']) : 'https://placehold.co/400x250?text=Room+' . $room['ma_phong']; ?>
                <div class="room-img"
                    style="background-image: url('<?= $img_src ?>'); background-size: cover; background-position: center;">
                </div>
                <div class="room-info">
                    <h3 class="room-title">Phòng <?= htmlspecialchars($room['ma_phong']) ?></h3>
                    <div class="room-price"><?= number_format($room['gia_thue'], 0, ',', '.') ?> VNĐ/tháng</div>
                    <div class="room-details">
                        <span><i class="fa-solid fa-ruler-combined"></i> <?= htmlspecialchars($room['dien_tich']) ?> m²</span>
                        <?php if ($room['trang_thai'] == 'Trong'): ?>
                            <span><i class="fa-solid fa-check-circle" style="color: green;"></i> Đang trống</span>
                        <?php else: ?>
                            <span><i class="fa-solid fa-times-circle" style="color: red;"></i> Đã thuê</span>
                        <?php endif; ?>
                    </div>
                    <p
                        style="color: #64748b; font-size: 0.9rem; margin-top: 0.5rem; margin-bottom: 1rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                        <?= !empty($room['mo_ta']) ? htmlspecialchars($room['mo_ta']) : 'Phòng trọ sạch sẽ, thoáng mát, bao gồm các tiện nghi cơ bản. Môi trường an ninh tốt, thích hợp làm nơi nghỉ ngơi và sinh hoạt.' ?>
                    </p>
                    <div style="margin-top: auto;">
                        <a href="?controller=room&action=detail&id=<?= $room['ma_phong'] ?>" class="btn btn-primary"
                            style="width: 100%; box-sizing: border-box;">Xem phòng</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; width: 100%; color: var(--text-light);">Hiện không có phòng nào trống.</p>
    <?php endif; ?>
</div>