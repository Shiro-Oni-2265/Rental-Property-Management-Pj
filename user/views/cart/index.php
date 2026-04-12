<div class="cart-container" style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem;">
    <h2 style="color: var(--primary-color); margin-bottom: 2rem; border-bottom: 2px solid var(--border-color); padding-bottom: 1rem;">
        Giỏ Hàng Của Bạn (<?= htmlspecialchars($_SESSION['user_name']) ?>)
    </h2>

    <?php if (empty($cartItems)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background-color: #f9fafb; border-radius: 0.5rem; border: 1px dashed var(--border-color);">
            <i class="fa-solid fa-cart-arrow-down" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-color); margin-bottom: 1rem;">Giỏ hàng của bạn đang trống</h3>
            <a href="?controller=room&action=list" class="btn btn-primary" style="display: inline-block; padding: 0.75rem 1.5rem;">
                Xem danh sách phòng
            </a>
        </div>
    <?php else: ?>
        <div class="cart-table-wrapper" style="overflow-x: auto; background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 2rem;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f3f4f6; border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 1rem; font-weight: 600; color: var(--text-color);">Phòng</th>
                        <th style="padding: 1rem; font-weight: 600; color: var(--text-color);">Đơn Giá</th>
                        <th style="padding: 1rem; font-weight: 600; color: var(--text-color);">Thời Gian Thuê</th>
                        <th style="padding: 1rem; font-weight: 600; color: var(--text-color);">Thành Tiền</th>
                        <th style="padding: 1rem; font-weight: 600; text-align: center;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                                <?php $img_src = !empty($item['hinh_anh']) ? '../' . htmlspecialchars($item['hinh_anh']) : 'https://placehold.co/100x75?text=Phong+' . $item['ma_phong']; ?>
                                <img src="<?= $img_src ?>" alt="Phòng <?= htmlspecialchars($item['ma_phong']) ?>" style="width: 80px; height: 60px; object-fit: cover; border-radius: 0.25rem;">
                                <div>
                                    <strong style="display: block; font-size: 1.1rem; color: var(--primary-color);">Phòng <?= htmlspecialchars($item['ma_phong']) ?></strong>
                                    <span style="font-size: 0.875rem; color: var(--text-light);"><?= htmlspecialchars($item['dien_tich']) ?> m²</span>
                                </div>
                            </td>
                            <td style="padding: 1rem; color: var(--text-color); font-weight: 500;">
                                <?= number_format($item['gia_thue'], 0, ',', '.') ?>đ <span style="font-size: 0.8em; color: var(--text-light);">/ 1 tháng</span>
                            </td>
                            <td style="padding: 1rem;">
                                <form action="?controller=cart&action=update" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($item['ma_phong']) ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="60" style="width: 60px; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 0.25rem; text-align: center;">
                                    <button type="submit" class="btn btn-outline" style="padding: 0.5rem; background-color: #f3f4f6; color: var(--text-color);" title="Cập nhật">
                                        <i class="fa-solid fa-rotate-right"></i>
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 1rem; font-weight: bold; color: #dc2626;">
                                <?= number_format($item['item_total'], 0, ',', '.') ?>đ
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <a href="?controller=cart&action=remove&id=<?= htmlspecialchars($item['ma_phong']) ?>" class="btn-remove" style="color: #ef4444; padding: 0.5rem; border-radius: 0.25rem; transition: background 0.2s; display: inline-block; background-color: #fee2e2;" title="Xóa bỏ phòng này" onclick="return confirm('Bạn có chắc muốn bỏ phòng này khỏi giỏ?');">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-summary" style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 2rem;">
            <div class="cart-actions" style="display: flex; gap: 1rem; align-items: center;">
                <a href="?controller=room&action=list" class="btn btn-outline" style="display: inline-block; padding: 0.75rem 1.5rem;">
                    <i class="fa-solid fa-arrow-left"></i> Tiếp tục chọn phòng
                </a>
                <a href="?controller=cart&action=clear" onclick="return confirm('Bạn chắc chắn muốn xóa toàn bộ giỏ hàng?');" style="color: #6b7280; text-decoration: none; font-size: 0.875rem: display: flex; align-items: center; gap: 0.25rem; transition: color 0.2s;">
                    <i class="fa-solid fa-eraser"></i> Làm mới giỏ hàng
                </a>
            </div>
            
            <div class="cart-totals" style="background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid var(--border-color); min-width: 300px; max-width: 400px; width: 100%;">
                <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.25rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">Tổng Quan Đơn Đặt Phòng</h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--text-light);">
                    <span>Người thuê:</span>
                    <strong style="color: var(--text-color);"><?= htmlspecialchars($_SESSION['user_name']) ?></strong>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--text-light);">
                    <span>Tổng tạm tính:</span>
                    <strong><?= number_format($totalPrice, 0, ',', '.') ?>đ</strong>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: bold; color: #dc2626; border-top: 2px solid #f3f4f6; padding-top: 1rem;">
                    <span>Tổng tiền:</span>
                    <span><?= number_format($totalPrice, 0, ',', '.') ?>đ</span>
                </div>
                
                <a href="?controller=checkout&action=index" class="btn btn-primary" style="display: block; text-align: center; padding: 1rem; font-size: 1.1rem; width: 100%; box-sizing: border-box;">
                    Tiến Hành Đặt Phòng <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i>
                </a>
                <p style="text-align: center; font-size: 0.8rem; color: var(--text-light); margin-top: 1rem; margin-bottom: 0;">
                    Thông tin liên hệ sẽ được lấy tự động từ tài khoản của bạn.
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>
