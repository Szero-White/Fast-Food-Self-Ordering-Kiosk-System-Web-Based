<?php
$action = $_GET['action'] ?? '';
$adminOnlyActions = ['get_chat_history', 'get_chat_stats', 'get_chat_stats_by_date'];

if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    if (in_array($action, $adminOnlyActions, true)) {
        $adminSessionPath = sys_get_temp_dir() . '/fastfood_admin_sessions';
        if (!is_dir($adminSessionPath)) {
            mkdir($adminSessionPath, 0700, true);
        }
        session_save_path($adminSessionPath);
    }

    session_start();
}
header('Content-Type: application/json; charset=utf-8');

$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
$requestHost = $_SERVER['HTTP_HOST'] ?? '';
if ($requestOrigin !== '') {
    $originHost = parse_url($requestOrigin, PHP_URL_HOST);
    $normalizedHost = strtolower(preg_replace('/:\d+$/', '', $requestHost) ?? $requestHost);
    if (is_string($originHost) && strtolower($originHost) === $normalizedHost) {
        header('Access-Control-Allow-Origin: ' . $requestOrigin);
    }
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($requestMethod === 'OPTIONS') {
    exit(0);
}

include(__DIR__ . '/../../config/config.php');
require_once __DIR__ . '/../../controllers/cart_controller.php';
require_once __DIR__ . '/../../../config/kiosk_order_repository.php';
require_once __DIR__ . '/../../../config/gemini_chatbot_client.php';

if (!$mysqli) {
    $response = ['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()];
    echo json_encode($response);
    exit;
}

// Test query
$result = mysqli_query($mysqli, "SELECT 1");
if (!$result) {
    $response = ['success' => false, 'message' => 'Database query failed: ' . mysqli_error($mysqli)];
    echo json_encode($response);
    exit;
}

function chatbot_limit_text(string $value, int $length): string
{
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $length, 'UTF-8');
    }

    return substr($value, 0, $length);
}

function chatbot_request_json(): array
{
    $data = json_decode(file_get_contents('php://input'), true);

    return is_array($data) ? $data : [];
}

$response = ['success' => false, 'data' => null, 'message' => ''];
$allowedResponseTypes = ['static', 'api_products', 'api_price', 'api_promo', 'api_stock', 'api_cart_add', 'ai_gemini', 'fallback', 'error'];

if (in_array($action, $adminOnlyActions, true) && !isset($_SESSION['dangnhap'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'code' => 'admin_required',
        'message' => 'Bạn cần đăng nhập quản trị để xem dữ liệu này.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

switch($action) {
    case 'ai_chat':
        $data = chatbot_request_json();
        $message = trim((string)($data['message'] ?? $_GET['message'] ?? ''));
        $message = chatbot_limit_text($message, 500);

        if ($message === '') {
            $response = ['success' => false, 'code' => 'missing_message', 'message' => 'Thiếu nội dung câu hỏi.'];
            break;
        }

        $aiResult = chatbot_ai_generate_reply($mysqli, $message);

        if (!empty($aiResult['success'])) {
            $response = [
                'success' => true,
                'data' => [
                    'response' => $aiResult['answer'],
                    'matched_keyword' => 'gemini',
                    'response_type' => 'ai_gemini',
                ],
            ];
        } else {
            $response = [
                'success' => false,
                'code' => $aiResult['code'] ?? 'ai_error',
                'message' => $aiResult['message'] ?? 'AI chưa thể trả lời lúc này.',
            ];
        }
        break;

    case 'get_products':
        // Lấy danh sách sản phẩm
        $sql = "SELECT sanpham.id_sanpham,
                       sanpham.tensanpham,
                       sanpham.giasp,
                       sanpham.soluong,
                       sanpham.hinhanh,
                       sanpham.tomtat,
                       danhmuc.tendanhmuc
                FROM tbl_sanpham AS sanpham
                LEFT JOIN tbl_danhmuc AS danhmuc ON danhmuc.id_danhmuc = sanpham.id_danhmuc
                WHERE sanpham.soluong > 0
                ORDER BY sanpham.id_sanpham DESC
                LIMIT 50";
        $result = mysqli_query($mysqli, $sql);
        $products = [];
        while($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        $response = ['success' => true, 'data' => $products, 'count' => count($products)];
        break;
        
    case 'search_product':
        // Tìm sản phẩm theo tên
        $keyword = trim((string)($_GET['keyword'] ?? ''));
        $keyword = chatbot_limit_text($keyword, 100);
        $likeKeyword = '%' . $keyword . '%';
        $products = [];
        $stmt = mysqli_prepare(
            $mysqli,
            "SELECT sanpham.id_sanpham,
                    sanpham.tensanpham,
                    sanpham.giasp,
                    sanpham.soluong,
                    sanpham.hinhanh,
                    sanpham.tomtat,
                    danhmuc.tendanhmuc
             FROM tbl_sanpham AS sanpham
             LEFT JOIN tbl_danhmuc AS danhmuc ON danhmuc.id_danhmuc = sanpham.id_danhmuc
             WHERE sanpham.tensanpham LIKE ? AND sanpham.soluong > 0
             LIMIT 5"
        );

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $likeKeyword);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
            mysqli_stmt_close($stmt);
        }
        $response = ['success' => true, 'data' => $products, 'keyword' => $keyword];
        break;

    case 'add_to_cart':
        $data = chatbot_request_json();
        $productId = (int)($data['product_id'] ?? 0);
        $quantity = max(1, (int)($data['quantity'] ?? 1));

        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $product = fetch_cart_product($mysqli, $productId);
        if ($product === null) {
            $response = ['success' => false, 'message' => 'Không tìm thấy món cần thêm vào giỏ hàng.'];
            break;
        }

        $quantity = kiosk_clamp_cart_quantity($quantity, (int)($product['soluong'] ?? 0));
        if ($quantity <= 0) {
            $response = ['success' => false, 'message' => 'Món này hiện đã hết hàng.'];
            break;
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ((int)$item['id'] === $productId) {
                $item['soluong'] = kiosk_clamp_cart_quantity(
                    (int)$item['soluong'] + $quantity,
                    (int)($product['soluong'] ?? 0)
                );
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => (int)$product['id_sanpham'],
                'ten' => (string)$product['tensanpham'],
                'gia' => (float)$product['giasp'],
                'hinhanh' => (string)$product['hinhanh'],
                'soluong' => $quantity,
            ];
        }

        $response = [
            'success' => true,
            'data' => [
                'product' => $product,
                'added_quantity' => $quantity,
                'cart_quantity' => kiosk_cart_quantity($_SESSION['cart']),
                'cart_total' => kiosk_cart_total($_SESSION['cart']),
            ],
        ];
        break;
        
    case 'get_promotions':
        // Lấy bài viết khuyến mãi mới nhất - tìm cả có dấu và không dấu
        $sql = "SELECT tenbaiviet, tomtat FROM tbl_baiviet 
                WHERE tenbaiviet LIKE '%khuyen mai%' 
                   OR tenbaiviet LIKE '%khuyến mãi%'
                   OR tenbaiviet LIKE '%giam gia%'
                   OR tenbaiviet LIKE '%giảm giá%'
                   OR tenbaiviet LIKE '%uu dai%'
                   OR tenbaiviet LIKE '%ưu đãi%'
                   OR tenbaiviet LIKE '%sale%'
                   OR tenbaiviet LIKE '%giảm%'
                   OR tenbaiviet LIKE '%giam%'
                   OR tomtat LIKE '%khuyen mai%'
                   OR tomtat LIKE '%khuyến mãi%'
                   OR tomtat LIKE '%giam gia%'
                   OR tomtat LIKE '%giảm giá%'
                ORDER BY id_bv DESC LIMIT 5";
        $result = mysqli_query($mysqli, $sql);
        $promos = [];
        while($row = mysqli_fetch_assoc($result)) {
            $promos[] = $row;
        }
        $response = ['success' => true, 'data' => $promos];
        break;
        
    case 'check_stock':
        // Kiểm tra tồn kho
        $product = trim((string)($_GET['product'] ?? ''));
        $product = chatbot_limit_text($product, 100);
        $likeProduct = '%' . $product . '%';
        $stmt = mysqli_prepare(
            $mysqli,
            'SELECT tensanpham, soluong
             FROM tbl_sanpham
             WHERE tensanpham LIKE ?
             LIMIT 1'
        );
        mysqli_stmt_bind_param($stmt, 's', $likeProduct);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result)) {
            $response = ['success' => true, 'data' => $row];
        } else {
            $response = ['success' => false, 'message' => 'Không tìm thấy món này'];
        }
        mysqli_stmt_close($stmt);
        break;
        
    case 'get_price_range':
        // Lấy khoảng giá
        $sql = "SELECT MIN(giasp) as min_price, MAX(giasp) as max_price, 
                AVG(giasp) as avg_price FROM tbl_sanpham WHERE soluong > 0";
        $result = mysqli_query($mysqli, $sql);
        $row = mysqli_fetch_assoc($result);
        $response = ['success' => true, 'data' => $row];
        break;

    case 'get_chat_history':
        // Lấy lịch sử chat (cho admin)
        $limit = max(1, min(200, intval($_GET['limit'] ?? 50)));
        $offset = max(0, intval($_GET['offset'] ?? 0));
        $type = $_GET['type'] ?? '';
        $whereSql = in_array($type, $allowedResponseTypes, true)
            ? "WHERE response_type = '" . mysqli_real_escape_string($mysqli, $type) . "'"
            : '';

        $sql = "SELECT * FROM tbl_chatbot_history $whereSql ORDER BY created_at DESC LIMIT $offset, $limit";
        $result = mysqli_query($mysqli, $sql);
        $history = [];
        while($row = mysqli_fetch_assoc($result)) {
            $history[] = $row;
        }
        // Count total
        $countResult = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM tbl_chatbot_history $whereSql");
        $total = mysqli_fetch_assoc($countResult)['total'];
        $response = ['success' => true, 'data' => $history, 'total' => intval($total)];
        break;

    case 'save_chat':
        // Lưu lịch sử chat từ frontend
        $data = chatbot_request_json();
        $userMsg = chatbot_limit_text(trim((string)($data['user_message'] ?? '')), 500);
        $botResp = chatbot_limit_text(trim((string)($data['bot_response'] ?? '')), 2000);
        $keyword = chatbot_limit_text(trim((string)($data['matched_keyword'] ?? '')), 100);
        $type = trim((string)($data['response_type'] ?? 'static'));
        if (!in_array($type, $allowedResponseTypes, true)) {
            $type = 'fallback';
        }
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $ua = chatbot_limit_text((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 255);

        if (!empty($userMsg) && !empty($botResp)) {
            $stmt = mysqli_prepare(
                $mysqli,
                'INSERT INTO tbl_chatbot_history (user_message, bot_response, matched_keyword, response_type, user_ip, user_agent)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            mysqli_stmt_bind_param($stmt, 'ssssss', $userMsg, $botResp, $keyword, $type, $ip, $ua);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $response = ['success' => true, 'id' => mysqli_insert_id($mysqli)];
        } else {
            $response = ['success' => false, 'message' => 'Missing data'];
        }
        break;

    case 'get_chat_stats':
        // Thống kê chat cho dashboard admin
        $stats = [];
        // Total conversations
        $r = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM tbl_chatbot_history");
        $stats['total_chats'] = mysqli_fetch_assoc($r)['total'];
        // Today's conversations
        $r = mysqli_query($mysqli, "SELECT COUNT(*) as today FROM tbl_chatbot_history WHERE DATE(created_at) = CURDATE()");
        $stats['today_chats'] = mysqli_fetch_assoc($r)['today'];
        // Most common keywords
        $r = mysqli_query($mysqli, "SELECT matched_keyword, COUNT(*) as count FROM tbl_chatbot_history WHERE matched_keyword IS NOT NULL GROUP BY matched_keyword ORDER BY count DESC LIMIT 10");
        $stats['top_keywords'] = [];
        while($row = mysqli_fetch_assoc($r)) {
            $stats['top_keywords'][] = $row;
        }
        // Response type breakdown
        $r = mysqli_query($mysqli, "SELECT response_type, COUNT(*) as count FROM tbl_chatbot_history GROUP BY response_type");
        $stats['response_types'] = [];
        while($row = mysqli_fetch_assoc($r)) {
            $stats['response_types'][] = $row;
        }
        $response = ['success' => true, 'data' => $stats];
        break;

    case 'get_chat_stats_by_date':
        // Thống kê theo ngày (7 ngày gần nhất)
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as count 
                FROM tbl_chatbot_history 
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(created_at) 
                ORDER BY date ASC";
        $result = mysqli_query($mysqli, $sql);
        $dates = [];
        $counts = [];
        while($row = mysqli_fetch_assoc($result)) {
            $dates[] = $row['date'];
            $counts[] = intval($row['count']);
        }
        $response = ['success' => true, 'data' => ['dates' => $dates, 'counts' => $counts]];
        break;
        
    default:
        $response = ['success' => false, 'message' => 'Unknown action'];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
