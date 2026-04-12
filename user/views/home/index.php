<style>
    /* Scoped styles for the new homepage to keep it clean */
    .hero-section {
        background: linear-gradient(135deg, rgba(240, 249, 255, 1) 0%, rgba(224, 242, 254, 1) 100%);
        border-radius: 1rem;
        padding: 4rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        margin-bottom: 4rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        border: 1px solid #bae6fd;
    }

    .hero-content {
        flex: 1;
        max-width: 600px;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 1.5rem;
    }

    .hero-title span {
        color: var(--primary-color);
        position: relative;
        display: inline-block;
    }

    .hero-title span::after {
        content: '';
        position: absolute;
        bottom: 5px;
        left: 0;
        width: 100%;
        height: 8px;
        background-color: #bfdbfe;
        z-index: -1;
        border-radius: 4px;
    }

    .hero-desc {
        font-size: 1.25rem;
        color: #475569;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .hero-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .hero-image {
        flex: 1;
        display: flex;
        justify-content: center;
        position: relative;
    }

    .hero-image img {
        max-width: 100%;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: rotate(2deg);
        transition: transform 0.3s ease;
    }

    .hero-image img:hover {
        transform: rotate(0deg) scale(1.02);
    }

    .floating-badge {
        position: absolute;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
        font-weight: bold;
        color: #0f172a;
        bottom: -1.5rem;
        left: -2rem;
        border: 1px solid #f1f5f9;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    .features-section {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-bottom: 5rem;
    }

    .feature-card {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: #bfdbfe;
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        background-color: #eff6ff;
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin: 0 auto 1.5rem;
    }

    .section-heading {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-heading h2 {
        font-size: 2.25rem;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .section-heading p {
        color: var(--text-light);
        font-size: 1.1rem;
    }

    .about-section {
        background-color: #f8fafc;
        border-radius: 1rem;
        padding: 4rem;
        display: flex;
        align-items: center;
        gap: 4rem;
        margin-bottom: 5rem;
        position: relative;
        overflow: hidden;
    }

    .about-section::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: var(--primary-color);
        opacity: 0.05;
        border-radius: 50%;
    }

    .about-content {
        flex: 1;
    }

    .about-image {
        flex: 1;
    }

    .about-image img {
        border-radius: 1rem;
        width: 100%;
        object-fit: cover;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {

        .hero-section,
        .about-section {
            flex-direction: column;
            padding: 2rem;
        }

        .hero-title {
            font-size: 2.25rem;
        }

        .features-section {
            grid-template-columns: 1fr;
        }

        .floating-badge {
            display: none;
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <div
            style="display: inline-block; padding: 0.35rem 1rem; background-color: #dbeafe; color: #1e3a8a; border-radius: 9999px; font-weight: 600; font-size: 0.875rem; margin-bottom: 1.5rem;">
            🌟 Hệ thống quản lý KTX Tốt Nhất
        </div>
        <h1 class="hero-title">
            Tìm không gian sống <br><span>hoàn hảo</span> của bạn
        </h1>
        <p class="hero-desc">
            Trải nghiệm môi trường sống hiện đại, an ninh và tiện nghi với mức giá sinh viên. Chúng tôi mang đến cho bạn
            không chỉ một căn phòng, mà là một tổ ấm thực sự.
        </p>
        <div class="hero-actions">
            <a href="?controller=room&action=list" class="btn btn-primary"
                style="padding: 1rem 2rem; font-size: 1.1rem; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                TÌM PHÒNG NGAY <i class="fa-solid fa-arrow-right"></i>
            </a>
            <a href="#about" class="btn btn-outline"
                style="padding: 1rem 2rem; font-size: 1.1rem; border-radius: 0.5rem; background: white;">
                Tìm hiểu thêm
            </a>
        </div>
    </div>

    <div class="hero-image">
        <!-- Using a placehold image that looks somewhat aesthetic, in a real app this would be a nice illustration -->
        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80"
            alt="KTX Hiện đại" style="width: 100%; max-width: 500px; height: 350px;">
        <div class="floating-badge">
            <div
                style="background-color: #22c55e; width: 30px; height: 30px; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">100% Xác thực
                </div>
                <div style="font-size: 1.1rem; color: #0f172a;">Phòng chất lượng</div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="features-section">
    <div class="feature-card">
        <div class="feature-icon">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h3 style="margin-top: 0; color: #0f172a;">An ninh 24/7</h3>
        <p style="color: var(--text-light); line-height: 1.5;">Hệ thống camera giám sát và bảo vệ chuyên nghiệp, đảm bảo
            an toàn tuyệt đối cho cuộc sống của bạn.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">
            <i class="fa-solid fa-couch"></i>
        </div>
        <h3 style="margin-top: 0; color: #0f172a;">Nội thất đầy đủ</h3>
        <p style="color: var(--text-light); line-height: 1.5;">Phòng được trang bị đầy đủ nội thất cơ bản: giường, tủ
            quần áo, bàn học xịn xò. Chỉ cần xách vali vào ở.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">
            <i class="fa-solid fa-wifi"></i>
        </div>
        <h3 style="margin-top: 0; color: #0f172a;">Tiện ích đa dạng</h3>
        <p style="color: var(--text-light); line-height: 1.5;">Wifi tốc độ cao, khu vực giặt sấy, không gian sinh hoạt
            chung hiện đại và cực kì thân thiện.</p>
    </div>
</div>

<!-- About Section -->
<div id="about" class="about-section">
    <div class="about-image">
        <img src="https://images.unsplash.com/photo-1554995207-c18c203602cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
            alt="Giới thiệu KTX" style="height: 400px; object-fit: cover;">
    </div>
    <div class="about-content">
        <h2 style="font-size: 2.25rem; color: #0f172a; margin-top: 0; margin-bottom: 1.5rem;">Tại sao nên chọn Hệ thống
            KTX PT Manager?</h2>
        <p style="font-size: 1.1rem; color: #475569; line-height: 1.6; margin-bottom: 1.5rem;">
            Chúng tôi hiểu rằng môi trường sống đóng vai trò quan trọng trong sự phát triển học tập và làm việc của bạn.
            Do đó, PT Manager quản lý KTX không chỉ tập trung vào việc cho thuê phòng, mà còn kiến tạo một cộng đồng
            sinh sống văn minh.
        </p>

        <div
            style="background: white; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-left: 4px solid var(--primary-color);">
            <div
                style="font-weight: bold; color: #0f172a; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-location-dot" style="color: var(--primary-color);"></i> Địa chỉ khu vực phòng trọ:
            </div>
            <div style="color: #475569; line-height: 1.5;">KTX Sinh viên PT Manager<br>Số 123 Đường Nguyễn Văn Cừ,
                Phường 4, Quận 5, TP. Hồ Chí Minh</div>
        </div>
        <ul style="list-style: none; padding: 0; color: #334155; display: flex; flex-direction: column; gap: 1rem;">
            <li style="display: flex; gap: 1rem; align-items: flex-start;">
                <i class="fa-solid fa-circle-check"
                    style="color: var(--primary-color); margin-top: 0.25rem; font-size: 1.25rem;"></i>
                <span>Quy trình đặt phòng và thanh toán hoàn toàn trực tuyến, minh bạch rõ ràng.</span>
            </li>
            <li style="display: flex; gap: 1rem; align-items: flex-start;">
                <i class="fa-solid fa-circle-check"
                    style="color: var(--primary-color); margin-top: 0.25rem; font-size: 1.25rem;"></i>
                <span>Hỗ trợ sự cố kỹ thuật siêu tốc 24/7 trực tiếp qua hệ thống.</span>
            </li>
            <li style="display: flex; gap: 1rem; align-items: flex-start;">
                <i class="fa-solid fa-circle-check"
                    style="color: var(--primary-color); margin-top: 0.25rem; font-size: 1.25rem;"></i>
                <span>Cộng đồng cư dân tri thức, sinh hoạt có nguyên tắc văn minh.</span>
            </li>
        </ul>
    </div>
</div>

<!-- Featured Rooms Section -->
<section class="featured-rooms" style="margin-top: 5rem; margin-bottom: 5rem;">
    <div class="section-heading">
        <h2>Khám phá phòng trống nổi bật</h2>
        <p>Những căn phòng mới được dọn dẹp và sẵn sàng đón cư dân</p>
    </div>

    <div class="room-grid">
        <?php if (!empty($featuredRooms)): ?>
            <?php foreach ($featuredRooms as $room): ?>
                <div class="room-card" style="border-radius: 1rem;">
                    <?php $img_src = !empty($room['hinh_anh']) ? '../' . htmlspecialchars($room['hinh_anh']) : 'https://placehold.co/400x250?text=Room+' . $room['ma_phong']; ?>
                    <div class="room-img"
                        style="background-image: url('<?= $img_src ?>'); position: relative; background-size: cover; background-position: center;">
                        <span
                            style="position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.9); padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: bold; color: var(--primary-color); font-size: 0.875rem; backdrop-filter: blur(4px);">
                            Mới
                        </span>
                    </div>
                    <div class="room-info">
                        <h3 class="room-title">Phòng <?= htmlspecialchars($room['ma_phong']) ?></h3>
                        <div class="room-details" style="margin-bottom: 0.5rem;">
                            <span style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.25rem;"><i
                                    class="fa-solid fa-ruler-combined"></i> <?= htmlspecialchars($room['dien_tich']) ?>
                                m²</span>
                            <?php if ($room['trang_thai'] == 'Trong'): ?>
                                <span
                                    style="background: #dcfce7; color: #166534; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: 500;"><i
                                        class="fa-solid fa-check-circle"></i> Trống</span>
                            <?php else: ?>
                                <span
                                    style="background: #fee2e2; color: #b91c1c; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: 500;"><i
                                        class="fa-solid fa-times-circle"></i> Đã thuê</span>
                            <?php endif; ?>
                        </div>
                        <p
                            style="color: #64748b; font-size: 0.9rem; margin-bottom: 1rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                            <?= !empty($room['mo_ta']) ? htmlspecialchars($room['mo_ta']) : 'Phòng được thiết kế hiện đại, thông thoáng, đón ánh sáng tự nhiên. Trang bị sẵn các tiện ích cần thiết.' ?>
                        </p>
                        <div class="room-price" style="font-size: 1.5rem; margin-bottom: 1.5rem;">
                            <?= number_format($room['gia_thue'], 0, ',', '.') ?> <span
                                style="font-size: 0.875rem; font-weight: normal; color: var(--text-light);">VNĐ/tháng</span>
                        </div>

                        <div style="margin-top: auto; display: flex; gap: 0.5rem;">
                            <?php if ($room['trang_thai'] == 'Trong'): ?>
                                <a href="?controller=cart&action=add&id=<?= $room['ma_phong'] ?>" class="btn btn-outline"
                                    style="flex: 1; text-align: center; padding: 0.75rem; border-radius: 0.5rem;"
                                    title="Thêm vào giỏ hàng">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline"
                                    style="flex: 1; text-align: center; padding: 0.75rem; border-radius: 0.5rem; opacity: 0.5; cursor: not-allowed;"
                                    title="Đã có người thuê" disabled>
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            <?php endif; ?>
                            <a href="?controller=room&action=detail&id=<?= $room['ma_phong'] ?>" class="btn btn-primary"
                                style="flex: 4; text-align: center; padding: 0.75rem; border-radius: 0.5rem;">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div
                style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: #f8fafc; border-radius: 1rem; border: 1px dashed #cbd5e1;">
                <i class="fa-solid fa-door-closed" style="font-size: 3rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                <p style="font-size: 1.25rem; color: var(--text-light); margin: 0;">Hiện tại chưa có phòng trống.</p>
            </div>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-top: 3rem;">
        <a href="?controller=room&action=list" class="btn btn-outline"
            style="padding: 1rem 3rem; font-size: 1.1rem; border-radius: 9999px;">
            Xem toàn bộ danh sách phòng
        </a>
    </div>
</section>