<?php
session_start();
include('config/config.php');
require_once __DIR__ . '/../config/site_asset_repository.php';

$adminLogoUrl = site_asset_url($mysqli, 'admin_logo');
$adminFaviconUrl = site_asset_url($mysqli, 'site_favicon');

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

// Step 1: Check username and get security question
if ($step == 1 && isset($_POST['check_user'])) {
    $username = mysqli_real_escape_string($mysqli, $_POST['username']);
    // Kiểm tra admin đang hoạt động (status > 0)
    $sql = "SELECT id_admin, security_question, security_answer FROM tbl_admin WHERE username = '$username' AND admin_status > 0";
    $result = mysqli_query($mysqli, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Nếu chưa có câu trả lời xác thực, set mặc định là 'cat'
        if (empty($row['security_answer'])) {
            $admin_id = $row['id_admin'];
            $default_answer = md5('cat');
            mysqli_query($mysqli, "UPDATE tbl_admin SET security_answer = '$default_answer', security_question = 'Thú cưng yêu thích của bạn là gì?' WHERE id_admin = $admin_id");
            $row['security_question'] = 'Thú cưng yêu thích của bạn là gì?';
        }
        
        $_SESSION['reset_admin_id'] = $row['id_admin'];
        $_SESSION['reset_username'] = $username;
        $_SESSION['security_question'] = $row['security_question'] ?? 'Thú cưng yêu thích của bạn là gì?';
        header('Location: forgot_password.php?step=2');
        exit;
    } else {
        $error = 'Tài khoản không tồn tại hoặc đã bị khóa!';
    }
}

// Step 2: Verify security answer
if ($step == 2 && isset($_POST['verify_answer'])) {
    if (!isset($_SESSION['reset_admin_id'])) {
        header('Location: forgot_password.php?step=1');
        exit;
    }
    
    $answer = md5($_POST['security_answer']);
    $admin_id = $_SESSION['reset_admin_id'];
    
    $sql = "SELECT * FROM tbl_admin WHERE id_admin = $admin_id AND security_answer = '$answer'";
    $result = mysqli_query($mysqli, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['verified'] = true;
        header('Location: forgot_password.php?step=3');
        exit;
    } else {
        $error = 'Câu trả lời xác thực không đúng!';
    }
}

// Step 3: Reset password
if ($step == 3 && isset($_POST['reset_password'])) {
    if (!isset($_SESSION['reset_admin_id']) || !isset($_SESSION['verified'])) {
        header('Location: forgot_password.php?step=1');
        exit;
    }
    
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password != $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp!';
    } elseif (strlen($new_password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự!';
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $admin_id = $_SESSION['reset_admin_id'];
        $stmt = $mysqli->prepare("UPDATE tbl_admin SET password = ? WHERE id_admin = ?");
        $stmt->bind_param('si', $hashed, $admin_id);
        
        if ($stmt->execute()) {
            // Clear session
            unset($_SESSION['reset_admin_id']);
            unset($_SESSION['reset_username']);
            unset($_SESSION['security_question']);
            unset($_SESSION['verified']);
            $success = 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập ngay bây giờ.';
        } else {
            $error = 'Có lỗi xảy ra, vui lòng thử lại!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu - FastFood Admin</title>
    <link rel="icon" href="<?php echo htmlspecialchars($adminFaviconUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="apple-touch-icon" href="<?php echo htmlspecialchars($adminFaviconUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css_admin/auth-forgot.css">
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
                <h2>Khôi Phục Mật Khẩu</h2>
                <p>Bước <?php echo $step; ?>/3</p>
            </div>

            <!-- Progress Steps -->
            <div class="progress-steps">
                <div class="step <?php echo $step >= 1 ? 'completed' : 'inactive'; ?>">
                    <div class="step-circle"><i class="fas fa-user"></i></div>
                    <span class="step-label">Tài khoản</span>
                </div>
                <div class="step <?php echo $step == 2 ? 'active' : ($step > 2 ? 'completed' : 'inactive'); ?>">
                    <div class="step-circle"><i class="fas fa-shield-alt"></i></div>
                    <span class="step-label">Xác thực</span>
                </div>
                <div class="step <?php echo $step == 3 ? 'active' : 'inactive'; ?>">
                    <div class="step-circle"><i class="fas fa-lock"></i></div>
                    <span class="step-label">Đặt lại</span>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="alert-custom alert-error">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert-custom alert-success">
                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                </div>
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="back-link">
                    <a href="login.php"><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập ngay</a>
                </div>
            <?php else: ?>

                <?php if ($step == 1): ?>
                <!-- Step 1: Enter username -->
                <form method="POST" action="?step=1">
                    <div class="form-group">
                        <label class="form-label">Tên đăng nhập</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required autofocus>
                        </div>
                    </div>
                    <button type="submit" name="check_user" class="btn-action">
                        <i class="fas fa-arrow-right me-2"></i>Tiếp tục
                    </button>
                </form>

                <?php elseif ($step == 2): ?>
                <!-- Step 2: Answer security question -->
                <div class="question-box">
                    <div class="question-label">Câu hỏi xác thực</div>
                    <div class="question-text">
                        <i class="fas fa-question-circle me-2"></i>
                        <?php echo htmlspecialchars($_SESSION['security_question'] ?? 'Thú cưng yêu thích của bạn là gì?'); ?>
                    </div>
                </div>

                <form method="POST" action="?step=2">
                    <div class="form-group">
                        <label class="form-label">Câu trả lời của bạn</label>
                        <div class="input-wrapper">
                            <i class="fas fa-comment"></i>
                            <input type="text" name="security_answer" class="form-control" placeholder="Nhập câu trả lời của bạn..." required autofocus autocomplete="off">
                        </div>
                    </div>
                    <button type="submit" name="verify_answer" class="btn-action">
                        <i class="fas fa-check-circle me-2"></i>Xác nhận
                    </button>
                </form>

                <?php elseif ($step == 3): ?>
                <!-- Step 3: Reset password -->
                <form method="POST" action="?step=3">
                    <div class="form-group">
                        <label class="form-label">Mật khẩu mới</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="new_password" class="form-control" placeholder="Ít nhất 6 ký tự" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required>
                        </div>
                    </div>
                    <button type="submit" name="reset_password" class="btn-action btn-success">
                        <i class="fas fa-save me-2"></i>Đặt lại mật khẩu
                    </button>
                </form>
                <?php endif; ?>

                <div class="back-link">
                    <a href="login.php"><i class="fas fa-arrow-left me-2"></i>Quay lại đăng nhập</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
