<div class="account-container"
    style="max-width: 1000px; margin: 2rem auto; display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">

    <!-- Sidebar: User Info -->
    <div class="profile-card"
        style="background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 2rem; text-align: center; border: 1px solid var(--border-color); position: sticky; top: 2rem;">
        <div class="avatar"
            style="width: 100px; height: 100px; background-color: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--text-light); margin: 0 auto 1.5rem; border: 4px solid #e5e7eb;">
            <i class="fa-solid fa-user"></i>
        </div>
        <h2 style="margin-top: 0; margin-bottom: 0.5rem; color: var(--text-color); font-size: 1.5rem;">
            <?= htmlspecialchars($userInfo['ho_ten']) ?>
        </h2>
        <div
            style="display: inline-block; padding: 0.25rem 0.75rem; background-color: #dbeafe; color: #1d4ed8; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; margin-bottom: 1.5rem;">
            Cư dân KTX
        </div>

        <div style="text-align: left; margin-bottom: 1.5rem;">
            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f3f4f6;">
                <div style="font-size: 0.875rem; color: var(--text-light); margin-bottom: 0.25rem;">Mã tài khoản (ID)
                </div>
                <div style="font-weight: 500; color: var(--text-color);"><i class="fa-solid fa-hashtag"
                        style="color: var(--primary-color); width: 20px;"></i>
                    <?= htmlspecialchars($userInfo['ma_nguoi_thue']) ?></div>
            </div>

            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f3f4f6;">
                <div style="font-size: 0.875rem; color: var(--text-light); margin-bottom: 0.25rem;">Số điện thoại</div>
                <div style="font-weight: 500; color: var(--text-color);"><i class="fa-solid fa-phone"
                        style="color: var(--primary-color); width: 20px;"></i>
                    <?= htmlspecialchars($userInfo['so_dien_thoai']) ?></div>
            </div>

            <div style="margin-bottom: 1rem;">
                <div style="font-size: 0.875rem; color: var(--text-light); margin-bottom: 0.25rem;">CMND / CCCD</div>
                <div style="font-weight: 500; color: var(--text-color);"><i class="fa-solid fa-id-card"
                        style="color: var(--primary-color); width: 20px;"></i>
                    <?= htmlspecialchars($userInfo['cccd']) ?></div>
            </div>
        </div>

        <?php if (!isset($_SESSION['verified'])): ?>
            <div
                style="margin-bottom: 1.5rem; text-align: left; background: #fffbeb; border: 1px solid #fde68a; padding: 1rem; border-radius: 0.5rem;">
                <h3 style="margin-top: 0; font-size: 1rem; color: #b45309;"><i class="fa-solid fa-shield-halved"></i> Cung
                    cấp thông tin bổ sung</h3>
                <p style="font-size: 0.875rem; color: #92400e; margin-bottom: 1rem;">Vui lòng tải lên mặt trước và mặt sau
                    CCCD để hoàn tất bước xác nhận danh tính trước khi giao kết hợp đồng.</p>
                <form action="?controller=account&action=verify" method="POST" enctype="multipart/form-data"
                    style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div>
                        <label style="font-size: 0.75rem; font-weight: 600; color: #78350f;">Mặt trước</label>
                        <input type="file" name="cccd_front" accept="image/*" required
                            style="font-size: 0.75rem; width: 100%; border: 1px solid #fcd34d; background: white; padding: 0.25rem;">
                    </div>
                    <div>
                        <label style="font-size: 0.75rem; font-weight: 600; color: #78350f;">Mặt sau</label>
                        <input type="file" name="cccd_back" accept="image/*" required
                            style="font-size: 0.75rem; width: 100%; border: 1px solid #fcd34d; background: white; padding: 0.25rem;">
                    </div>
                    <button type="submit" class="btn btn-primary"
                        style="padding: 0.5rem; font-size: 0.875rem; background-color: #d97706; border-color: #d97706;">Xác
                        nhận tài khoản</button>
                </form>
            </div>
        <?php else: ?>
            <div
                style="margin-bottom: 1.5rem; text-align: left; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 1rem; border-radius: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: bold; color: #065f46;">
                    <i class="fa-solid fa-circle-check"></i> Đang chờ duyệt hồ sơ
                </div>
                <p style="font-size: 0.875rem; color: #047857; margin: 0.5rem 0 0 0;">Cảm ơn bạn đã cung cấp CCCD. Hệ thống
                    đang tiến hành kiểm tra.</p>
            </div>
        <?php endif; ?>

        <a href="?controller=auth&action=logout" class="btn btn-outline"
            style="width: 100%; box-sizing: border-box; display: block; text-align: center; color: #dc2626; border-color: #fca5a5; background-color: #fef2f2;">
            <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
        </a>
    </div>

    <!-- Main Content: Rental History / Contracts -->
    <div class="contracts-section">
        <h2
            style="color: var(--primary-color); margin-top: 0; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color);">
            <i class="fa-solid fa-file-contract"></i> Lịch sử thuê phòng
        </h2>

        <?php if (empty($contracts)): ?>
            <div
                style="background: white; border-radius: 0.5rem; padding: 3rem 2rem; text-align: center; border: 1px dashed var(--border-color);">
                <i class="fa-solid fa-box-open" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-color); margin-bottom: 1rem;">Bạn chưa thuê phòng nào</h3>
                <p style="color: var(--text-light); margin-bottom: 1.5rem;">Hãy xem danh sách phòng và chọn cho mình một căn
                    ưng ý nhé.</p>
                <a href="?controller=room&action=list" class="btn btn-primary">Tìm phòng ngay</a>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <?php foreach ($contracts as $contract): ?>
                    <?php
                    // Determine status badge color
                    $statusColor = '#6b7280'; // Default gray
                    $statusBg = '#f3f4f6';
                    if ($contract['trang_thai'] === 'Dang thue') {
                        $statusColor = '#166534'; // Green
                        $statusBg = '#dcfce7';
                    } elseif ($contract['trang_thai'] === 'Het han') {
                        $statusColor = '#991b1b'; // Red
                        $statusBg = '#fee2e2';
                    }
                    ?>

                    <div class="contract-card"
                        style="background: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid var(--border-color); border-left: 4px solid <?= $statusColor ?>;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                            <div>
                                <h3
                                    style="margin: 0 0 0.5rem 0; color: var(--text-color); display: flex; align-items: center; gap: 0.5rem;">
                                    Phòng <?= htmlspecialchars($contract['ma_phong']) ?>
                                    <span
                                        style="font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 500; background-color: <?= $statusBg ?>; color: <?= $statusColor ?>;">
                                        <?= htmlspecialchars($contract['trang_thai']) ?>
                                    </span>
                                </h3>
                                <div style="font-size: 0.875rem; color: var(--text-light);">
                                    Hợp đồng số: <strong>#<?= htmlspecialchars($contract['ma_hop_dong']) ?></strong>
                                    (Tạo ngày <?= date('d/m/Y', strtotime($contract['ngay_bat_dau'])) ?>)
                                </div>
                            </div>
                            <!-- Icon represents contract -->
                            <div
                                style="width: 48px; height: 48px; background-color: #f8fafc; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: var(--primary-color);">
                                <i class="fa-solid fa-house-chimney-window"></i>
                            </div>
                        </div>

                        <div
                            style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; background-color: #f9fafb; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                            <div>
                                <div
                                    style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase; font-weight: 600; margin-bottom: 0.25rem;">
                                    Thời hạn</div>
                                <div style="font-weight: 500; color: var(--text-color);">
                                    <?= date('d/m/Y', strtotime($contract['ngay_bat_dau'])) ?> -
                                    <?= date('d/m/Y', strtotime($contract['ngay_ket_thuc'])) ?>
                                </div>
                            </div>
                            <div>
                                <div
                                    style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase; font-weight: 600; margin-bottom: 0.25rem;">
                                    Diện tích phòng</div>
                                <div style="font-weight: 500; color: var(--text-color);">
                                    <?= htmlspecialchars($contract['dien_tich']) ?> m²
                                </div>
                            </div>
                        </div>

                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px dashed var(--border-color);">
                            <div>
                                <div style="font-size: 0.875rem; color: var(--text-light);">Giá thuê hàng tháng:</div>
                                <div style="font-weight: bold; color: var(--text-color);">
                                    <?= number_format($contract['gia_thue'], 0, ',', '.') ?> VNĐ
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.875rem; color: var(--text-light);">Tiền cọc đã thu:</div>
                                <div style="font-weight: bold; color: #dc2626;">
                                    <?= number_format($contract['tien_coc'], 0, ',', '.') ?> VNĐ
                                </div>
                            </div>
                        </div>

                        <?php if ($contract['trang_thai'] === 'Dang thue'): ?>
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                                <form action="?controller=account&action=extend" method="POST"
                                    style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem;"
                                    onsubmit="return confirm('Xác nhận gia hạn hợp đồng thêm số tháng đã chọn? Hệ thống sẽ tạo liên hệ thủ tục sau.');">
                                    <input type="hidden" name="contract_id"
                                        value="<?= htmlspecialchars($contract['ma_hop_dong']) ?>">
                                    <label for="months_<?= $contract['ma_hop_dong'] ?>"
                                        style="font-size: 0.875rem; color: #475569; font-weight: 500;">Gia hạn phòng
                                        (tháng):</label>
                                    <input type="number" id="months_<?= $contract['ma_hop_dong'] ?>" name="months" value="1" min="1"
                                        max="12" required
                                        style="width: 70px; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                    <button type="submit" class="btn btn-primary"
                                        style="padding: 0.5rem 1rem; font-size: 0.875rem;"><i
                                            class="fa-solid fa-clock-rotate-left"></i> Xác nhận</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Support & Feedback Section -->
        <div style="margin-top: 2rem;">
            <div style="background: white; border-radius: 0.5rem; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <h2 style="color: var(--primary-color); margin-top: 0; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color);">
                    <i class="fa-solid fa-headset"></i> Gửi yêu cầu & Báo cáo sự cố
                </h2>
                <p style="color: var(--text-light); margin-bottom: 1.5rem;">Bạn đang gặp vấn đề về phòng ở, cần bảo trì thiết bị hay có góp ý với hệ thống? Hãy gửi cho chúng tôi tại đây để được hỗ trợ nhanh nhất.</p>
                
                <form action="?controller=account&action=submitFeedback" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div>
                        <label for="feedback_type" style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Phân loại yêu cầu <span style="color: red;">*</span></label>
                        <select id="feedback_type" name="feedback_type" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; background-color: #f9fafb; font-size: 1rem; color: #0f172a;">
                            <option value="" disabled selected>-- Chọn loại vấn đề --</option>
                            <option value="Yêu cầu bảo trì">Yêu cầu bảo trì (Điện, nước, cơ sở vật chất)</option>
                            <option value="Khiếu nại">Khiếu nại (Tiếng ồn, an ninh, vệ sinh)</option>
                            <option value="Góp ý">Góp ý (Đề xuất cải tiến hệ thống KTX)</option>
                            <option value="Khác">Vấn đề khác</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="feedback_content" style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Nội dung chi tiết <span style="color: red;">*</span></label>
                        <textarea id="feedback_content" name="feedback_content" rows="4" required placeholder="Vui lòng cung cấp chi tiết (Ví dụ: Ống nước phòng 3 bị rò rỉ dưới bồn rửa mặt...)" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; font-family: inherit; font-size: 1rem; resize: vertical; box-sizing: border-box;"></textarea>
                    </div>
                    
                    <div style="text-align: right;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem; border-radius: 0.5rem;">
                            <i class="fa-solid fa-paper-plane"></i> Gửi thông báo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>