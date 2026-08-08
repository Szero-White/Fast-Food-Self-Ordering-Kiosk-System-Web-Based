<?php
session_start();
if (!isset($_SESSION['dangnhap'])) {
    header('Location:login.php');
}

if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
    unset($_SESSION['dangnhap']);
    header('Location:login.php');
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastFood Admin - Quản Lý Nhà Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css_admin/admin_style.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-hamburger"></i>
                <span>FastFood</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <div class="sidebar-user">
            <div class="user-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="user-info">
                <h6><?php echo $_SESSION['dangnhap']; ?></h6>
                <span>Quản trị viên</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <?php include("modules/menu.php"); ?>
        </nav>

        <div class="sidebar-footer">
            <a href="index.php?dangxuat=1" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="breadcrumb">
                    <h4 id="pageTitle">Dashboard</h4>
                </div>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <button class="action-btn">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </button>
                    <button class="action-btn">
                        <i class="fas fa-envelope"></i>
                        <span class="badge">5</span>
                    </button>
                </div>
                <div class="user-dropdown">
                    <button class="user-btn">
                        <span><?php echo $_SESSION['dangnhap']; ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-wrapper">
            <?php
            include("config/config.php");
            include("modules/main.php");
            ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.24.0-lts/standard/ckeditor.js"></script>
    <script src="css_admin/admin_script.js"></script>
</body>
</html>