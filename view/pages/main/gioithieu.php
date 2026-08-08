<?php
// Lấy thông tin từ database
$sql_gioithieu = "SELECT * FROM tbl_gioithieu WHERE id = 1";
$query_gioithieu = mysqli_query($mysqli, $sql_gioithieu);
$row_gioithieu = mysqli_fetch_array($query_gioithieu);
?>
<style>
    .about-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .page-header-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        margin: 0 auto 40px auto;
        max-width: 84rem;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    }
    .page-header-box h1 {
        font-size: 2.5rem;
        margin-bottom: 15px;
        font-weight: bold;
    }
    .page-header-box p {
        font-size: 1.2rem;
        opacity: 0.9;
    }
    
    .about-section {
        background: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    }
    
    .about-section h2 {
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .about-section p {
        color: #7f8c8d;
        line-height: 1.8;
        margin-bottom: 15px;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .feature-card {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        transition: transform 0.3s;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
    }
    
    .feature-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }
    
    .feature-card h3 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .feature-card p {
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-top: 30px;
    }
    
    .stat-item {
        text-align: center;
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        display: block;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
</style>

<div class="about-container">
    <div class="page-header-box">
        <h1>🍔 Giới Thiệu</h1>
        <p>Trải nghiệm ẩm thực nhanh chóng, tiện lợi và tuyệt vời nhất!</p>
    </div>
    
    <div class="about-section">
        <h2>📖 Về Chúng Tôi</h2>
        <?php if(isset($row_gioithieu['noidung']) && !empty($row_gioithieu['noidung'])) { ?>
            <div class="about-content">
                <?php echo $row_gioithieu['noidung']; ?>
            </div>
        <?php } else { ?>
            <p>FastFood Restaurant là chuỗi nhà hàng thức ăn nhanh hàng đầu tại Thành phố Hồ Chí Minh. Chúng tôi tự hào mang đến cho khách hàng những món ăn ngon, chất lượng với giá cả hợp lý.</p>
            <p>Với hơn 10 năm kinh nghiệm trong ngành ẩm thực, chúng tôi đã phục vụ hàng triệu khách hàng và nhận được nhiều phản hồi tích cực. Cam kết của chúng tôi là luôn đặt chất lượng món ăn và sự hài lòng của khách hàng lên hàng đầu.</p>
        <?php } ?>
        <?php if(isset($row_gioithieu['hinhanh']) && !empty($row_gioithieu['hinhanh'])) { ?>
            <img src="uploads/<?php echo $row_gioithieu['hinhanh']; ?>" style="max-width: 100%; margin-top: 20px; border-radius: 10px;">
        <?php } ?>
    </div>
    
    <div class="about-section">
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
    </div>
    
    <div class="about-section">
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
    </div>
    
    <div class="about-section">
        <h2>📞 Liên Hệ</h2>
        <p>📍 Địa chỉ: Quận 7, Thành phố Hồ Chí Minh</p>
        <p>📞 Hotline: 1900 6099</p>
        <p>📧 Email: congtoan2k4@gmail.com</p>
        <p>🕐 Giờ mở cửa: 9:00 - 22:00 (Hàng ngày)</p>
    </div>
</div>

