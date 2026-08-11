<?php
declare(strict_types=1);

require_once __DIR__ . '/paths.php';

function chatbot_plain_text(?string $value, int $limit = 220): string
{
    $text = html_entity_decode(strip_tags((string)$value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = trim(preg_replace('/\s+/', ' ', $text) ?? '');

    if (mb_strlen($text, 'UTF-8') <= $limit) {
        return $text;
    }

    return mb_substr($text, 0, $limit, 'UTF-8') . '...';
}

function chatbot_context_products(mysqli $mysqli, int $limit): array
{
    $sql = "
        SELECT sanpham.tensanpham,
               sanpham.giasp,
               sanpham.soluong,
               sanpham.tomtat,
               danhmuc.tendanhmuc
        FROM tbl_sanpham AS sanpham
        LEFT JOIN tbl_danhmuc AS danhmuc ON danhmuc.id_danhmuc = sanpham.id_danhmuc
        ORDER BY sanpham.soluong DESC, sanpham.id_sanpham DESC
        LIMIT ?
    ";

    $stmt = mysqli_prepare($mysqli, $sql);
    if (!$stmt) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, 'i', $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $products = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $products;
}

function chatbot_context_promotions(mysqli $mysqli, int $limit): array
{
    $sql = "
        SELECT baiviet.tenbaiviet,
               baiviet.tomtat,
               danhmuc.tendanhmucbv
        FROM tbl_baiviet AS baiviet
        LEFT JOIN tbl_danhmucbaiviet AS danhmuc ON danhmuc.id_baiviet = baiviet.id_danhmuc
        ORDER BY baiviet.id_bv DESC
        LIMIT ?
    ";

    $stmt = mysqli_prepare($mysqli, $sql);
    if (!$stmt) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, 'i', $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $promotions = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $promotions[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $promotions;
}

function chatbot_context_about(mysqli $mysqli): string
{
    $result = mysqli_query($mysqli, 'SELECT noidung FROM tbl_gioithieu WHERE id = 1 LIMIT 1');
    $row = $result ? mysqli_fetch_assoc($result) : null;

    return chatbot_plain_text($row['noidung'] ?? '', 360);
}

function chatbot_build_project_context(mysqli $mysqli, array $config): string
{
    $products = chatbot_context_products($mysqli, (int)($config['max_products'] ?? 14));
    $promotions = chatbot_context_promotions($mysqli, (int)($config['max_promotions'] ?? 6));
    $about = chatbot_context_about($mysqli);

    $lines = [
        'THÔNG TIN HỆ THỐNG FASTFOOD KIOSK',
        '- Tên cửa hàng: FastFood',
        '- Địa chỉ: Quận 7, TP.HCM',
        '- Hotline: 1900 6099',
        '- Email: congtoan2k4@gmail.com',
        '- Giờ mở cửa: 9:00 - 22:00 hằng ngày',
        '- Thanh toán: chuyển khoản bằng QR hoặc tiền mặt tại quầy',
        '- Quy trình đặt món: chọn món, thêm vào giỏ hàng, chọn thanh toán, hoàn tất thanh toán, nhận mã đơn.',
    ];

    if ($about !== '') {
        $lines[] = '- Giới thiệu: ' . $about;
    }

    $lines[] = '';
    $lines[] = 'THỰC ĐƠN VÀ TỒN KHO';

    if ($products === []) {
        $lines[] = '- Chưa có dữ liệu sản phẩm.';
    } else {
        foreach ($products as $product) {
            $name = chatbot_plain_text($product['tensanpham'] ?? '', 80);
            $category = chatbot_plain_text($product['tendanhmuc'] ?? 'Chưa phân loại', 60);
            $summary = chatbot_plain_text($product['tomtat'] ?? '', 120);
            $price = number_format((float)($product['giasp'] ?? 0), 0, ',', '.') . 'đ';
            $stock = (int)($product['soluong'] ?? 0);

            $lines[] = "- {$name} | Danh mục: {$category} | Giá: {$price} | Còn: {$stock} phần | Mô tả: {$summary}";
        }
    }

    $lines[] = '';
    $lines[] = 'TIN TỨC/KHUYẾN MÃI';

    if ($promotions === []) {
        $lines[] = '- Chưa có dữ liệu bài viết khuyến mãi.';
    } else {
        foreach ($promotions as $promotion) {
            $title = chatbot_plain_text($promotion['tenbaiviet'] ?? '', 100);
            $category = chatbot_plain_text($promotion['tendanhmucbv'] ?? 'Tin tức', 60);
            $summary = chatbot_plain_text($promotion['tomtat'] ?? '', 150);

            $lines[] = "- {$title} | Danh mục: {$category} | Tóm tắt: {$summary}";
        }
    }

    return implode("\n", $lines);
}
