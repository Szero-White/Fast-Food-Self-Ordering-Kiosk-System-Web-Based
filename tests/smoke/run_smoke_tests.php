<?php

require_once __DIR__ . '/SmokeTestResult.php';

$rootPath = dirname(__DIR__, 2);
$baseUrl = rtrim($argv[1] ?? getenv('KIOSK_BASE_URL') ?: 'http://localhost/web_mysqli', '/');
$result = new SmokeTestResult();

echo 'Smoke test FastFood Kiosk' . PHP_EOL;
echo 'Thư mục dự án: ' . $rootPath . PHP_EOL;
echo 'Base URL: ' . $baseUrl . PHP_EOL . PHP_EOL;

run_php_lint_tests($result, $rootPath);
run_filesystem_tests($result, $rootPath);
run_http_tests($result, $baseUrl);
run_admin_guard_tests($result, $baseUrl);

exit($result->summary());

function run_php_lint_tests(SmokeTestResult $result, string $rootPath): void
{
    $files = [
        'admincp/index.php',
        'admincp/login.php',
        'admincp/forgot_password.php',
        'admincp/includes/admin_security.php',
        'view/index.php',
        'view/pages/main.php',
        'view/pages/main/chatbot_api.php',
        'view/pages/main/giohang.php',
        'view/pages/main/thanhtoan.php',
        'view/pages/main/lienhe.php',
        'config/paths.php',
        'config/banner_repository.php',
        'config/kiosk_order_repository.php',
        'config/gemini_chatbot_client.php',
    ];

    foreach ($files as $file) {
        $fullPath = $rootPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);
        if (!is_file($fullPath)) {
            $result->fail('Kiểm tra cú pháp PHP: ' . $file, 'Không tìm thấy file');
            continue;
        }

        $command = 'php -l ' . escapeshellarg($fullPath) . ' 2>&1';
        $output = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        $result->check(
            $exitCode === 0,
            'Kiểm tra cú pháp PHP: ' . $file,
            'Cú pháp hợp lệ',
            implode(' ', $output)
        );
    }
}

function run_filesystem_tests(SmokeTestResult $result, string $rootPath): void
{
    $requiredFiles = [
        '.gitignore',
        'README.md',
        'storage/uploads/.htaccess',
        'storage/uploads/.gitkeep',
        'storage/uploads/products/.gitkeep',
        'storage/uploads/posts/.gitkeep',
        'storage/uploads/site/.gitkeep',
        'storage/uploads/banners/.gitkeep',
        'config/chatbot_ai_config.php',
        'config/chatbot_ai_secret.example.php',
    ];

    foreach ($requiredFiles as $file) {
        $path = $rootPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);
        $result->check(is_file($path), 'File bắt buộc: ' . $file, 'Đã tìm thấy', 'Bị thiếu');
    }

    $secretPath = $rootPath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'chatbot_ai_secret.php';
    if (is_file($secretPath)) {
        $command = 'git -C ' . escapeshellarg($rootPath) . ' check-ignore ' . escapeshellarg('config/chatbot_ai_secret.php') . ' 2>&1';
        $output = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        $result->check(
            $exitCode === 0,
            'File secret AI được Git ignore',
            'config/chatbot_ai_secret.php đã được ignore',
            'config/chatbot_ai_secret.php đang tồn tại nhưng chưa được ignore'
        );
    } else {
        $result->pass('File secret AI được Git ignore', 'Chưa tạo file secret local');
    }

    $uploadsHtaccess = $rootPath . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . '.htaccess';
    $htaccessContent = is_file($uploadsHtaccess) ? file_get_contents($uploadsHtaccess) : '';
    $blocksPhpExecution = stripos($htaccessContent, 'php') !== false
        && (stripos($htaccessContent, 'Deny from all') !== false || stripos($htaccessContent, 'Require all denied') !== false);

    $result->check(
        $blocksPhpExecution,
        'Chặn thực thi script trong upload',
        '.htaccess có cấu hình chặn thực thi PHP',
        '.htaccess chưa thể hiện rõ việc chặn thực thi PHP'
    );
}

function run_http_tests(SmokeTestResult $result, string $baseUrl): void
{
    $pages = [
        'Trang chào mừng' => '/view/index.php?quanly=welcome',
        'Trang chủ' => '/view/index.php?quanly=index',
        'Trang giới thiệu' => '/view/index.php?quanly=gioithieu',
        'Trang tin tức khuyến mãi' => '/view/index.php?quanly=danhmucbaiviet',
        'Trang liên hệ' => '/view/index.php?quanly=lienhe',
        'Trang giỏ hàng' => '/view/index.php?quanly=giohang',
        'Trang gọi nhân viên' => '/view/index.php?quanly=goinhanvien',
    ];

    foreach ($pages as $name => $path) {
        $response = http_request($baseUrl . $path);
        $result->check(
            $response['status'] >= 200 && $response['status'] < 400 && !contains_php_error($response['body']),
            $name,
            'HTTP ' . $response['status'],
            'HTTP ' . $response['status'] . ' ' . summarize_body($response['body'])
        );
    }

    $chatbotResponse = http_request($baseUrl . '/view/pages/main/chatbot_api.php?action=get_products');
    $chatbotJson = json_decode($chatbotResponse['body'], true);
    $result->check(
        $chatbotResponse['status'] === 200 && is_array($chatbotJson),
        'Chatbot API: lấy danh sách món',
        'Phản hồi JSON hợp lệ',
        'HTTP ' . $chatbotResponse['status'] . ' ' . summarize_body($chatbotResponse['body'])
    );
}

function run_admin_guard_tests(SmokeTestResult $result, string $baseUrl): void
{
    $adminActions = [
        'Bảo vệ xử lý đơn hàng' => '/admincp/modules/quanlydonhang/xuly.php?iddonhang=1&action=xoa',
        'Bảo vệ xử lý món ăn' => '/admincp/modules/quanlysp/xuly.php?idsp=1&action=xoa',
        'Bảo vệ xử lý banner' => '/admincp/modules/quanlybanner/xuly.php?idbanner=1&action=xoa',
        'Bảo vệ xử lý gọi nhân viên' => '/admincp/modules/quanlyhotro/xuly.php?id=1&action=hoanthanh',
    ];

    foreach ($adminActions as $name => $path) {
        $response = http_request($baseUrl . $path);
        $isProtected = in_array($response['status'], [302, 401, 403], true)
            || stripos($response['body'], 'login.php') !== false
            || stripos($response['body'], 'Dang nhap') !== false
            || stripos($response['body'], 'Đăng nhập') !== false;

        $result->check(
            $isProtected,
            $name,
            'Đã chặn khi chưa đăng nhập admin',
            'Endpoint có thể đang mở khi chưa đăng nhập admin, HTTP ' . $response['status']
        );
    }

    $historyResponse = http_request($baseUrl . '/view/pages/main/chatbot_api.php?action=get_chat_history');
    $historyJson = json_decode($historyResponse['body'], true);
    $historyProtected = $historyResponse['status'] === 401
        || (is_array($historyJson) && ($historyJson['success'] ?? true) === false && ($historyJson['code'] ?? '') === 'unauthorized');

    $result->check(
        $historyProtected,
        'Bảo vệ lịch sử chatbot',
        'Đã chặn khi chưa đăng nhập admin',
        'Endpoint lịch sử chatbot có thể đang mở khi chưa đăng nhập admin'
    );
}

function http_request(string $url): array
{
    $headers = [];
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'ignore_errors' => true,
            'timeout' => 8,
            'header' => "User-Agent: FastFoodSmokeTest/1.0\r\n",
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    if (isset($http_response_header) && is_array($http_response_header)) {
        $headers = $http_response_header;
    }

    return [
        'status' => parse_status_code($headers),
        'body' => $body === false ? '' : $body,
        'headers' => $headers,
    ];
}

function parse_status_code(array $headers): int
{
    if ($headers === []) {
        return 0;
    }

    if (preg_match('/\s(\d{3})\s/', $headers[0], $matches)) {
        return (int)$matches[1];
    }

    return 0;
}

function contains_php_error(string $body): bool
{
    return preg_match('/(Fatal error|Parse error|Warning:|Notice:|Stack trace)/i', $body) === 1;
}

function summarize_body(string $body): string
{
    $body = trim(strip_tags($body));
    $body = preg_replace('/\s+/', ' ', $body) ?? '';

    return mb_substr($body, 0, 180);
}
