<?php
require_once __DIR__ . '/includes/admin_security.php';
include('config/config.php');
require_once __DIR__ . '/../config/site_asset_repository.php';

$adminLogoUrl = site_asset_url($mysqli, 'admin_logo');
$adminFaviconUrl = site_asset_url($mysqli, 'site_favicon');

if (isset($_POST['dangnhap'])) {
    $taikhoan = trim((string)($_POST['username'] ?? ''));
    $matkhau = (string)($_POST['password'] ?? '');

    $stmt = $mysqli->prepare('SELECT id_admin, username, password FROM tbl_admin WHERE username = ? AND admin_status > 0 LIMIT 1');
    $stmt->bind_param('s', $taikhoan);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $passwordOk = false;
    if ($admin) {
        $storedPassword = (string)$admin['password'];
        $passwordOk = password_verify($matkhau, $storedPassword)
            || (strlen($storedPassword) === 32 && hash_equals($storedPassword, md5($matkhau)));

        if ($passwordOk && strlen($storedPassword) === 32) {
            $newHash = password_hash($matkhau, PASSWORD_DEFAULT);
            $updateStmt = $mysqli->prepare('UPDATE tbl_admin SET password = ? WHERE id_admin = ?');
            $updateStmt->bind_param('si', $newHash, $admin['id_admin']);
            $updateStmt->execute();
            $updateStmt->close();
        }
    }

    if ($passwordOk) {
        session_regenerate_id(true);
        $_SESSION['dangnhap'] = $admin['username'];
        $_SESSION['admin_id'] = (int)$admin['id_admin'];
        header('Location:index.php');
        exit;
    }

    header('Location:login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - FastFood</title>
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
                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                </button>
            </form>

            <div class="forgot-password">
                <a href="forgot_password.php"><i class="fas fa-key me-1"></i>Quên mật khẩu?</a>
            </div>
        </div>

        <div class="demo-panel">
            <button type="button" class="demo-toggle" onclick="toggleDemo(this)" aria-expanded="false">
                <i class="fas fa-vial demo-toggle-icon"></i>Tài khoản demo
                <i class="fas fa-chevron-down demo-chevron"></i>
            </button>
            <div class="demo-body" id="demo-body" hidden>
                <div class="demo-row">
                    <i class="fas fa-user demo-row-icon"></i>
                    <div class="demo-field">
                        <span class="demo-field-label">Tên đăng nhập</span>
                        <span class="demo-field-val" id="demo-user">toan</span>
                    </div>
                    <button type="button" class="demo-copy" onclick="copyDemo('demo-user')" title="Sao chép">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <div class="demo-divider"></div>
                <div class="demo-row">
                    <i class="fas fa-lock demo-row-icon"></i>
                    <div class="demo-field">
                        <span class="demo-field-label">Mật khẩu</span>
                        <span class="demo-field-val" id="demo-pass">123456</span>
                    </div>
                    <button type="button" class="demo-copy" onclick="copyDemo('demo-pass')" title="Sao chép">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <button type="button" class="btn-demo-fill" onclick="fillDemo()">
                    <i class="fas fa-bolt me-2"></i>Điền tự động vào form
                </button>
            </div>
        </div>
    </div>

    <script>
    function toggleDemo(btn) {
        const body = document.getElementById('demo-body');
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', !expanded);
        body.hidden = expanded;
        btn.querySelector('.demo-chevron').style.transform = expanded ? '' : 'rotate(180deg)';
    }
    function fillDemo() {
        document.querySelector('input[name="username"]').value = document.getElementById('demo-user').textContent;
        document.querySelector('input[name="password"]').value = document.getElementById('demo-pass').textContent;
        document.querySelector('input[name="username"]').focus();
    }
    function copyDemo(id) {
        const text = document.getElementById(id).textContent;
        navigator.clipboard.writeText(text).then(function () {
            const icon = document.querySelector('[onclick="copyDemo(\'' + id + '\')"] i');
            icon.className = 'fas fa-check';
            setTimeout(function () { icon.className = 'fas fa-copy'; }, 1500);
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
