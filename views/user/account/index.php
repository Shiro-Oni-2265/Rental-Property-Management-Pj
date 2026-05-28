<?php
$path_to_root = './';
require_once 'views/layouts/header.php';
?>

<div class="account-container" style="max-width: 1000px; margin: 2rem auto; display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">

    <!-- Sidebar: User Info -->
    <div class="profile-card"
        style="background: rgba(255, 255, 255, 0.05); border-radius: 1rem; backdrop-filter: blur(10px); padding: 2rem; text-align: center; border: 1px solid rgba(255, 255, 255, 0.1); position: sticky; top: 2rem; box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);">
        <div class="avatar"
            style="width: 100px; height: 100px; background-color: rgba(255, 255, 255, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #94a3b8; margin: 0 auto 1.5rem; border: 4px solid rgba(255,255,255,0.1);">
            <i class="fa-solid fa-user"></i>
        </div>
        <h2 style="margin-top: 0; margin-bottom: 0.5rem; color: white; font-size: 1.5rem;">
            <?php echo htmlspecialchars($userInfo['ho_ten']); ?>
        </h2>
        <div style="display: inline-block; padding: 0.25rem 0.75rem; background-color: rgba(59, 130, 246, 0.2); color: #93c5fd; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; margin-bottom: 1.5rem;">
            Cư dân KTX
        </div>

        <div style="text-align: left; margin-bottom: 1.5rem;">
            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Mã tài khoản (ID)</div>
                <div style="font-weight: 500; color: white;">
                    <i class="fa-solid fa-hashtag" style="color: var(--primary); width: 20px;"></i>
                    <?php echo htmlspecialchars($userInfo['ma_nguoi_thue']); ?>
                </div>
            </div>

            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Số điện thoại</div>
                <div style="font-weight: 500; color: white;">
                    <i class="fa-solid fa-phone" style="color: var(--primary); width: 20px;"></i>
                    <?php echo htmlspecialchars($userInfo['so_dien_thoai']); ?>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">CMND / CCCD</div>
                <div style="font-weight: 500; color: white;">
                    <i class="fa-solid fa-id-card" style="color: var(--primary); width: 20px;"></i>
                    <?php echo htmlspecialchars($userInfo['cccd']); ?>
                </div>
            </div>
        </div>

        <?php if (!isset($_SESSION['verified'])): ?>
            <div style="margin-bottom: 1.5rem; text-align: left; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); padding: 1rem; border-radius: 0.5rem;">
                <h3 style="margin-top: 0; font-size: 1rem; color: #fbbf24;"><i class="fa-solid fa-shield-halved"></i> Xác nhận danh tính</h3>
                <p style="font-size: 0.875rem; color: #fef3c7; margin-bottom: 1rem;">Vui lòng tải lên mặt trước và mặt sau CCCD để hoàn tất bước xác nhận danh tính trước khi giao kết hợp đồng.</p>
                <form action="index.php?controller=account&action=verify" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div>
                        <label style="font-size: 0.75rem; font-weight: 600; color: #fef3c7;">Mặt trước</label>
                        <input type="file" name="cccd_front" accept="image/*" required style="font-size: 0.75rem; width: 100%; border: 1px solid rgba(255,255,255,0.1); background: rgba(15, 23, 42, 0.4); color: white; padding: 0.25rem;">
                    </div>
                    <div>
                        <label style="font-size: 0.75rem; font-weight: 600; color: #fef3c7;">Mặt sau</label>
                        <input type="file" name="cccd_back" accept="image/*" required style="font-size: 0.75rem; width: 100%; border: 1px solid rgba(255,255,255,0.1); background: rgba(15, 23, 42, 0.4); color: white; padding: 0.25rem;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem; font-size: 0.875rem; background-color: #d97706; border-color: #d97706;">Xác nhận tài khoản</button>
                </form>
            </div>
        <?php else: ?>
            <div style="margin-bottom: 1.5rem; text-align: left; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); padding: 1rem; border-radius: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: bold; color: #a7f3d0;">
                    <i class="fa-solid fa-circle-check"></i> Đang chờ duyệt hồ sơ
                </div>
                <p style="font-size: 0.875rem; color: #e2e8f0; margin: 0.5rem 0 0 0;">Cảm ơn bạn đã cung cấp CCCD. Hệ thống đang tiến hành kiểm tra.</p>
            </div>
        <?php endif; ?>

        <a href="index.php?controller=auth&action=logout" class="btn btn-outline"
            style="width: 100%; box-sizing: border-box; display: block; text-align: center; color: #fca5a5; border-color: rgba(239, 68, 68, 0.3); background-color: rgba(239, 68, 68, 0.1);">
            <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
        </a>
    </div>

    <!-- Main Content: Rental History / Contracts -->
    <div class="contracts-section">
        <h2 style="color: var(--primary); margin-top: 0; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid rgba(255,255,255,0.1);">
            <i class="fa-solid fa-file-contract"></i> Lịch sử thuê phòng
        </h2>

        <?php if (empty($contracts)): ?>
            <div style="background: rgba(255, 255, 255, 0.05); border-radius: 1rem; border: 1px solid rgba(255,255,255,0.1); padding: 3rem 2rem; text-align: center; border-style: dashed;">
                <i class="fa-solid fa-box-open" style="font-size: 4rem; color: #64748b; margin-bottom: 1rem;"></i>
                <h3 style="color: white; margin-bottom: 1rem;">Bạn chưa thuê phòng nào</h3>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Hãy xem danh sách phòng và chọn cho mình một căn ưng ý nhé.</p>
                <a href="index.php?controller=user_room&action=list" class="btn btn-primary">Tìm phòng ngay</a>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <?php foreach ($contracts as $contract): ?>
                    <?php
                    $statusColor = '#94a3b8';
                    $statusBg = 'rgba(148, 163, 184, 0.2)';
                    if ($contract['trang_thai'] === 'Dang thue') {
                        $statusColor = '#10b981';
                        $statusBg = 'rgba(16, 185, 129, 0.2)';
                    } elseif ($contract['trang_thai'] === 'Het han') {
                        $statusColor = '#ef4444';
                        $statusBg = 'rgba(239, 68, 68, 0.2)';
                    }
                    ?>

                    <div class="contract-card" style="background: rgba(255, 255, 255, 0.05); border-radius: 1rem; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.1); border-left: 4px solid <?php echo $statusColor; ?>; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                            <div>
                                <h3 style="margin: 0 0 0.5rem 0; color: white; display: flex; align-items: center; gap: 0.5rem;">
                                    Phòng <?php echo htmlspecialchars($contract['ma_phong']); ?>
                                    <span style="font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 500; background-color: <?php echo $statusBg; ?>; color: <?php echo $statusColor; ?>;">
                                        <?php echo htmlspecialchars($contract['trang_thai']); ?>
                                    </span>
                                </h3>
                                <div style="font-size: 0.875rem; color: var(--text-muted);">
                                    Hợp đồng số: <strong>#<?php echo htmlspecialchars($contract['ma_hop_dong']); ?></strong> (Tạo ngày <?php echo date('d/m/Y', strtotime($contract['ngay_bat_dau'])); ?>)
                                </div>
                            </div>
                            <div style="width: 48px; height: 48px; background-color: rgba(255,255,255,0.05); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: var(--primary);">
                                <i class="fa-solid fa-house-chimney-window"></i>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; background-color: rgba(15, 23, 42, 0.4); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                            <div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 0.25rem;">Thời hạn</div>
                                <div style="font-weight: 500; color: #cbd5e1;">
                                    <?php echo date('d/m/Y', strtotime($contract['ngay_bat_dau'])); ?> - <?php echo date('d/m/Y', strtotime($contract['ngay_ket_thuc'])); ?>
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 0.25rem;">Diện tích phòng</div>
                                <div style="font-weight: 500; color: #cbd5e1;">
                                    <?php echo htmlspecialchars($contract['dien_tich']); ?> m²
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px dashed rgba(255,255,255,0.1);">
                            <div>
                                <div style="font-size: 0.875rem; color: var(--text-muted);">Giá thuê hàng tháng:</div>
                                <div style="font-weight: bold; color: white;">
                                    <?php echo formatMoney($contract['gia_thue']); ?>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.875rem; color: var(--text-muted);">Tiền cọc đã thu:</div>
                                <div style="font-weight: bold; color: var(--danger);">
                                    <?php echo formatMoney($contract['tien_coc']); ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($contract['trang_thai'] === 'Dang thue'): ?>
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                                <form action="index.php?controller=account&action=extend" method="POST"
                                    style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem;"
                                    onsubmit="return confirm('Xác nhận gia hạn hợp đồng thêm số tháng đã chọn? Hệ thống sẽ tạo liên hệ thủ tục sau.');">
                                    <input type="hidden" name="contract_id" value="<?php echo htmlspecialchars($contract['ma_hop_dong']); ?>">
                                    <label for="months_<?php echo $contract['ma_hop_dong']; ?>" style="font-size: 0.875rem; color: #cbd5e1; font-weight: 500;">Gia hạn phòng (tháng):</label>
                                    <input type="number" id="months_<?php echo $contract['ma_hop_dong']; ?>" name="months" value="1" min="1" max="12" required
                                        style="width: 70px; padding: 0.5rem; border: 1px solid rgba(255,255,255,0.1); background: rgba(15, 23, 42, 0.4); color: white; border-radius: 0.25rem;">
                                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;"><i class="fa-solid fa-clock-rotate-left"></i> Xác nhận</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Support & Feedback Section -->
        <div style="margin-top: 2rem;">
            <div style="background: rgba(255, 255, 255, 0.05); border-radius: 1rem; border: 1px solid rgba(255,255,255,0.1); padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <h2 style="color: var(--primary); margin-top: 0; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid rgba(255,255,255,0.1);">
                    <i class="fa-solid fa-headset"></i> Gửi yêu cầu & Báo cáo sự cố
                </h2>
                <p style="color: #cbd5e1; margin-bottom: 1.5rem;">Bạn đang gặp vấn đề về phòng ở, cần bảo trì thiết bị hay có góp ý với hệ thống? Hãy gửi cho chúng tôi tại đây để được hỗ trợ nhanh nhất.</p>
                
                <form action="index.php?controller=account&action=submitFeedback" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div>
                        <label for="feedback_type" style="display: block; font-weight: 600; color: white; margin-bottom: 0.5rem;">Phân loại yêu cầu <span style="color: red;">*</span></label>
                        <select id="feedback_type" name="feedback_type" required style="width: 100%; padding: 0.75rem; border: 1px solid rgba(255,255,255,0.1); border-radius: 0.5rem; background: rgba(15, 23, 42, 0.6); color: white; font-size: 1rem;">
                            <option value="" disabled selected>-- Chọn loại vấn đề --</option>
                            <option value="Yêu cầu bảo trì">Yêu cầu bảo trì (Điện, nước, cơ sở vật chất)</option>
                            <option value="Khiếu nại">Khiếu nại (Tiếng ồn, an ninh, vệ sinh)</option>
                            <option value="Góp ý">Góp ý (Đề xuất cải tiến hệ thống KTX)</option>
                            <option value="Khác">Vấn đề khác</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="feedback_content" style="display: block; font-weight: 600; color: white; margin-bottom: 0.5rem;">Nội dung chi tiết <span style="color: red;">*</span></label>
                        <textarea id="feedback_content" name="feedback_content" rows="4" required placeholder="Vui lòng cung cấp chi tiết (Ví dụ: Ống nước phòng 3 bị rò rỉ dưới bồn rửa mặt...)" style="width: 100%; padding: 0.75rem; border: 1px solid rgba(255,255,255,0.1); background: rgba(15, 23, 42, 0.4); color: white; border-radius: 0.5rem; font-family: inherit; font-size: 1rem; resize: vertical; box-sizing: border-box;"></textarea>
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

<?php require_once 'views/layouts/footer.php'; ?>
