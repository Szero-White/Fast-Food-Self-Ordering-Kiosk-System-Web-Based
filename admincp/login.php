<?php
require_once __DIR__ . '/includes/admin_security.php';
include('config/config.php');
require_once __DIR__ . '/../config/site_asset_repository.php';

$adminLogoUrl = site_asset_url($mysqli, 'admin_logo');
$adminFaviconUrl = site_asset_url($mysqli, 'site_favicon');
$loginError = (string)($_SESSION['admin_login_error'] ?? '');
$loginUsername = (string)($_SESSION['admin_login_username'] ?? '');
unset($_SESSION['admin_login_error'], $_SESSION['admin_login_username']);

if (isset($_POST['dangnhap'])) {
    $taikhoan = trim((string)($_POST['username'] ?? ''));
    $matkhau = (string)($_POST['password'] ?? '');
    $_SESSION['admin_login_username'] = $taikhoan;

    if ($taikhoan === '' || $matkhau === '') {
        $_SESSION['admin_login_error'] = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
        header('Location:login.php');
        exit;
    }

    $stmt = $mysqli->prepare('SELECT id_admin, username, password FROM tbl_admin WHERE username = ? AND admin_status > 0 LIMIT 1');
    if (!$stmt) {
        $_SESSION['admin_login_error'] = 'Không thể đăng nhập lúc này, vui lòng thử lại sau.';
        header('Location:login.php');
        exit;
    }
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
        unset($_SESSION['admin_login_error'], $_SESSION['admin_login_username']);
        header('Location:index.php');
        exit;
    }

    $_SESSION['admin_login_error'] = 'Tên đăng nhập hoặc mật khẩu không đúng.';
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

            <?php if ($loginError !== '') { ?>
                <div class="login-alert" role="alert">
                    <span class="login-alert-icon"><i class="fas fa-triangle-exclamation"></i></span>
                    <span><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            <?php } ?>

            <form action="" method="POST" id="admin-login-form" novalidate>
                <div class="form-group">
                    <label class="form-label">Tên đăng nhập</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" value="<?php echo htmlspecialchars($loginUsername, ENT_QUOTES, 'UTF-8'); ?>" data-required="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" data-required="true">
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
                        <span class="demo-field-val" id="demo-pass">12345678</span>
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
    const loginForm = document.getElementById('admin-login-form');

    function getLoginFieldMessage(field) {
        return field.name === 'username'
            ? 'Vui lòng nhập tên đăng nhập.'
            : 'Vui lòng nhập mật khẩu.';
    }

    function showFieldError(field, message) {
        const group = field.closest('.form-group');
        let error = group ? group.querySelector('.form-field-error') : null;

        if (!error && group) {
            error = document.createElement('div');
            error.className = 'form-field-error';
            group.appendChild(error);
        }

        if (group) {
            group.classList.add('has-field-error');
        }
        field.classList.add('has-soft-error');
        if (error) {
            error.textContent = message;
        }
    }

    function clearFieldError(field) {
        const group = field.closest('.form-group');
        const error = group ? group.querySelector('.form-field-error') : null;

        if (group) {
            group.classList.remove('has-field-error');
        }
        field.classList.remove('has-soft-error');
        if (error) {
            error.textContent = '';
        }
    }

    if (loginForm) {
        loginForm.querySelectorAll('input[data-required="true"]').forEach(function (field) {
            field.addEventListener('input', function () {
                if (field.value.trim() !== '') {
                    clearFieldError(field);
                }
            });
        });

        loginForm.addEventListener('submit', function (event) {
            let firstInvalidField = null;

            loginForm.querySelectorAll('input[data-required="true"]').forEach(function (field) {
                if (field.value.trim() === '') {
                    showFieldError(field, getLoginFieldMessage(field));
                    firstInvalidField = firstInvalidField || field;
                } else {
                    clearFieldError(field);
                }
            });

            if (firstInvalidField) {
                event.preventDefault();
                firstInvalidField.focus();
            }
        });
    }

    function toggleDemo(btn) {
        const body = document.getElementById('demo-body');
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', !expanded);
        body.hidden = expanded;
        btn.querySelector('.demo-chevron').style.transform = expanded ? '' : 'rotate(180deg)';
    }
    function fillDemo() {
        const usernameInput = document.querySelector('input[name="username"]');
        const passwordInput = document.querySelector('input[name="password"]');
        usernameInput.value = document.getElementById('demo-user').textContent;
        passwordInput.value = document.getElementById('demo-pass').textContent;
        usernameInput.dispatchEvent(new Event('input'));
        passwordInput.dispatchEvent(new Event('input'));
        usernameInput.focus();
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
