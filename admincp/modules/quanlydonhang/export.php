<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['dangnhap'])) {
    header('Location: ../../login.php');
    exit;
}

require_once __DIR__ . '/../../config/config.php';

function order_export_text(?string $value): string
{
    $text = html_entity_decode(strip_tags((string)$value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return trim(preg_replace('/\s+/', ' ', $text) ?? '');
}

function order_status_label(int $status): string
{
    return match ($status) {
        1 => 'Hoàn thành',
        2 => 'Đã hủy',
        default => 'Đang chọn',
    };
}

function payment_method_label(?string $method): string
{
    return match ($method) {
        'cash' => 'Tiền mặt',
        'transfer' => 'Chuyển khoản',
        'qr' => 'Quét mã QR',
        default => 'Chưa chọn',
    };
}

$filename = 'danh-sach-don-hang-' . date('Ymd-His') . '.csv';

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');
echo "\xEF\xBB\xBF";

fputcsv($output, [
    'ID',
    'Mã đơn',
    'Khách hàng',
    'Sản phẩm',
    'Tổng tiền',
    'Phương thức thanh toán',
    'Trạng thái',
    'Ngày đặt',
    'Ghi chú',
]);

$sql = "SELECT donhang.id,
               donhang.madon,
               donhang.tenkhach,
               donhang.tongtien,
               donhang.phuongthuc,
               donhang.trangthai,
               donhang.ngaydat,
               donhang.ghichu,
               COALESCE(chitiet.sanpham, '') AS sanpham
        FROM tbl_donhang AS donhang
        LEFT JOIN (
            SELECT id_donhang,
                   GROUP_CONCAT(CONCAT(ten_sanpham, ' x', soluong) SEPARATOR '; ') AS sanpham
            FROM tbl_chitietdonhang
            GROUP BY id_donhang
        ) AS chitiet ON chitiet.id_donhang = donhang.id
        ORDER BY donhang.ngaydat DESC";
$result = mysqli_query($mysqli, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['madon'],
        order_export_text($row['tenkhach'] ?? ''),
        order_export_text($row['sanpham'] ?? ''),
        number_format((float)$row['tongtien'], 0, ',', '.'),
        payment_method_label($row['phuongthuc'] ?? null),
        order_status_label((int)$row['trangthai']),
        $row['ngaydat'],
        order_export_text($row['ghichu'] ?? ''),
    ]);
}

fclose($output);
exit;
