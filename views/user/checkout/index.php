<?php
$path_to_root = './';
require_once 'views/layouts/header.php';
?>

<div class="checkout-container"
    style="max-width: 800px; margin: 2rem auto; padding: 2rem; background: rgba(255, 255, 255, 0.05); border-radius: 1rem; backdrop-filter: blur(10px); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1);">
    <h2 style="text-align: center; color: var(--primary); margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid rgba(255,255,255,0.1);">
        Xác Nhận Đặt Phòng
    </h2>

    <form action="index.php?controller=checkout&action=process" method="POST">
        <div style="background-color: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem; border: 1px solid rgba(255,255,255,0.05);">
            <h3 style="margin-top: 0; color: white; margin-bottom: 1rem;">Thông tin người thuê</h3>
            <p style="margin: 0.5rem 0; color: #e2e8f0;"><strong>Họ Tên:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            <p style="margin: 0.5rem 0; font-size: 0.875rem; color: var(--text-muted);">(Hợp đồng sẽ được tạo tự động dựa trên thông tin CCCD và Số điện thoại bạn đã đăng ký trên hệ thống)</p>

            <div style="margin-top: 1rem;">
                <label for="ngay_bat_dau" style="font-weight: bold; color: #e2e8f0;">Ngày bắt đầu thuê:</label>
                <input type="date" id="ngay_bat_dau" name="ngay_bat_dau" required min="<?php echo date('Y-m-d'); ?>"
                    value="<?php echo date('Y-m-d'); ?>"
                    style="padding: 0.5rem; margin-top: 0.5rem; border: 1px solid rgba(255,255,255,0.1); background: rgba(15, 23, 42, 0.4); color: white; border-radius: 0.25rem;">
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed rgba(255,255,255,0.1);">
                <label style="font-weight: bold; color: #e2e8f0; display: block; margin-bottom: 0.75rem;">Phương thức thanh toán khoản cọc:</label>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border: 1px solid rgba(255,255,255,0.1); border-radius: 0.25rem; background: rgba(15, 23, 42, 0.2); color: white;">
                        <input type="radio" name="phuong_thuc_thanh_toan" value="bank_transfer" checked style="accent-color: var(--primary);">
                        <i class="fa-solid fa-building-columns" style="color: #94a3b8;"></i> Chuyển khoản ngân hàng trực tiếp
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border: 1px solid rgba(255,255,255,0.1); border-radius: 0.25rem; background: rgba(15, 23, 42, 0.2); color: white;">
                        <input type="radio" name="phuong_thuc_thanh_toan" value="momo" style="accent-color: var(--primary);">
                        <i class="fa-solid fa-wallet" style="color: #a21caf;"></i> Ví điện tử MoMo
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border: 1px solid rgba(255,255,255,0.1); border-radius: 0.25rem; background: rgba(15, 23, 42, 0.2); color: white;">
                        <input type="radio" name="phuong_thuc_thanh_toan" value="cash" style="accent-color: var(--primary);">
                        <i class="fa-solid fa-money-bill-1-wave" style="color: #22c55e;"></i> Thanh toán tiền mặt (Yêu cầu qua VP trong 24h)
                    </label>
                </div>
                <div style="font-size: 0.875rem; color: var(--text-muted); margin-top: 0.75rem;">
                    * Hạn thanh toán và thông tin chi tiết (Mã QR, STK) sẽ được hệ thống gửi ngay sau khi bạn xác nhận tạo hợp đồng thành công.
                </div>
            </div>
        </div>

        <h3 style="color: white; margin-bottom: 1rem;">Chi tiết phòng đặt</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem; text-align: left;">
            <thead>
                <tr style="background-color: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.1); color: white;">
                    <th style="padding: 1rem;">Phòng</th>
                    <th style="padding: 1rem; text-align: center;">Thời Gian (Tháng)</th>
                    <th style="padding: 1rem; text-align: right;">Đơn Giá</th>
                    <th style="padding: 1rem; text-align: right;">Tiền Cọc (1 Tháng)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($checkoutItems as $item): ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); color: #cbd5e1;">
                        <td style="padding: 1rem;">
                            <strong>Phòng <?php echo htmlspecialchars($item['ma_phong']); ?></strong>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <?php echo htmlspecialchars($item['quantity']); ?>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <?php echo formatMoney($item['gia_thue']); ?>
                        </td>
                        <td style="padding: 1rem; text-align: right; color: white;">
                            <?php echo formatMoney($item['gia_thue']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Dịch Vụ Đi Kèm -->
        <h3 style="color: white; margin-bottom: 1rem;">Dịch vụ đi kèm (Thanh toán hàng tháng)</h3>
        <div style="background-color: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem; border: 1px solid rgba(255,255,255,0.05); display: flex; flex-direction: column; gap: 1rem;">
            
            <!-- Điện -->
            <label style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: 1px solid rgba(255,255,255,0.1); border-radius: 0.25rem; background: rgba(255,255,255,0.05); cursor: not-allowed; opacity: 0.9; color: #cbd5e1;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" checked disabled style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <div>
                        <div style="font-weight: bold; color: white;">Giá Điện (Bắt buộc)</div>
                        <div style="font-size: 0.875rem; color: var(--text-muted);">Tính theo số kWh tiêu thụ thực tế</div>
                    </div>
                </div>
                <div style="font-weight: 500; color: white;">3.500đ / kWh</div>
            </label>

            <!-- Nước -->
            <label style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: 1px solid rgba(255,255,255,0.1); border-radius: 0.25rem; background: rgba(255,255,255,0.05); cursor: not-allowed; opacity: 0.9; color: #cbd5e1;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" checked disabled style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <div>
                        <div style="font-weight: bold; color: white;">Giá Nước (Bắt buộc)</div>
                        <div style="font-size: 0.875rem; color: var(--text-muted);">Tính theo từng mét khối (m3) tiêu thụ</div>
                    </div>
                </div>
                <div style="font-weight: 500; color: white;">15.000đ / m3</div>
            </label>

            <!-- Internet -->
            <label style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: 1px solid var(--primary); border-radius: 0.25rem; background: rgba(15, 23, 42, 0.4); cursor: pointer; transition: all 0.2s; color: white;" id="internet_label">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" id="include_internet" name="include_internet" value="1" checked style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <div>
                        <div style="font-weight: bold; color: white;">Internet / Wifi (Tùy chọn)</div>
                        <div style="font-size: 0.875rem; color: var(--text-muted);">Tốc độ cao, ổn định 24/7. Hủy bất kỳ lúc nào.</div>
                    </div>
                </div>
                <div style="font-weight: 500; color: var(--primary);">100.000đ / tháng (Phòng)</div>
            </label>
        </div>

        <div style="background-color: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem; border: 1px solid rgba(255,255,255,0.05);">
            <h3 style="color: white; margin-top: 0; margin-bottom: 1rem;">Tổng Kết Thanh Toán</h3>

            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; color: #cbd5e1;">
                <span>Tổng Tiền Thuê & Dịch vụ:</span>
                <span id="total_rent_display" style="font-weight: 500; color: white;">...đ</span>
            </div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; color: #cbd5e1;">
                <span>Tổng Tiền Cọc (1 Tháng / Phòng):</span>
                <span style="font-weight: 500; color: white;"><?php echo formatMoney($totalDeposit ?? 0); ?></span>
            </div>

            <div style="border-top: 1px dashed rgba(255,255,255,0.1); margin: 1rem 0;"></div>

            <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold; color: var(--danger);">
                <span>Tổng Cộng Cần Thanh Toán (Dự kiến):</span>
                <span id="grand_total_display">...đ</span>
            </div>

            <p style="margin-top: 0.5rem; margin-bottom: 0; font-size: 0.875rem; color: #fca5a5;">
                *Tiền cọc sẽ được hoàn trả sau khi kết thúc hợp đồng theo quy định. Tiền thuê thu cho đợt đầu tiên đã bao gồm dịch vụ nếu chọn. Phí Điện/Nước sẽ tính cuối tháng theo thực tế.
            </p>
        </div>

        <div style="text-align: center;">
            <input type="hidden" name="source" value="<?php echo htmlspecialchars($source); ?>">
            <input type="hidden" name="items_data" value="<?php echo htmlspecialchars(json_encode($checkoutItems)); ?>">

            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="index.php?controller=<?php echo $source === 'cart' ? 'cart' : 'user_room&action=list'; ?>" class="btn btn-outline"
                    style="min-width: 150px; background: transparent; border-color: rgba(255,255,255,0.2); color: white;">
                    Quay Lại
                </a>
                <button type="submit" class="btn btn-primary"
                    style="min-width: 250px; font-size: 1.1rem; padding: 1rem;"
                    onclick="return confirm('Bạn xác nhận muốn đặt phòng và đồng ý tạo hợp đồng?');">
                    Xác Nhận Đặt Phòng
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const internetCheckbox = document.getElementById('include_internet');
        const internetLabel = document.getElementById('internet_label');
        const totalRentDisplay = document.getElementById('total_rent_display');
        const grandTotalDisplay = document.getElementById('grand_total_display');
        
        const baseRent = <?php echo floatval($totalPrice ?? 0); ?>;
        const totalDeposit = <?php echo floatval($totalDeposit ?? 0); ?>;
        const numRooms = <?php echo count($checkoutItems); ?>;
        const INTERNET_COST = 100000;

        function updateTotals() {
            let currentRent = baseRent;
            let currentInternet = 0;
            
            if (internetCheckbox.checked) {
                internetLabel.style.borderColor = 'var(--primary)';
                internetLabel.style.background = 'rgba(15, 23, 42, 0.6)';
                currentInternet = INTERNET_COST * numRooms;
            } else {
                internetLabel.style.borderColor = 'rgba(255,255,255,0.1)';
                internetLabel.style.background = 'rgba(255,255,255,0.02)';
            }

            const activeTotalRent = currentRent + currentInternet;
            const grandTotal = activeTotalRent + totalDeposit;

            // Simple VND formatter
            const formatVND = (num) => new Intl.NumberFormat('vi-VN').format(num) + ' VNĐ';

            totalRentDisplay.innerText = formatVND(activeTotalRent);
            grandTotalDisplay.innerText = formatVND(grandTotal);
        }

        internetCheckbox.addEventListener('change', updateTotals);
        updateTotals();
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>
