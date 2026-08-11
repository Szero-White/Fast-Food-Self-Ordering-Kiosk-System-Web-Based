<?php
$sql_gioithieu = "SELECT * FROM tbl_gioithieu WHERE id = 1";
$query_gioithieu = mysqli_query($mysqli, $sql_gioithieu);
$row_gioithieu = mysqli_fetch_array($query_gioithieu);

function about_safe_content(?string $content): string
{
    $html = strip_tags((string)$content, '<p><br><strong><b><em><i><ul><ol><li><a><h3><h4>');
    $html = preg_replace('/\s+on[a-z]+\s*=\s*(["\']).*?\1/iu', '', $html) ?? '';
    $html = preg_replace('/href\s*=\s*(["\'])\s*javascript:.*?\1/iu', 'href="#"', $html) ?? '';

    return $html;
}
?>

<div class="about-container">
    <div class="page-header-box">
        <h1>🍔 Giới Thiệu</h1>
        <p>Trải nghiệm ẩm thực nhanh chóng, tiện lợi và tuyệt vời nhất!</p>
    </div>

    <section class="about-section">
        <h2>📖 Về Chúng Tôi</h2>
        <?php if (!empty($row_gioithieu['noidung'])) { ?>
            <div class="about-content">
                <?php echo about_safe_content($row_gioithieu['noidung']); ?>
            </div>
        <?php } else { ?>
            <p>Nhà hàng FastFood là chuỗi thức ăn nhanh hàng đầu tại Thành phố Hồ Chí Minh. Chúng tôi tự hào mang đến cho khách hàng những món ăn ngon, chất lượng với giá cả hợp lý.</p>
            <p>Với hơn 10 năm kinh nghiệm trong ngành ẩm thực, chúng tôi đã phục vụ hàng triệu khách hàng và nhận được nhiều phản hồi tích cực. Cam kết của chúng tôi là luôn đặt chất lượng món ăn và sự hài lòng của khách hàng lên hàng đầu.</p>
        <?php } ?>

        <?php if (!empty($row_gioithieu['hinhanh'])) { ?>
            <img src="<?php echo htmlspecialchars(upload_url($row_gioithieu['hinhanh']), ENT_QUOTES, 'UTF-8'); ?>" alt="Hình ảnh giới thiệu FastFood" class="about-image">
        <?php } ?>
    </section>

    <section class="about-section">
        <h2>✨ Đặc Điểm Nổi Bật</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🍕</div>
                <h3>Thực Đơn Đa Dạng</h3>
                <p>Pizza, Mì Ý, Gà rán, Hamburger và nhiều món ăn vặt hấp dẫn</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Giao Hàng Nhanh</h3>
                <p>Giao hàng trong vòng 30-45 phút, đảm bảo món ăn nóng giòn</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3>Giá Cả Hợp Lý</h3>
                <p>Giá từ 25.000đ đến 225.000đ, phù hợp mọi đối tượng khách hàng</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌟</div>
                <h3>Khuyến Mãi Liên Tục</h3>
                <p>Mua 1 tặng 1 vào thứ 3, giảm 15% đơn hàng đầu tiên</p>
            </div>
        </div>
    </section>

    <section class="about-section">
        <h2>📊 Thống Kê</h2>
        <div class="stats-row">
            <div class="stat-item">
                <span class="stat-number">50+</span>
                <span class="stat-label">Món Ăn</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">10+</span>
                <span class="stat-label">Năm Kinh Nghiệm</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">1M+</span>
                <span class="stat-label">Khách Hàng</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">4.8</span>
                <span class="stat-label">Đánh Giá</span>
            </div>
        </div>
    </section>

    <section class="about-section">
        <h2>📞 Liên Hệ</h2>
        <p>📍 Địa chỉ: Quận 7, Thành phố Hồ Chí Minh</p>
        <p>📞 Hotline: 1900 6099</p>
        <p>📧 Email: congtoan2k4@gmail.com</p>
        <p>🕐 Giờ mở cửa: 9:00 - 22:00 (Hằng ngày)</p>
    </section>
</div>
