<?php
$path_to_root = './';
require_once 'views/layouts/header.php';
?>

<div class="cart-container" style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem;">
    <h2 style="color: var(--primary); margin-bottom: 2rem; border-bottom: 2px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
        Giỏ Hàng Của Bạn (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)
    </h2>

    <?php if (empty($cartItems)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background-color: rgba(255,255,255,0.02); border-radius: 0.5rem; border: 1px dashed rgba(255,255,255,0.1);">
            <i class="fa-solid fa-cart-arrow-down" style="font-size: 4rem; color: #64748b; margin-bottom: 1rem;"></i>
            <h3 style="color: white; margin-bottom: 1rem;">Giỏ hàng của bạn đang trống</h3>
            <a href="index.php?controller=user_room&action=list" class="btn btn-primary" style="display: inline-block; padding: 0.75rem 1.5rem;">
                Xem danh sách phòng
            </a>
        </div>
    <?php else: ?>
        <div class="cart-table-wrapper" style="overflow-x: auto; background: rgba(255, 255, 255, 0.05); border-radius: 1rem; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 2rem;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <th style="padding: 1rem; font-weight: 600; color: white;">Phòng</th>
                        <th style="padding: 1rem; font-weight: 600; color: white;">Đơn Giá</th>
                        <th style="padding: 1rem; font-weight: 600; color: white;">Thời Gian Thuê</th>
                        <th style="padding: 1rem; font-weight: 600; color: white;">Thành Tiền</th>
                        <th style="padding: 1rem; font-weight: 600; text-align: center; color: white;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                                <?php $img_src = !empty($item['hinh_anh']) ? htmlspecialchars($item['hinh_anh']) : 'https://placehold.co/100x75?text=Phong+' . $item['ma_phong']; ?>
                                <img src="<?php echo $img_src; ?>" alt="Phòng <?php echo htmlspecialchars($item['ma_phong']); ?>" style="width: 80px; height: 60px; object-fit: cover; border-radius: 0.25rem;">
                                <div>
                                    <strong style="display: block; font-size: 1.1rem; color: white;">Phòng <?php echo htmlspecialchars($item['ma_phong']); ?></strong>
                                    <span style="font-size: 0.875rem; color: var(--text-muted);"><?php echo htmlspecialchars($item['dien_tich']); ?> m²</span>
                                </div>
                            </td>
                            <td style="padding: 1rem; color: #e2e8f0; font-weight: 500;">
                                <?php echo formatMoney($item['gia_thue']); ?> <span style="font-size: 0.8em; color: var(--text-muted);">/ 1 tháng</span>
                            </td>
                            <td style="padding: 1rem;">
                                <form action="index.php?controller=cart&action=update" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['ma_phong']); ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="60" style="width: 60px; padding: 0.5rem; border: 1px solid rgba(255,255,255,0.1); background: rgba(15, 23, 42, 0.4); color: white; border-radius: 0.25rem; text-align: center;">
                                    <button type="submit" class="btn btn-outline" style="padding: 0.5rem; background-color: rgba(255,255,255,0.1); color: white; border: none;" title="Cập nhật">
                                        <i class="fa-solid fa-rotate-right"></i>
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 1rem; font-weight: bold; color: var(--danger);">
                                <?php echo formatMoney($item['item_total']); ?>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <a href="index.php?controller=cart&action=remove&id=<?php echo htmlspecialchars($item['ma_phong']); ?>" class="btn-remove" style="color: #ef4444; padding: 0.5rem 0.75rem; border-radius: 0.25rem; transition: background 0.2s; display: inline-block; background-color: rgba(239, 68, 68, 0.2);" title="Xóa bỏ phòng này" onclick="return confirm('Bạn có chắc muốn bỏ phòng này khỏi giỏ?');">
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
                <a href="index.php?controller=user_room&action=list" class="btn btn-outline" style="display: inline-block; padding: 0.75rem 1.5rem; border-color: rgba(255,255,255,0.2); color: white; background: transparent;">
                    <i class="fa-solid fa-arrow-left"></i> Tiếp tục chọn phòng
                </a>
                <a href="index.php?controller=cart&action=clear" onclick="return confirm('Bạn chắc chắn muốn xóa toàn bộ giỏ hàng?');" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; transition: color 0.2s;">
                    <i class="fa-solid fa-eraser"></i> Làm mới giỏ hàng
                </a>
            </div>
            
            <div class="cart-totals" style="background: rgba(255, 255, 255, 0.05); padding: 2rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.1); min-width: 300px; max-width: 400px; width: 100%;">
                <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem; color: white;">Tổng Quan Đơn Đặt Phòng</h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--text-muted);">
                    <span>Người thuê:</span>
                    <strong style="color: white;"><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--text-muted);">
                    <span>Tổng tạm tính:</span>
                    <strong style="color: white;"><?php echo formatMoney($totalPrice); ?></strong>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: bold; color: var(--danger); border-top: 2px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                    <span>Tổng tiền:</span>
                    <span><?php echo formatMoney($totalPrice); ?></span>
                </div>
                
                <a href="index.php?controller=checkout&action=index" class="btn btn-primary" style="display: block; text-align: center; padding: 1rem; font-size: 1.1rem; width: 100%; box-sizing: border-box;">
                    Tiến Hành Đặt Phòng <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i>
                </a>
                <p style="text-align: center; font-size: 0.8rem; color: var(--text-muted); margin-top: 1rem; margin-bottom: 0;">
                    Thông tin liên hệ sẽ được lấy tự động từ tài khoản của bạn.
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
