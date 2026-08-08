<!-- Glassmorphism Footer CSS -->
<style>
    .glass-footer {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 0;
        margin-top: 30px;
        position: relative;
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
    }

    .glass-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    }

    .glass-footer-inner {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .glass-footer-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,0.15);
        padding: 8px 16px 8px 8px;
        border-radius: 50px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .glass-footer-brand img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.5);
    }

    .glass-footer-brand span {
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .glass-footer-info {
        display: flex;
        align-items: center;
        gap: 25px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .glass-info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,0.9);
        font-size: 0.9rem;
        background: rgba(255,255,255,0.1);
        padding: 8px 16px;
        border-radius: 25px;
        border: 1px solid rgba(255,255,255,0.15);
    }

    .glass-info-item i {
        font-size: 1rem;
    }

    .glass-info-item a {
        color: #ffeaa7;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .glass-info-item a:hover {
        color: #fab1a0;
        text-decoration: underline;
    }

    .glass-footer-social {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .glass-social-btn {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .glass-social-btn:hover {
        background: white;
        color: #764ba2;
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .glass-footer-bottom {
        background: rgba(0,0,0,0.2);
        padding: 12px;
        text-align: center;
        color: rgba(255,255,255,0.7);
        font-size: 0.85rem;
    }

    .glass-footer-bottom strong {
        color: #ffeaa7;
    }

    @media (max-width: 768px) {
        .glass-footer-inner {
            flex-direction: column;
            padding: 20px;
            text-align: center;
        }
        
        .glass-footer-info {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<!-- Glassmorphism Footer -->
<footer class="glass-footer">
    <div class="glass-footer-inner">
        <div class="glass-footer-brand">
            <img src="images/1200_50/logo.jpg" alt="FastFood Logo">
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
        © 2026 <strong>FastFood</strong> - Đặt Món Nhanh Chóng |Author Nguyen Cong Toan ❤️
    </div>
</footer>
