<div class="room-detail-container"
    style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 1rem; align-items: start;">
    <!-- Image Slider / Main Image -->
    <div class="room-gallery">
        <?php $img_src = !empty($room['hinh_anh']) ? '../' . htmlspecialchars($room['hinh_anh']) : 'https://placehold.co/800x600?text=Room+' . $room['ma_phong']; ?>
        <img src="<?= $img_src ?>" alt="Phòng <?= htmlspecialchars($room['ma_phong']) ?>"
            style="width: 100%; border-radius: 0.5rem; object-fit: cover; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    </div>

    <!-- Room Information -->
    <div class="room-info-detail">
        <h1 style="margin-top: 0; margin-bottom: 0.5rem;">Phòng <?= htmlspecialchars($room['ma_phong']) ?></h1>
        <div
            style="display: inline-block; padding: 0.25rem 0.75rem; background-color: <?= $room['trang_thai'] === 'Trong' ? '#dcfce7' : '#fee2e2' ?>; color: <?= $room['trang_thai'] === 'Trong' ? '#166534' : '#b91c1c' ?>; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; margin-bottom: 1.5rem;">
            <?= htmlspecialchars($room['trang_thai'] === 'Trong' ? 'Trống' : 'Đã thuê') ?>
        </div>

        <div class="room-price"
            style="font-size: 2rem; margin-bottom: 2rem; color: var(--primary-color); font-weight: bold;">
            <?= number_format($room['gia_thue'], 0, ',', '.') ?> VNĐ <span
                style="font-size: 1rem; color: var(--text-light); font-weight: normal;">/ tháng</span>
        </div>

        <div class="detail-specs"
            style="margin-bottom: 2rem; padding: 1.5rem; background-color: #f3f4f6; border-radius: 0.5rem;">
            <h3 style="margin-top: 0; margin-bottom: 1rem;">Thông tin chi tiết</h3>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                <li style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-light);"><i class="fa-solid fa-ruler-combined"
                            style="width: 20px;"></i> Diện tích</span>
                    <strong><?= htmlspecialchars($room['dien_tich']) ?> m²</strong>
                </li>
                <li style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-light);"><i class="fa-solid fa-couch" style="width: 20px;"></i> Nội
                        thất</span>
                    <strong>Cơ bản</strong>
                </li>
            </ul>
        </div>

        <div class="landlord-contact"
            style="margin-bottom: 2rem; padding: 1.5rem; background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 0.5rem;">
            <h3 style="margin-top: 0; margin-bottom: 1rem; color: #b45309;"><i class="fa-solid fa-address-card"></i>
                Thông tin ban quản lý / Chủ trọ</h3>
            <p style="margin: 0 0 0.5rem 0; font-weight: bold;">Ban Quản Lý: KTX PT Manager</p>
            <p style="margin: 0 0 0.5rem 0;"><i class="fa-solid fa-phone" style="color: #d97706; width: 20px;"></i>
                Hotline: <strong>0901.234.567</strong> (Zalo)</p>
            <p style="margin: 0;"><i class="fa-solid fa-envelope" style="color: #d97706; width: 20px;"></i> Email:
                ptmanger_ktx@gmail.com</p>
        </div>

        <?php if ($room['trang_thai'] == 'Trong'): ?>
            <!-- Form for Custom Duration -->
            <form action="?controller=cart&action=add" method="POST" style="width: 100%;">
                <input type="hidden" name="id" value="<?= htmlspecialchars($room['ma_phong']) ?>">
                <div style="margin-bottom: 1.5rem;">
                    <label for="quantity" style="display: block; font-weight: 500; margin-bottom: 0.5rem;">Số tháng muốn
                        thuê (Tối thiểu 1 tháng):</label>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="60" required
                            style="width: 100px; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; font-size: 1.1rem; text-align: center;">
                        <span>Tháng</span>
                    </div>
                </div>

                <div class="action-buttons" style="display: flex; gap: 1rem;">
                    <button type="submit" name="action_type" value="cart" class="btn btn-outline"
                        style="flex: 1; text-align: center; padding: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-color: var(--primary-color); color: var(--primary-color);">
                        <i class="fa-solid fa-cart-shopping"></i> Thêm vào giỏ
                    </button>
                    <button type="submit" name="action_type" value="checkout" class="btn btn-primary"
                        style="flex: 1; text-align: center; padding: 1rem; display: flex; align-items: center; justify-content: center;">
                        Thuê ngay
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div
                style="padding: 1.5rem; text-align: center; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 0.5rem; color: #64748b;">
                <i class="fa-solid fa-door-closed" style="font-size: 2.5rem; margin-bottom: 1rem; color: #94a3b8;"></i><br>
                <span style="font-size: 1.1rem;">Phòng này hiện đã được cho thuê.<br>Vui lòng tham khảo các phòng
                    khác.</span>
            </div>
        <?php endif; ?>
    </div>
</div>