<?php
$thanhcong = (string)($_SESSION['contact_form_success'] ?? '');
$loi = (string)($_SESSION['contact_form_error'] ?? '');
$contactOld = $_SESSION['contact_form_old'] ?? [];
unset($_SESSION['contact_form_success'], $_SESSION['contact_form_error'], $_SESSION['contact_form_old']);

if (empty($_SESSION['contact_form_token'])) {
    contact_generate_form_token();
}

$contactToken = (string)$_SESSION['contact_form_token'];

function contact_old_value(array $old, string $key): string
{
    return htmlspecialchars((string)($old[$key] ?? ''), ENT_QUOTES, 'UTF-8');
}

function contact_selected(array $old, string $key, string $value): string
{
    return (string)($old[$key] ?? '') === $value ? ' selected' : '';
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

            <form method="POST" class="contact-form" novalidate>
                <input type="hidden" name="gui_lienhe" value="1">
                <input type="hidden" name="contact_form_token" value="<?php echo htmlspecialchars($contactToken, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="cf-row">
                    <div class="cf-group">
                        <label for="ten">Họ và tên <span class="cf-required">*</span></label>
                        <div class="cf-input-wrap">
                            <i class="fas fa-user"></i>
                            <input type="text" id="ten" name="ten" placeholder="Nhập họ và tên" value="<?php echo contact_old_value($contactOld, 'ten'); ?>" required>
                        </div>
                    </div>
                    <div class="cf-group">
                        <label for="email">Email <span class="cf-required">*</span></label>
                        <div class="cf-input-wrap">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email" value="<?php echo contact_old_value($contactOld, 'email'); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="cf-row">
                    <div class="cf-group">
                        <label for="sodienthoai">Số điện thoại</label>
                        <div class="cf-input-wrap">
                            <i class="fas fa-phone"></i>
                            <input type="tel" id="sodienthoai" name="sodienthoai" placeholder="Nhập số điện thoại" value="<?php echo contact_old_value($contactOld, 'sodienthoai'); ?>">
                        </div>
                    </div>
                    <div class="cf-group">
                        <label for="loai">Loại liên hệ <span class="cf-required">*</span></label>
                        <div class="cf-input-wrap cf-select-wrap">
                            <i class="fas fa-tag"></i>
                            <select id="loai" name="loai" required>
                                <option value="">-- Chọn loại --</option>
                                <option value="gap_loi"<?php echo contact_selected($contactOld, 'loai', 'gap_loi'); ?>>Gặp lỗi website</option>
                                <option value="don_hang"<?php echo contact_selected($contactOld, 'loai', 'don_hang'); ?>>Vấn đề đơn hàng</option>
                                <option value="gop_y"<?php echo contact_selected($contactOld, 'loai', 'gop_y'); ?>>Góp ý cải tiến</option>
                                <option value="hop_tac"<?php echo contact_selected($contactOld, 'loai', 'hop_tac'); ?>>Hợp tác kinh doanh</option>
                                <option value="khac"<?php echo contact_selected($contactOld, 'loai', 'khac'); ?>>Khác</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="cf-group">
                    <label for="noidung">Nội dung <span class="cf-required">*</span></label>
                    <textarea id="noidung" name="noidung" rows="5"
                              placeholder="Mô tả chi tiết vấn đề hoặc câu hỏi của bạn..." required><?php echo contact_old_value($contactOld, 'noidung'); ?></textarea>
                </div>

                <button type="submit" class="cf-submit">
                    <i class="fas fa-paper-plane"></i>
                    Gửi liên hệ
                </button>
            </form>
        </section>

    </div>
</div>
<script>
document.querySelectorAll('.contact-form').forEach(function (form) {
    form.addEventListener('submit', function () {
        const button = form.querySelector('.cf-submit');
        if (!button || button.disabled) {
            return;
        }

        button.disabled = true;
        button.classList.add('is-submitting');
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
    });
});
</script>
