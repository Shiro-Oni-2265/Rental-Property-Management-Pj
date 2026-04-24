<div class="checkout-container"
    style="max-width: 800px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid var(--border-color);">
    <h2
        style="text-align: center; color: var(--primary-color); margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #f3f4f6;">
        Xác Nhận Đặt Phòng
    </h2>

    <form action="?controller=checkout&action=process" method="POST">
        <div
            style="background-color: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
            <h3 style="margin-top: 0; color: #334155; margin-bottom: 1rem;">Thông tin người thuê</h3>
            <p style="margin: 0.5rem 0;"><strong>Họ Tên:</strong> <?= htmlspecialchars($_SESSION['user_name']) ?></p>
            <p style="margin: 0.5rem 0; font-size: 0.875rem; color: #64748b;">(Hợp đồng sẽ được tạo tự động dựa trên
                thông tin CCCD và Số điện thoại bạn đã đăng ký trên hệ thống)</p>

            <div style="margin-top: 1rem;">
                <label for="ngay_bat_dau" style="font-weight: bold; color: #334155;">Ngày bắt đầu thuê:</label>
                <input type="date" id="ngay_bat_dau" name="ngay_bat_dau" required min="<?= date('Y-m-d') ?>"
                    value="<?= date('Y-m-d') ?>"
                    style="padding: 0.5rem; margin-top: 0.5rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed #cbd5e1;">
                <label style="font-weight: bold; color: #334155; display: block; margin-bottom: 0.75rem;">Phương thức
                    thanh toán khoản cọc:</label>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <label
                        style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 0.25rem; background: white;">
                        <input type="radio" name="phuong_thuc_thanh_toan" value="bank_transfer" checked
                            style="accent-color: var(--primary-color);">
                        <i class="fa-solid fa-building-columns" style="color: #64748b;"></i> Chuyển khoản ngân hàng trực
                        tiếp
                    </label>
                    <label
                        style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 0.25rem; background: white;">
                        <input type="radio" name="phuong_thuc_thanh_toan" value="momo"
                            style="accent-color: var(--primary-color);">
                        <i class="fa-solid fa-wallet" style="color: #a21caf;"></i> Ví điện tử MoMo
                    </label>
                    <label
                        style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 0.25rem; background: white;">
                        <input type="radio" name="phuong_thuc_thanh_toan" value="cash"
                            style="accent-color: var(--primary-color);">
                        <i class="fa-solid fa-money-bill-1-wave" style="color: #16a34a;"></i> Thanh toán tiền mặt (Yêu
                        cầu qua VP trong 24h)
                    </label>
                </div>
                <div style="font-size: 0.875rem; color: #64748b; margin-top: 0.75rem;">
                    * Hạn thanh toán và thông tin chi tiết (Mã QR, STK) sẽ được hệ thống gửi ngay sau khi bạn xác nhận
                    tạo hợp đồng thành công.
                </div>
            </div>
        </div>

        <h3 style="color: #334155; margin-bottom: 1rem;">Chi tiết phòng đặt</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem; text-align: left;">
            <thead>
                <tr style="background-color: #f3f4f6; border-bottom: 1px solid var(--border-color);">
                    <th style="padding: 1rem;">Phòng</th>
                    <th style="padding: 1rem; text-align: center;">Thời Gian (Tháng)</th>
                    <th style="padding: 1rem; text-align: right;">Đơn Giá</th>
                    <th style="padding: 1rem; text-align: right;">Tiền Cọc (1 Tháng)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($checkoutItems as $item): ?>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 1rem;">
                            <strong>Phòng <?= htmlspecialchars($item['ma_phong']) ?></strong>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <?= htmlspecialchars($item['quantity']) ?>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <?= number_format($item['gia_thue'], 0, ',', '.') ?>đ
                        </td>
                        <td style="padding: 1rem; text-align: right; color: var(--text-color);">
                            <?= number_format($item['gia_thue'], 0, ',', '.') ?>đ
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Dịch Vụ Đi Kèm -->
        <h3 style="color: #334155; margin-bottom: 1rem;">Dịch vụ đi kèm (Thanh toán hàng tháng)</h3>
        <div style="background-color: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 1rem;">
            
            <!-- Điện -->
            <label style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: 1px solid #cbd5e1; border-radius: 0.25rem; background: #f1f5f9; cursor: not-allowed; opacity: 0.9;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" checked disabled style="width: 18px; height: 18px; accent-color: var(--primary-color);">
                    <div>
                        <div style="font-weight: bold; color: #334155;">Giá Điện (Bắt buộc)</div>
                        <div style="font-size: 0.875rem; color: #64748b;">Tính theo số kWh tiêu thụ thực tế</div>
                    </div>
                </div>
                <div style="font-weight: 500; color: #0f172a;">3.500đ / kWh</div>
            </label>

            <!-- Nước -->
            <label style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: 1px solid #cbd5e1; border-radius: 0.25rem; background: #f1f5f9; cursor: not-allowed; opacity: 0.9;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" checked disabled style="width: 18px; height: 18px; accent-color: var(--primary-color);">
                    <div>
                        <div style="font-weight: bold; color: #334155;">Giá Nước (Bắt buộc)</div>
                        <div style="font-size: 0.875rem; color: #64748b;">Tính theo từng mét khối (m3) tiêu thụ</div>
                    </div>
                </div>
                <div style="font-weight: 500; color: #0f172a;">4.000đ / m3</div>
            </label>

            <!-- Internet -->
            <label style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: 1px solid var(--primary-color); border-radius: 0.25rem; background: white; cursor: pointer; transition: all 0.2s;" id="internet_label">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" id="include_internet" name="include_internet" value="1" checked style="width: 18px; height: 18px; accent-color: var(--primary-color);">
                    <div>
                        <div style="font-weight: bold; color: #0f172a;">Internet / Wifi (Tùy chọn)</div>
                        <div style="font-size: 0.875rem; color: #64748b;">Tốc độ cao, ổn định 24/7. Hủy bất kỳ lúc nào.</div>
                    </div>
                </div>
                <div style="font-weight: 500; color: var(--primary-color);">100.000đ / tháng (Phòng)</div>
            </label>
        </div>

        <div
            style="background-color: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
            <h3 style="color: #334155; margin-top: 0; margin-bottom: 1rem;">Tổng Kết Thanh Toán</h3>

            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; color: #475569;">
                <span>Tổng Tiền Thuê & Dịch vụ:</span>
                <span id="total_rent_display" style="font-weight: 500;">...đ</span>
            </div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; color: #475569;">
                <span>Tổng Tiền Cọc (1 Tháng / Phòng):</span>
                <span style="font-weight: 500;"><?= number_format($totalDeposit ?? 0, 0, ',', '.') ?>đ</span>
            </div>

            <div style="border-top: 1px dashed #cbd5e1; margin: 1rem 0;"></div>

            <div
                style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold; color: #b91c1c;">
                <span>Tổng Cộng Cần Thanh Toán (Dự kiến):</span>
                <span id="grand_total_display">...đ</span>
            </div>

            <p style="margin-top: 0.5rem; margin-bottom: 0; font-size: 0.875rem; color: #991b1b;">
                *Tiền cọc sẽ được hoàn trả sau khi kết thúc hợp đồng theo quy định. Tiền thuê thu cho đợt đầu tiên đã bao gồm dịch vụ nếu chọn. Phí Điện/Nước sẽ tính cuối tháng theo thực tế.
            </p>
        </div>

        <div style="text-align: center;">
            <input type="hidden" name="source" value="<?= htmlspecialchars($source) ?>">
            <input type="hidden" name="items_data" value="<?= htmlspecialchars(json_encode($checkoutItems)) ?>">

            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="?controller=<?= $source === 'cart' ? 'cart' : 'room&action=list' ?>" class="btn btn-outline"
                    style="min-width: 150px;">
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
        
        // Base variables from PHP
        const baseRent = <?= floatval($totalPrice ?? 0) ?>;
        const totalDeposit = <?= floatval($totalDeposit ?? 0) ?>;
        const numRooms = <?= count($checkoutItems) ?>;
        const INTERNET_COST = 100000;

        function updateTotals() {
            let currentRent = baseRent;
            let currentInternet = 0;
            
            if (internetCheckbox.checked) {
                // Change UI of label
                internetLabel.style.borderColor = 'var(--primary-color)';
                internetLabel.style.background = 'white';
                currentInternet = INTERNET_COST * numRooms;
            } else {
                // Change UI of label to match unselected
                internetLabel.style.borderColor = '#cbd5e1';
                internetLabel.style.background = '#f1f5f9';
            }

            const activeTotalRent = currentRent + currentInternet;
            const grandTotal = activeTotalRent + totalDeposit;

            // Format numbers like PHP's number_format(..., 0, ',', '.')
            totalRentDisplay.innerText = new Intl.NumberFormat('vi-VN').format(activeTotalRent).replace(/\./g, '.') + 'đ';
            grandTotalDisplay.innerText = new Intl.NumberFormat('vi-VN').format(grandTotal).replace(/\./g, '.') + 'đ';
        }

        // Add event listener
        internetCheckbox.addEventListener('change', updateTotals);
        
        // Initial calculation
        updateTotals();
    });
</script>