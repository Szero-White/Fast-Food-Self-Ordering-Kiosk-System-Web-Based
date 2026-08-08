<?php
session_start();
include('config/config.php');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
            width: 400px;
            height: 400px;
            background: #ff6b6b;
            border-radius: 50%;
            top: -200px;
            right: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: #4ecdc4;
            border-radius: 50%;
            bottom: -150px;
            left: -100px;
            animation-delay: 5s;
        }

        .shape-3 {
            width: 250px;
            height: 250px;
            background: #ffe66d;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(50px, -50px) scale(1.1); }
            50% { transform: translate(-30px, 30px) scale(0.9); }
            75% { transform: translate(30px, 50px) scale(1.05); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 50px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .logo-icon i {
            font-size: 40px;
            color: white;
        }

        .logo-section h2 {
            color: white;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .logo-section p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .input-wrapper {
            position: relative;
        }

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
            padding: 15px 15px 15px 50px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-password {
            text-align: center;
            margin-top: 25px;
        }

        .forgot-password a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: white;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 35px 25px;
            }

            .logo-section h2 {
                font-size: 24px;
            }
        }
    </style>
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
                    <i class="fas fa-hamburger"></i>
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



