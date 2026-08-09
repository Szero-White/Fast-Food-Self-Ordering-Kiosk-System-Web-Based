<?php require_once __DIR__ . '/../../config/site_asset_repository.php'; ?>

<footer class="glass-footer">
    <div class="glass-footer-inner">
        <div class="glass-footer-brand">
            <img src="<?php echo htmlspecialchars(site_asset_url($mysqli, 'site_logo'), ENT_QUOTES, 'UTF-8'); ?>" alt="FastFood Logo">
            <span>FastFood</span>
        </div>

        <div class="glass-footer-info">
            <div class="glass-info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>Quận 7, TP.HCM</span>
            </div>
            <div class="glass-info-item">
                <i class="fas fa-envelope"></i>
                <a href="mailto:congtoan2k4@gmail.com">congtoan2k4@gmail.com</a>
            </div>
            <div class="glass-info-item" title="Hotline: 1900 6099">
                <i class="fas fa-phone"></i>
                <span>1900 6099</span>
            </div>
        </div>

        <div class="glass-footer-social">
            <a href="#" class="glass-social-btn" title="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="glass-social-btn" title="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="glass-social-btn" title="YouTube">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
    </div>

    <div class="glass-footer-bottom">
        © 2026 <strong>FastFood</strong> - Đặt món nhanh chóng | Tác giả Nguyễn Công Toàn ❤️
    </div>
</footer>
