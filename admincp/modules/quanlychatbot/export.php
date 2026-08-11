<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/admin_security.php';
require_once __DIR__ . '/../../config/config.php';

admin_require_login('../../login.php');

$allowedTypes = ['static', 'api_products', 'api_price', 'api_promo', 'api_stock', 'api_cart_add', 'ai_gemini', 'fallback', 'error'];
$type = (string)($_GET['type'] ?? '');
$whereSql = in_array($type, $allowedTypes, true)
    ? "WHERE response_type = '" . mysqli_real_escape_string($mysqli, $type) . "'"
    : '';

$typeLabels = [
    'static' => 'Trả lời tĩnh',
    'api_products' => 'API - Sản phẩm',
    'api_price' => 'API - Giá',
    'api_promo' => 'API - Khuyến mãi',
    'api_stock' => 'API - Tồn kho',
    'api_cart_add' => 'API - Thêm giỏ hàng',
    'ai_gemini' => 'AI - Gemini',
    'fallback' => 'Không hiểu',
    'error' => 'Lỗi',
];

function chatbot_export_text(?string $value): string
{
    $text = (string)$value;
    $text = str_replace(['<br>', '<br/>', '<br />'], "\n", $text);
    $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return trim(preg_replace('/\s+/', ' ', $text) ?? '');
}

$filename = 'lich-su-chatbot-' . date('Ymd-His') . '.csv';

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');
echo "\xEF\xBB\xBF";

fputcsv($output, [
    'ID',
    'Thời gian',
    'Câu hỏi',
    'Trả lời',
    'Từ khóa',
    'Loại phản hồi',
    'IP',
    'Trình duyệt',
]);

$sql = "SELECT id, user_message, bot_response, matched_keyword, response_type, user_ip, user_agent, created_at
        FROM tbl_chatbot_history
        $whereSql
        ORDER BY created_at DESC";
$result = mysqli_query($mysqli, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['created_at'],
        chatbot_export_text($row['user_message'] ?? ''),
        chatbot_export_text($row['bot_response'] ?? ''),
        chatbot_export_text($row['matched_keyword'] ?? ''),
        $typeLabels[$row['response_type']] ?? $row['response_type'],
        $row['user_ip'] ?? '',
        chatbot_export_text($row['user_agent'] ?? ''),
    ]);
}

fclose($output);
exit;
