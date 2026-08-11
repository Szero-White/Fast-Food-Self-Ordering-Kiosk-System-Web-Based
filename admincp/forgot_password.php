<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin_security.php';
include('config/config.php');
require_once __DIR__ . '/../config/site_asset_repository.php';

$adminLogoUrl = site_asset_url($mysqli, 'admin_logo');
$adminFaviconUrl = site_asset_url($mysqli, 'site_favicon');
$step = max(1, min(3, (int)($_GET['step'] ?? 1)));
$error = '';
$success = '';

function reset_csrf_token(): string
{
    if (empty($_SESSION['reset_csrf_token']) || !is_string($_SESSION['reset_csrf_token'])) {
        $_SESSION['reset_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['reset_csrf_token'];
}

function reset_csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(reset_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function reset_csrf_is_valid(): bool
{
    $token = (string)($_POST['csrf_token'] ?? '');
    return $token !== '' && hash_equals(reset_csrf_token(), $token);
}

function redirect_reset_step(int $step): void
{
    header('Location: forgot_password.php?step=' . $step);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !reset_csrf_is_valid()) {
    $error = 'Phiên xác thực không hợp lệ, vui lòng thử lại.';
}

if ($error === '' && $step === 1 && isset($_POST['check_user'])) {
    $username = trim((string)($_POST['username'] ?? ''));

    $stmt = $mysqli->prepare(
        'SELECT id_admin, username, security_question, security_answer
         FROM tbl_admin
         WHERE username = ? AND admin_status > 0
         LIMIT 1'
    );
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$admin) {
        $error = 'Tài khoản không tồn tại hoặc đã bị khóa.';
    } elseif (empty($admin['security_question']) || empty($admin['security_answer'])) {
        $error = 'Tài khoản này chưa cấu hình câu hỏi bảo mật. Vui lòng liên hệ quản trị viên.';
    } else {
        $_SESSION['reset_admin_id'] = (int)$admin['id_admin'];
        $_SESSION['reset_username'] = (string)$admin['username'];
        $_SESSION['security_question'] = (string)$admin['security_question'];
        unset($_SESSION['verified']);
        redirect_reset_step(2);
    }
}

if ($error === '' && $step === 2 && isset($_POST['verify_answer'])) {
    if (empty($_SESSION['reset_admin_id'])) {
        redirect_reset_step(1);
    }

    $adminId = (int)$_SESSION['reset_admin_id'];
    $answer = trim((string)($_POST['security_answer'] ?? ''));
    $stmt = $mysqli->prepare('SELECT security_answer FROM tbl_admin WHERE id_admin = ? AND admin_status > 0 LIMIT 1');
    $stmt->bind_param('i', $adminId);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $storedAnswer = (string)($admin['security_answer'] ?? '');
    $legacyAnswerOk = strlen($storedAnswer) === 32 && hash_equals($storedAnswer, md5($answer));
    $passwordAnswerOk = password_verify($answer, $storedAnswer);

    if ($legacyAnswerOk || $passwordAnswerOk) {
        if ($legacyAnswerOk) {
            $newAnswerHash = password_hash($answer, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare('UPDATE tbl_admin SET security_answer = ? WHERE id_admin = ?');
            $stmt->bind_param('si', $newAnswerHash, $adminId);
            $stmt->execute();
            $stmt->close();
        }

        $_SESSION['verified'] = true;
        redirect_reset_step(3);
    } else {
        $error = 'Câu trả lời bảo mật không đúng.';
    }
}

if ($error === '' && $step === 3 && isset($_POST['reset_password'])) {
    if (empty($_SESSION['reset_admin_id']) || empty($_SESSION['verified'])) {
        redirect_reset_step(1);
    }

    $adminId = (int)$_SESSION['reset_admin_id'];
    $newPassword = (string)($_POST['new_password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    if ($newPassword !== $confirmPassword) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } elseif (strlen($newPassword) < 8) {
        $error = 'Mật khẩu phải có ít nhất 8 ký tự.';
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('UPDATE tbl_admin SET password = ? WHERE id_admin = ?');
        $stmt->bind_param('si', $hashedPassword, $adminId);

        if ($stmt->execute()) {
            unset(
                $_SESSION['reset_admin_id'],
                $_SESSION['reset_username'],
                $_SESSION['security_question'],
                $_SESSION['verified'],
                $_SESSION['reset_csrf_token']
            );
            $success = 'Đặt lại mật khẩu thành công. Bạn có thể đăng nhập ngay bây giờ.';
            $step = 1;
        } else {
            $error = 'Có lỗi xảy ra, vui lòng thử lại.';
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - FastFood Admin</title>
    <link rel="icon" href="<?php echo htmlspecialchars($adminFaviconUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="apple-touch-icon" href="<?php echo htmlspecialchars($adminFaviconUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css_admin/auth-forgot.css?v=<?php echo filemtime(__DIR__ . '/css_admin/auth-forgot.css'); ?>">
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="container">
        <div class="forgot-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="<?php echo htmlspecialchars($adminLogoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="FastFood">
                </div>
                <h2>Khôi phục mật khẩu</h2>
                <p>Bước <?php echo $step; ?>/3</p>
            </div>

            <div class="progress-steps">
                <div class="step <?php echo $step > 1 ? 'completed' : ($step === 1 ? 'active' : 'inactive'); ?>">
                    <div class="step-circle"><i class="fas fa-user"></i></div>
                    <span class="step-label">Tài khoản</span>
                </div>
                <div class="step <?php echo $step > 2 ? 'completed' : ($step === 2 ? 'active' : 'inactive'); ?>">
                    <div class="step-circle"><i class="fas fa-shield-alt"></i></div>
                    <span class="step-label">Xác thực</span>
                </div>
                <div class="step <?php echo $step > 3 ? 'completed' : ($step === 3 ? 'active' : 'inactive'); ?>">
                    <div class="step-circle"><i class="fas fa-lock"></i></div>
                    <span class="step-label">Mật khẩu mới</span>
                </div>
            </div>

            <?php if ($error !== '') { ?>
                <div class="auth-alert auth-alert-danger" role="alert">
                    <span class="auth-alert-icon"><i class="fas fa-triangle-exclamation"></i></span>
                    <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            <?php } ?>

            <?php if ($success !== '') { ?>
                <div class="auth-alert auth-alert-success" role="alert">
                    <span class="auth-alert-icon"><i class="fas fa-circle-check"></i></span>
                    <span><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <a href="login.php" class="btn-action btn-success"><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập ngay</a>
            <?php } elseif ($step === 1) { ?>
                <form method="POST" novalidate>
                    <?php echo reset_csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label">Tên đăng nhập</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" data-required="true">
                        </div>
                    </div>
                    <button type="submit" name="check_user" class="btn-action">
                        <i class="fas fa-search me-2"></i>Kiểm tra tài khoản
                    </button>
                </form>
            <?php } elseif ($step === 2) { ?>
                <form method="POST" novalidate>
                    <?php echo reset_csrf_field(); ?>
                    <div class="question-box">
                        <i class="fas fa-question-circle"></i>
                        <h5>Câu hỏi bảo mật</h5>
                        <p><?php echo htmlspecialchars((string)($_SESSION['security_question'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Câu trả lời</label>
                        <div class="input-wrapper">
                            <i class="fas fa-key"></i>
                            <input type="text" name="security_answer" class="form-control" placeholder="Nhập câu trả lời" data-required="true">
                        </div>
                    </div>
                    <button type="submit" name="verify_answer" class="btn-action">
                        <i class="fas fa-check me-2"></i>Xác nhận
                    </button>
                </form>
            <?php } else { ?>
                <form method="POST" novalidate>
                    <?php echo reset_csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label">Mật khẩu mới</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="new_password" class="form-control" placeholder="Nhập mật khẩu mới" data-required="true" data-min-length="8">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu mới" data-required="true" data-min-length="8">
                        </div>
                    </div>
                    <button type="submit" name="reset_password" class="btn-action">
                        <i class="fas fa-save me-2"></i>Đặt lại mật khẩu
                    </button>
                </form>
            <?php } ?>

            <div class="back-link">
                <a href="login.php"><i class="fas fa-arrow-left me-1"></i>Quay lại đăng nhập</a>
            </div>
        </div>
    </div>
    <script>
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

    function getForgotFieldMessage(field) {
        var messages = {
            username: 'Vui lòng nhập tên đăng nhập.',
            security_answer: 'Vui lòng nhập câu trả lời bảo mật.',
            new_password: 'Vui lòng nhập mật khẩu mới.',
            confirm_password: 'Vui lòng nhập lại mật khẩu mới.'
        };
        const value = field.value.trim();
        const minLength = Number(field.dataset.minLength || 0);

        if (field.dataset.required === 'true' && value === '') {
            return messages[field.name] || 'Vui lòng nhập đầy đủ thông tin.';
        }

        if (minLength > 0 && field.value.length > 0 && field.value.length < minLength) {
            return 'Mật khẩu phải có ít nhất 8 ký tự.';
        }

        if (field.name === 'confirm_password') {
            var newPassword = document.querySelector('input[name="new_password"]');
            if (newPassword && field.value !== '' && field.value !== newPassword.value) {
                return 'Mật khẩu xác nhận không khớp.';
            }
        }

        return '';
    }

    document.querySelectorAll('form').forEach(function (form) {
        const fields = form.querySelectorAll('input[data-required="true"], input[data-min-length]');

        fields.forEach(function (field) {
            field.addEventListener('input', function () {
                const message = getForgotFieldMessage(field);
                if (message === '') {
                    clearFieldError(field);
                }
            });
        });

        form.addEventListener('submit', function (event) {
            let firstInvalidField = null;

            fields.forEach(function (field) {
                const message = getForgotFieldMessage(field);
                if (message !== '') {
                    showFieldError(field, message);
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
    });
    </script>
</body>
</html>
