<!-- Glassmorphism Header CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    .glass-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 0;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
        border-bottom: 1px solid rgba(255,255,255,0.2);
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
    }

    .glass-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 12px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .glass-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        background: rgba(255,255,255,0.15);
        padding: 6px 16px 6px 6px;
        border-radius: 50px;
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s ease;
    }

    .glass-logo:hover {
        background: rgba(255,255,255,0.25);
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .glass-logo img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.5);
    }

    .glass-logo span {
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        letter-spacing: 0.5px;
    }

    .glass-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.1);
        padding: 6px;
        border-radius: 50px;
        border: 1px solid rgba(255,255,255,0.15);
    }

    .glass-nav a {
        color: rgba(255,255,255,0.9);
        text-decoration: none;
        padding: 10px 50px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .glass-nav a:hover,
    .glass-nav a.active {
        color: #667eea;
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .glass-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .glass-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .glass-btn-cart {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
        color: #764ba2;
    }

    .glass-btn-cart:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 25px rgba(255, 154, 158, 0.5);
        color: #764ba2;
    }

    .glass-btn-call {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: #fff;
    }

    .glass-btn-call:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 25px rgba(250, 112, 154, 0.5);
        color: #fff;
    }

    .glass-badge {
        background: #764ba2;
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        min-width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 4px;
    }

    @media (max-width: 992px) {
        .glass-container {
            padding: 10px 15px;
        }
        .glass-nav a span,
        .glass-btn span {
            display: none;
        }
        .glass-btn {
            padding: 12px;
        }
        .glass-logo span {
            display: none;
        }
        .glass-logo {
            padding: 6px;
        }
    }
</style>

<?php
$sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
$query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);

// Get current page
$current_page = $_GET['quanly'] ?? 'index';
?>

<!-- Glassmorphism Header -->
<header class="glass-header">
    <div class="glass-container">
        <a href="index.php?quanly=index" class="glass-logo">
            <img src="images/1200_50/logo.jpg" alt="FastFood Logo">
            <span>FastFood</span>
        </a>

        <nav class="glass-nav">
            <a href="index.php?quanly=index" class="<?php echo ($current_page == 'index') ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Trang Chủ</span>
            </a>
            <a href="index.php?quanly=gioithieu" class="<?php echo ($current_page == 'gioithieu') ? 'active' : ''; ?>">
                <i class="fas fa-info-circle"></i>
                <span>Giới Thiệu</span>
            </a>
            <a href="index.php?quanly=danhmucbaiviet" class="<?php echo ($current_page == 'danhmucbaiviet') ? 'active' : ''; ?>">
                <i class="fas fa-gift"></i>
                <span>Khuyến Mãi</span>
            </a>
            <a href="index.php?quanly=lienhe" class="<?php echo ($current_page == 'lienhe') ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i>
                <span>Liên Hệ</span>
            </a>
        </nav>

        <div class="glass-actions">
            <a href="index.php?quanly=giohang" class="glass-btn glass-btn-cart">
                <i class="fas fa-shopping-cart"></i>
                <span>Giỏ Hàng</span>
                <span class="glass-badge">0</span>
            </a>
            <a href="index.php?quanly=goinhanvien" class="glass-btn glass-btn-call">
                <i class="fas fa-bell"></i>
                <span>Gọi NV</span>
            </a>
        </div>
    </div>
</header>

