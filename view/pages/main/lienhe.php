<?php
if (isset($_POST['gui_lienhe'])) {
    $ten        = trim((string)($_POST['ten']         ?? ''));
    $email      = trim((string)($_POST['email']       ?? ''));
    $sodienthoai= trim((string)($_POST['sodienthoai'] ?? ''));
    $loai       = trim((string)($_POST['loai']        ?? ''));
    $noidung    = trim((string)($_POST['noidung']     ?? ''));
    $trangthai  = 'chua_xem';

    $stmt = mysqli_prepare(
        $mysqli,
        'INSERT INTO tbl_lienhe (thongtinlienhe, hinhanh, ngaygui, trangthai, ten, email, sodienthoai, loai, noidung)
         VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?)'
    );
    $thongtinlienhe = "$ten | $email | $sodienthoai | $loai";
    $empty = '';
    mysqli_stmt_bind_param($stmt, 'ssssssss', $thongtinlienhe, $empty, $trangthai, $ten, $email, $sodienthoai, $loai, $noidung);

    if (mysqli_stmt_execute($stmt)) {
        $thanhcong = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.';
    } else {
        $loi = 'Có lỗi xảy ra, vui lòng thử lại sau.';
    }

    mysqli_stmt_close($stmt);
}
?>

<div class="contact-page">

    <!-- Hero -->
    <div class="contact-hero">
        <div class="contact-hero-icon"><i class="fas fa-headset"></i></div>
        <div>
            <h1>Liên hệ với chúng tôi</h1>
            <p>Có câu hỏi hoặc gặp vấn đề? Chúng tôi luôn sẵn sàng hỗ trợ bạn.</p>
        </div>
    </div>

    <!-- Stats bar -->
    <div class="contact-stats">
        <div class="contact-stat">
            <span class="contact-stat-icon" style="background:#fff3e0;color:#f57c00"><i class="fas fa-clock"></i></span>
            <div>
                <strong>Phản hồi nhanh</strong>
                <span>Trong vòng 24h</span>
            </div>
        </div>
        <div class="contact-stat">
            <span class="contact-stat-icon" style="background:#e8f5e9;color:#2e7d32"><i class="fas fa-shield-halved"></i></span>
            <div>
                <strong>Bảo mật thông tin</strong>
                <span>100% an toàn</span>
            </div>
        </div>
        <div class="contact-stat">
            <span class="contact-stat-icon" style="background:#e3f2fd;color:#1565c0"><i class="fas fa-star"></i></span>
            <div>
                <strong>Hỗ trợ chuyên nghiệp</strong>
                <span>Đội ngũ tận tâm</span>
            </div>
        </div>
    </div>

    <!-- Main grid -->
    <div class="contact-body">

        <!-- Sidebar -->
        <aside class="contact-sidebar">
            <h3><i class="fas fa-address-card me-2"></i>Thông tin liên hệ</h3>

            <div class="contact-info-item">
                <span class="ci-icon" style="background:#fff3e0;color:#f57c00">
                    <i class="fas fa-location-dot"></i>
                </span>
                <div>
                    <h4>Địa chỉ</h4>
                    <p>Quận 7, TP. Hồ Chí Minh</p>
                </div>
            </div>

            <div class="contact-info-item">
                <span class="ci-icon" style="background:#e8f5e9;color:#2e7d32">
                    <i class="fas fa-phone"></i>
                </span>
                <div>
                    <h4>Hotline</h4>
                    <p>1900 6099</p>
                </div>
            </div>

            <div class="contact-info-item">
                <span class="ci-icon" style="background:#e3f2fd;color:#1565c0">
                    <i class="fas fa-envelope"></i>
                </span>
                <div>
                    <h4>Email</h4>
                    <p>congtoan2k4@gmail.com</p>
                </div>
            </div>

            <div class="contact-info-item">
                <span class="ci-icon" style="background:#f3e5f5;color:#7b1fa2">
                    <i class="fas fa-clock"></i>
                </span>
                <div>
                    <h4>Giờ làm việc</h4>
                    <p>9:00 – 22:00 hằng ngày</p>
                </div>
            </div>

            <div class="contact-social">
                <p class="contact-social-label">Kết nối với chúng tôi</p>
                <div class="contact-social-links">
                    <a href="#" class="cs-btn cs-facebook" title="Facebook" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="cs-btn cs-instagram" title="Instagram" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="cs-btn cs-youtube" title="YouTube" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Form -->
        <section class="contact-form-panel">
            <h3><i class="fas fa-paper-plane me-2" style="color:#667eea"></i>Gửi tin nhắn</h3>

            <?php if (!empty($thanhcong)) { ?>
                <div class="contact-alert contact-alert-success">
                    <i class="fas fa-circle-check"></i>
                    <span><?php echo htmlspecialchars($thanhcong, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            <?php } ?>
            <?php if (!empty($loi)) { ?>
                <div class="contact-alert contact-alert-danger">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span><?php echo htmlspecialchars($loi, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            <?php } ?>

            <form method="POST" class="contact-form">
                <div class="cf-row">
                    <div class="cf-group">
                        <label for="ten">Họ và tên <span class="cf-required">*</span></label>
                        <div class="cf-input-wrap">
                            <i class="fas fa-user"></i>
                            <input type="text" id="ten" name="ten" placeholder="Nhập họ và tên" required>
                        </div>
                    </div>
                    <div class="cf-group">
                        <label for="email">Email <span class="cf-required">*</span></label>
                        <div class="cf-input-wrap">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email" required>
                        </div>
                    </div>
                </div>

                <div class="cf-row">
                    <div class="cf-group">
                        <label for="sodienthoai">Số điện thoại</label>
                        <div class="cf-input-wrap">
                            <i class="fas fa-phone"></i>
                            <input type="tel" id="sodienthoai" name="sodienthoai" placeholder="Nhập số điện thoại">
                        </div>
                    </div>
                    <div class="cf-group">
                        <label for="loai">Loại liên hệ <span class="cf-required">*</span></label>
                        <div class="cf-input-wrap cf-select-wrap">
                            <i class="fas fa-tag"></i>
                            <select id="loai" name="loai" required>
                                <option value="">-- Chọn loại --</option>
                                <option value="gap_loi">Gặp lỗi website</option>
                                <option value="don_hang">Vấn đề đơn hàng</option>
                                <option value="gop_y">Góp ý cải tiến</option>
                                <option value="hop_tac">Hợp tác kinh doanh</option>
                                <option value="khac">Khác</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="cf-group">
                    <label for="noidung">Nội dung <span class="cf-required">*</span></label>
                    <textarea id="noidung" name="noidung" rows="5"
                              placeholder="Mô tả chi tiết vấn đề hoặc câu hỏi của bạn..." required></textarea>
                </div>

                <button type="submit" name="gui_lienhe" class="cf-submit">
                    <i class="fas fa-paper-plane"></i>
                    Gửi liên hệ
                </button>
            </form>
        </section>

    </div>
</div>
