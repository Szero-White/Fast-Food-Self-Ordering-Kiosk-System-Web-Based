<?php
session_start();
include('config/config.php');
require_once __DIR__ . '/../config/site_asset_repository.php';

$adminLogoUrl = site_asset_url($mysqli, 'admin_logo');
$adminFaviconUrl = site_asset_url($mysqli, 'site_favicon');

if (isset($_POST['dangnhap'])) {
    $taikhoan = trim($_POST['username'] ?? '');
    $matkhau = $_POST['password'] ?? '';

    $stmt = $mysqli->prepare("SELECT id_admin, username, password FROM tbl_admin WHERE username = ? AND admin_status > 0 LIMIT 1");
    $stmt->bind_param('s', $taikhoan);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    $passwordOk = false;
    if ($admin) {
        $storedPassword = $admin['password'];
        $passwordOk = password_verify($matkhau, $storedPassword) || hash_equals($storedPassword, md5($matkhau));

        if ($passwordOk && strlen($storedPassword) === 32) {
            $newHash = password_hash($matkhau, PASSWORD_DEFAULT);
            $updateStmt = $mysqli->prepare("UPDATE tbl_admin SET password = ? WHERE id_admin = ?");
            $updateStmt->bind_param('si', $newHash, $admin['id_admin']);
            $updateStmt->execute();
        }
    }

    if ($passwordOk) {
        session_regenerate_id(true);
        $_SESSION['dangnhap'] = $admin['username'];
        $_SESSION['admin_id'] = (int)$admin['id_admin'];
        header("Location:index.php");
        exit;
    } else {
        header("Location:login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Admin - FastFood</title>
    <link rel="icon" href="<?php echo htmlspecialchars($adminFaviconUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="apple-touch-icon" href="<?php echo htmlspecialchars($adminFaviconUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css_admin/auth-login.css">
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="<?php echo htmlspecialchars($adminLogoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="FastFood">
                </div>
                <h2>Quản trị FastFood</h2>
                <p>Quản lý nhà hàng của bạn</p>
            </div>

            <form action="" method="POST">
                <div class="form-group">
                    <label class="form-label">Tên đăng nhập</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                    </div>
                </div>

                <button type="submit" name="dangnhap" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập
                </button>
            </form>

            <div class="forgot-password">
                <a href="forgot_password.php"><i class="fas fa-key me-1"></i>Quên mật khẩu?</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



