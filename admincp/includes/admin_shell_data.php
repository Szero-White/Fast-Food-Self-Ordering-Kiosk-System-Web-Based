<?php
declare(strict_types=1);

if (!function_exists('admin_count_rows')) {
    function admin_count_rows(mysqli $mysqli, string $sql): int
    {
        $result = mysqli_query($mysqli, $sql);
        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        return (int)($row['tong'] ?? 0);
    }
}

if (!function_exists('admin_fetch_rows')) {
    function admin_fetch_rows(mysqli $mysqli, string $sql): array
    {
        $result = mysqli_query($mysqli, $sql);
        if (!$result) {
            return [];
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        mysqli_free_result($result);

        return $rows;
    }
}

ensure_order_notification_columns($mysqli);

if (
    ($_GET['action'] ?? '') === 'quanlydonhang'
    && ($_GET['query'] ?? '') === 'xem'
    && isset($_GET['iddonhang'])
) {
    mark_order_seen($mysqli, (int)$_GET['iddonhang']);
}

$adminName = htmlspecialchars((string)$_SESSION['dangnhap'], ENT_QUOTES, 'UTF-8');
$unreadContacts = admin_count_rows($mysqli, "SELECT COUNT(*) AS tong FROM tbl_lienhe WHERE trangthai = 'chua_xem'");
$newPaidOrders = admin_count_rows($mysqli, 'SELECT COUNT(*) AS tong FROM tbl_donhang WHERE trangthai = 1 AND admin_seen = 0');
$newPaidOrderItems = admin_fetch_rows(
    $mysqli,
    'SELECT id, madon, tongtien, phuongthuc, ngaydat
     FROM tbl_donhang
     WHERE trangthai = 1 AND admin_seen = 0
     ORDER BY ngaydat DESC
     LIMIT 3'
);
$lowStockProducts = admin_count_rows($mysqli, 'SELECT COUNT(*) AS tong FROM tbl_sanpham WHERE soluong <= 5');
$lowStockItems = admin_fetch_rows(
    $mysqli,
    'SELECT id_sanpham, tensanpham, masp, soluong
     FROM tbl_sanpham
     WHERE soluong <= 5
     ORDER BY soluong ASC, id_sanpham DESC
     LIMIT 3'
);
$uncategorizedProducts = admin_count_rows(
    $mysqli,
    'SELECT COUNT(*) AS tong
     FROM tbl_sanpham
     LEFT JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
     WHERE tbl_danhmuc.id_danhmuc IS NULL'
);
$activeBanners = admin_count_rows($mysqli, 'SELECT COUNT(*) AS tong FROM tbl_banner WHERE is_active = 1');
$missingActiveBanner = $activeBanners === 0 ? 1 : 0;
$systemAlertCount = $newPaidOrders + $lowStockProducts + $uncategorizedProducts + $missingActiveBanner;
$adminLogoUrl = site_asset_url($mysqli, 'admin_logo');
$adminFaviconUrl = site_asset_url($mysqli, 'site_favicon');
$adminCssVersion = filemtime(__DIR__ . '/../css_admin/admin_style.css');
$crudAdminCssVersion = filemtime(__DIR__ . '/../css_admin/pages/crud-admin.css');
$adminJsVersion = filemtime(__DIR__ . '/../js_admin/admin_script.js');
$crudAdminJsVersion = filemtime(__DIR__ . '/../js_admin/pages/crud-admin.js');
