<?php
declare(strict_types=1);

const ADMIN_SESSION_GC_LIFETIME = 86400;

if (session_status() === PHP_SESSION_NONE) {
    // Admin dùng phiên trình duyệt và không có bộ đếm tự đăng xuất riêng.
    // Lifetime bên dưới chỉ giúp PHP giữ file session lâu hơn mặc định.
    ini_set('session.gc_maxlifetime', (string) ADMIN_SESSION_GC_LIFETIME);
    ini_set('session.cookie_lifetime', '0');

    $adminSessionPath = sys_get_temp_dir() . '/fastfood_admin_sessions';
    if (!is_dir($adminSessionPath)) {
        mkdir($adminSessionPath, 0700, true);
    }
    session_save_path($adminSessionPath);

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function admin_require_login(string $loginPath = 'login.php'): void
{
    if (!isset($_SESSION['dangnhap'])) {
        header('Location: ' . $loginPath);
        exit;
    }
}

function admin_csrf_token(): string
{
    if (empty($_SESSION['admin_csrf_token']) || !is_string($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['admin_csrf_token'];
}

function admin_csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="'
        . htmlspecialchars(admin_csrf_token(), ENT_QUOTES, 'UTF-8')
        . '">';
}

function admin_set_flash(string $type, string $message): void
{
    $_SESSION['admin_flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function admin_render_flash(): void
{
    $flash = $_SESSION['admin_flash'] ?? null;
    unset($_SESSION['admin_flash']);

    if (!is_array($flash)) {
        return;
    }

    $type = (string)($flash['type'] ?? 'info');
    $message = trim((string)($flash['message'] ?? ''));
    $allowedTypes = ['success', 'danger', 'warning', 'info'];

    if ($message === '') {
        return;
    }

    if (!in_array($type, $allowedTypes, true)) {
        $type = 'info';
    }

    echo '<div class="alert alert-' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . ' admin-flash-alert" role="alert">'
        . '<i class="fas fa-circle-info me-2"></i>'
        . htmlspecialchars($message, ENT_QUOTES, 'UTF-8')
        . '</div>';
}

function admin_verify_csrf_token(): bool
{
    $expectedToken = admin_csrf_token();
    $actualToken   = (string) ($_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '');

    return $actualToken !== '' && hash_equals($expectedToken, $actualToken);
}

function admin_require_valid_csrf(string $redirectPath = '../../index.php'): void
{
    if (admin_verify_csrf_token()) {
        return;
    }

    http_response_code(403);
    echo 'Yêu cầu không hợp lệ. <a href="../../login.php">Đăng nhập lại</a>';
    exit;
}
