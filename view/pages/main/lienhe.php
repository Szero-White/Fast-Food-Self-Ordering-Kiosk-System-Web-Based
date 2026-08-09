<?php
if (isset($_POST['gui_lienhe'])) {
    $ten = trim($_POST['ten'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sodienthoai = trim($_POST['sodienthoai'] ?? '');
    $loai = trim($_POST['loai'] ?? '');
    $noidung = trim($_POST['noidung'] ?? '');
    $trangthai = 'chua_xem';
    $thongtinlienhe = '';
    $hinhanh = '';

    $stmt = mysqli_prepare(
        $mysqli,
        'INSERT INTO tbl_lienhe (thongtinlienhe, hinhanh, ngaygui, trangthai, ten, email, sodienthoai, loai, noidung)
         VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?)'
    );
    mysqli_stmt_bind_param($stmt, 'ssssssss', $thongtinlienhe, $hinhanh, $trangthai, $ten, $email, $sodienthoai, $loai, $noidung);

    if (mysqli_stmt_execute($stmt)) {
        $thanhcong = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.';
    } else {
        $loi = 'Có lỗi xảy ra, vui lòng thử lại sau.';
    }

    mysqli_stmt_close($stmt);
}
?>

<div class="contact-container">
    <div class="page-header-box">
        <h1>📞 Liên Hệ Với Chúng Tôi</h1>
        <p>Có câu hỏi hoặc gặp vấn đề? Chúng tôi sẵn sàng hỗ trợ bạn!</p>
    </div>

    <div class="contact-grid">
        <aside class="contact-info">
            <h3>Thông tin liên hệ</h3>
            <div class="info-item">
                <span class="info-icon">📍</span>
                <div class="info-content">
                    <h4>Địa chỉ</h4>
                    <p>Quận 7, TP. Hồ Chí Minh</p>
                </div>
            </div>
            <div class="info-item">
                <span class="info-icon">📞</span>
                <div class="info-content">
                    <h4>Hotline</h4>
                    <p>1900 6099</p>
                </div>
            </div>
            <div class="info-item">
                <span class="info-icon">✉️</span>
                <div class="info-content">
                    <h4>Email</h4>
                    <p>congtoan2k4@gmail.com</p>
                </div>
            </div>
            <div class="info-item">
                <span class="info-icon">🕐</span>
                <div class="info-content">
                    <h4>Giờ làm việc</h4>
                    <p>9:00 - 22:00 (Hằng ngày)</p>
                </div>
            </div>

            <div class="social-links">
                <h4>Kết nối với chúng tôi</h4>
                <a href="#" title="Facebook">📘</a>
                <a href="#" title="Instagram">📸</a>
                <a href="#" title="YouTube">▶️</a>
            </div>
        </aside>

        <section class="contact-form">
            <?php if (!empty($thanhcong)) { ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo htmlspecialchars($thanhcong, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            <?php } ?>
            <?php if (!empty($loi)) { ?>
                <div class="alert alert-danger">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span><?php echo htmlspecialchars($loi, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            <?php } ?>

            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="ten">Họ và tên *</label>
                        <input type="text" id="ten" name="ten" placeholder="Nhập họ tên" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" placeholder="Nhập email" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="sodienthoai">Số điện thoại</label>
                        <input type="tel" id="sodienthoai" name="sodienthoai" placeholder="Nhập số điện thoại">
                    </div>
                    <div class="form-group">
                        <label for="loai">Loại liên hệ *</label>
                        <select id="loai" name="loai" required>
                            <option value="">-- Chọn loại --</option>
                            <option value="gap_loi">⚠️ Gặp lỗi website</option>
                            <option value="don_hang">📦 Vấn đề đơn hàng</option>
                            <option value="gop_y">💡 Góp ý cải tiến</option>
                            <option value="hop_tac">🤝 Hợp tác kinh doanh</option>
                            <option value="khac">📝 Khác</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="noidung">Nội dung *</label>
                    <textarea id="noidung" name="noidung" placeholder="Mô tả chi tiết vấn đề của bạn..." required></textarea>
                </div>

                <button type="submit" name="gui_lienhe" class="btn-submit">
                    📤 Gửi liên hệ
                </button>
            </form>
        </section>
    </div>
</div>
