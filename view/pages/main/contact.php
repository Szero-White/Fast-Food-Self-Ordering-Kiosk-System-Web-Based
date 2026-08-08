<?php
// Xu ly gui lien he
if(isset($_POST['gui_lienhe'])) {
    $ten = mysqli_real_escape_string($mysqli, $_POST['ten']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $sodienthoai = mysqli_real_escape_string($mysqli, $_POST['sodienthoai']);
    $loai = mysqli_real_escape_string($mysqli, $_POST['loai']);
    $noidung = mysqli_real_escape_string($mysqli, $_POST['noidung']);
    
    $sql = "INSERT INTO tbl_lienhe (ten, email, sodienthoai, loai, noidung, ngaygui, trangthai) 
            VALUES ('$ten', '$email', '$sodienthoai', '$loai', '$noidung', NOW(), 'chua_xem')";
    
    if(mysqli_query($mysqli, $sql)) {
        $thanhcong = "Cam on ban da lien he! Chung toi se phan hoi trong thoi gian som nhat.";
    } else {
        $loi = "Co loi xay ra, vui long thu lai sau.";
    }
}
?>

<style>
    .contact-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .contact-header-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 50px 30px;
        text-align: center;
        margin-bottom: 50px;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    }
    .contact-header-box h1 {
        font-size: 2.5rem;
        margin-bottom: 15px;
        font-weight: 700;
    }
    .contact-header-box p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 40px;
    }
    
    /* Contact Info */
    .contact-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 20px;
        color: white;
    }
    .contact-info h3 {
        margin-bottom: 25px;
        font-size: 1.3rem;
    }
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
    }
    .info-icon {
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .info-content h4 {
        font-size: 1rem;
        margin-bottom: 5px;
    }
    .info-content p {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    
    .social-links {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.3);
    }
    .social-links h4 {
        margin-bottom: 15px;
    }
    .social-links a {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
        margin-right: 10px;
        font-size: 1.2rem;
        transition: all 0.3s;
    }
    .social-links a:hover {
        background: white;
        transform: translateY(-3px);
    }
    
    /* Contact Form */
    .contact-form {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #667eea;
        outline: none;
    }
    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .btn-submit {
        width: 100%;
        padding: 18px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    
    /* Alert Messages */
    .alert {
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="contact-container">
    <div class="contact-header-box">
        <h1>📞 Liên Hệ Với Chúng Tôi</h1>
        <p>Có câu hỏi hoặc gặp vấn đề? Chúng tôi sẵn sàng hỗ trợ bạn!</p>
    </div>
    
    <div class="contact-grid">
        <div class="contact-info">
            <h3>Thong Tin Lien He</h3>
            
            <div class="info-item">
                <div class="info-icon">📍</div>
                <div class="info-content">
                    <h4>Dia Chi</h4>
                    <p>Quan 7, TP. Ho Chi Minh</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">📞</div>
                <div class="info-content">
                    <h4>Hotline</h4>
                    <p>1900 6099</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">✉️</div>
                <div class="info-content">
                    <h4>Email</h4>
                    <p>congtoan2k4@gmail.com</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">🕐</div>
                <div class="info-content">
                    <h4>Gio Lam Viec</h4>
                    <p>9:00 - 22:00 (Hang ngay)</p>
                </div>
            </div>
            
            <div class="social-links">
                <h4>Ket Noi Voi Chung Toi</h4>
                <a href="#">📘</a>
                <a href="#">📸</a>
                <a href="#">🐦</a>
                <a href="#">▶️</a>
            </div>
        </div>
        
        <div class="contact-form">
            <?php if(isset($thanhcong)) { ?>
                <div class="alert alert-success">
                    ✅ <?php echo $thanhcong; ?>
                </div>
            <?php } ?>
            
            <?php if(isset($loi)) { ?>
                <div class="alert alert-danger">
                    ❌ <?php echo $loi; ?>
                </div>
            <?php } ?>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="ten">Ho va Ten *</label>
                        <input type="text" id="ten" name="ten" required placeholder="Nhap ho ten">
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required placeholder="Nhap email">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="sodienthoai">So Dien Thoai</label>
                        <input type="tel" id="sodienthoai" name="sodienthoai" placeholder="Nhap so dien thoai">
                    </div>
                    <div class="form-group">
                        <label for="loai">Loai Lien He *</label>
                        <select id="loai" name="loai" required>
                            <option value="">-- Chon loai --</option>
                            <option value="gap_loi">⚠️ Gap loi website</option>
                            <option value="don_hang">📦 Van de don hang</option>
                            <option value="gop_y">💡 Gop y cai tien</option>
                            <option value="hop_tac">🤝 Hop tac kinh doanh</option>
                            <option value="khac">📝 Khac</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="noidung">Noi Dung *</label>
                    <textarea id="noidung" name="noidung" required placeholder="Mo ta chi tiet van de cua ban..."></textarea>
                </div>
                
                <button type="submit" name="gui_lienhe" class="btn-submit">
                    📤 Gui Lien He
                </button>
            </form>
        </div>
    </div>
</div>

