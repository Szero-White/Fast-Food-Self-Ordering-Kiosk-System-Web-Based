<?php
session_start();
include('config/config.php');

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }

        /* Animated background shapes */
        .bg-shapes {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        .shape {
            position: absolute;
            filter: blur(50px);
            opacity: 0.6;
            animation: float 20s infinite ease-in-out;
        }
        .shape-1 {
            width: 400px; height: 400px;
            background: #ff6b6b;
            border-radius: 50%;
            top: -200px; right: -100px;
            animation-delay: 0s;
        }
        .shape-2 {
            width: 300px; height: 300px;
            background: #4ecdc4;
            border-radius: 50%;
            bottom: -150px; left: -100px;
            animation-delay: 5s;
        }
        .shape-3 {
            width: 250px; height: 250px;
            background: #ffe66d;
            border-radius: 50%;
            top: 50%; left: 50%;
            animation-delay: 10s;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(50px, -50px) scale(1.1); }
            50% { transform: translate(-30px, 30px) scale(0.9); }
            75% { transform: translate(30px, 50px) scale(1.05); }
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .forgot-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 40px 35px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-icon {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
        }
        .logo-icon i { font-size: 35px; color: white; }
        .logo-section h2 {
            color: white;
            font-weight: 700;
            font-size: 26px;
            margin-bottom: 5px;
        }
        .logo-section p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            gap: 10px;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
        .step-circle {
            width: 35px; height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }
        .step.active .step-circle {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .step.completed .step-circle {
            background: #4ecdc4;
            color: white;
        }
        .step.inactive .step-circle {
            background: rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.6);
        }
        .step-label {
            font-size: 11px;
            color: rgba(255,255,255,0.7);
        }
        .step.active .step-label {
            color: white;
            font-weight: 500;
        }

        .form-group { margin-bottom: 22px; }
        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }
        .input-wrapper { position: relative; }
        .input-wrapper i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
        }
        .form-control {
            width: 100%;
            padding: 14px 15px 14px 48px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.5); }
        .form-control:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }
        .form-control:disabled {
            background: rgba(255, 255, 255, 0.05);
            cursor: not-allowed;
        }

        /* Security question display */
        .question-box {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .question-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .question-text {
            color: white;
            font-size: 16px;
            font-weight: 500;
        }

        .btn-action {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }
        .btn-success {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%) !important;
            box-shadow: 0 10px 30px rgba(78, 205, 196, 0.4) !important;
        }
        .btn-success:hover {
            box-shadow: 0 15px 40px rgba(78, 205, 196, 0.5) !important;
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }
        .back-link a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        .back-link a:hover {
            color: white;
        }

        .alert-custom {
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background: rgba(255, 107, 107, 0.2);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ffcccc;
        }
        .alert-success {
            background: rgba(78, 205, 196, 0.2);
            border: 1px solid rgba(78, 205, 196, 0.3);
            color: #ccffee;
        }

        /* Success animation */
        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: scaleIn 0.5s ease;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .success-icon i { font-size: 50px; color: white; }

        @media (max-width: 576px) {
            .forgot-card { padding: 30px 20px; }
            .logo-section h2 { font-size: 22px; }
        }
    </style>
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
                    <i class="fas fa-key"></i>
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
