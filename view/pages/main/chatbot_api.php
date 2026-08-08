<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include(__DIR__ . '/../../config/config.php');

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

$action = $_GET['action'] ?? '';
$response = ['success' => false, 'data' => null, 'message' => ''];

switch($action) {
    case 'get_products':
        // Lấy danh sách sản phẩm
        $sql = "SELECT tensanpham, giasp, soluong, tomtat FROM tbl_sanpham WHERE soluong > 0 ORDER BY id_sanpham DESC LIMIT 10";
        $result = mysqli_query($mysqli, $sql);
        $products = [];
        while($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        $response = ['success' => true, 'data' => $products, 'count' => count($products)];
        break;
        
    case 'search_product':
        // Tìm sản phẩm theo tên
        $keyword = mysqli_real_escape_string($mysqli, $_GET['keyword'] ?? '');
        $sql = "SELECT tensanpham, giasp, soluong FROM tbl_sanpham 
                WHERE tensanpham LIKE '%$keyword%' AND soluong > 0 
                LIMIT 5";
        $result = mysqli_query($mysqli, $sql);
        $products = [];
        while($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        $response = ['success' => true, 'data' => $products, 'keyword' => $keyword];
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
        $product = mysqli_real_escape_string($mysqli, $_GET['product'] ?? '');
        $sql = "SELECT tensanpham, soluong FROM tbl_sanpham 
                WHERE tensanpham LIKE '%$product%' LIMIT 1";
        $result = mysqli_query($mysqli, $sql);
        if($row = mysqli_fetch_assoc($result)) {
            $response = ['success' => true, 'data' => $row];
        } else {
            $response = ['success' => false, 'message' => 'Không tìm thấy món này'];
        }
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
        $limit = intval($_GET['limit'] ?? 50);
        $offset = intval($_GET['offset'] ?? 0);
        $sql = "SELECT * FROM tbl_chatbot_history ORDER BY created_at DESC LIMIT $offset, $limit";
        $result = mysqli_query($mysqli, $sql);
        $history = [];
        while($row = mysqli_fetch_assoc($result)) {
            $history[] = $row;
        }
        // Count total
        $countResult = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM tbl_chatbot_history");
        $total = mysqli_fetch_assoc($countResult)['total'];
        $response = ['success' => true, 'data' => $history, 'total' => intval($total)];
        break;

    case 'save_chat':
        // Lưu lịch sử chat từ frontend
        $data = json_decode(file_get_contents('php://input'), true);
        $userMsg = mysqli_real_escape_string($mysqli, $data['user_message'] ?? '');
        $botResp = mysqli_real_escape_string($mysqli, $data['bot_response'] ?? '');
        $keyword = mysqli_real_escape_string($mysqli, $data['matched_keyword'] ?? '');
        $type = mysqli_real_escape_string($mysqli, $data['response_type'] ?? 'static');
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $ua = mysqli_real_escape_string($mysqli, $_SERVER['HTTP_USER_AGENT'] ?? '');

        if (!empty($userMsg) && !empty($botResp)) {
            $sql = "INSERT INTO tbl_chatbot_history (user_message, bot_response, matched_keyword, response_type, user_ip, user_agent)
                    VALUES ('$userMsg', '$botResp', '$keyword', '$type', '$ip', '$ua')";
            mysqli_query($mysqli, $sql);
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
