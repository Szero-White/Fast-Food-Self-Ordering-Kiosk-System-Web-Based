<?php
require_once __DIR__ . '/menu_product_card.php';

function home_fetch_products(mysqli $mysqli, ?int $categoryId, string $keyword, int $offset, int $limit): mysqli_result|false
{
    $where = [];
    $types = '';
    $params = [];

    if ($categoryId !== null && $categoryId > 0) {
        $where[] = 'tbl_sanpham.id_danhmuc = ?';
        $types .= 'i';
        $params[] = $categoryId;
    }

    if ($keyword !== '') {
        $where[] = 'tbl_sanpham.tensanpham LIKE ?';
        $types .= 's';
        $params[] = '%' . $keyword . '%';
    }

    $whereSql = $where === [] ? '1=1' : implode(' AND ', $where);
    $sql = "
        SELECT tbl_sanpham.*, tbl_danhmuc.tendanhmuc
        FROM tbl_sanpham
        INNER JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
        WHERE $whereSql
        ORDER BY tbl_sanpham.id_sanpham DESC
        LIMIT ?, ?
    ";

    $stmt = mysqli_prepare($mysqli, $sql);
    if (!$stmt) {
        return false;
    }

    $types .= 'ii';
    $params[] = $offset;
    $params[] = $limit;
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
}

function home_count_products(mysqli $mysqli, ?int $categoryId, string $keyword): int
{
    $where = [];
    $types = '';
    $params = [];

    if ($categoryId !== null && $categoryId > 0) {
        $where[] = 'tbl_sanpham.id_danhmuc = ?';
        $types .= 'i';
        $params[] = $categoryId;
    }

    if ($keyword !== '') {
        $where[] = 'tbl_sanpham.tensanpham LIKE ?';
        $types .= 's';
        $params[] = '%' . $keyword . '%';
    }

    $whereSql = $where === [] ? '1=1' : implode(' AND ', $where);
    $stmt = mysqli_prepare(
        $mysqli,
        "SELECT COUNT(*) AS total
         FROM tbl_sanpham
         INNER JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
         WHERE $whereSql"
    );

    if (!$stmt) {
        return 0;
    }

    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = $result ? mysqli_fetch_assoc($result) : [];
    mysqli_stmt_close($stmt);

    return (int)($row['total'] ?? 0);
}

// Lấy danh mục cho bộ lọc
$sql_dm = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
$query_dm = mysqli_query($mysqli, $sql_dm);

// Lấy sản phẩm nổi bật
$sql_featured = "SELECT * FROM tbl_sanpham ORDER BY id_sanpham DESC LIMIT 4";
$query_featured = mysqli_query($mysqli, $sql_featured);

// Phân trang
$page = isset($_GET['trang']) ? max(1, (int)$_GET['trang']) : 1;
$begin = ($page - 1) * 8;

// Bộ lọc danh mục
$id_dm = isset($_GET['danhmuc']) && $_GET['danhmuc'] !== '' ? (int)$_GET['danhmuc'] : null;

// Tìm kiếm
$keyword = isset($_GET['search']) ? trim((string)$_GET['search']) : '';
$keyword = function_exists('mb_substr') ? mb_substr($keyword, 0, 100, 'UTF-8') : substr($keyword, 0, 100);

$query_pro = home_fetch_products($mysqli, $id_dm, $keyword, $begin, 8);

// Đếm tổng sản phẩm
$row_count = home_count_products($mysqli, $id_dm, $keyword);
$total_pages = ceil($row_count / 8);
$isFilteredView = (isset($_GET['danhmuc']) && $_GET['danhmuc'] !== '') || (isset($_GET['search']) && trim((string) $_GET['search']) !== '');
$categorySections = [];

if (!$isFilteredView) {
    $sectionCategoryResult = mysqli_query($mysqli, "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC");

    $allCategories = [];
    while ($category = mysqli_fetch_assoc($sectionCategoryResult)) {
        $allCategories[] = $category;
    }
    mysqli_free_result($sectionCategoryResult);

    foreach ($allCategories as $category) {
        $categoryId = (int) $category['id_danhmuc'];
        $sectionProductResult = home_fetch_products($mysqli, $categoryId, '', 0, 8);

        $sectionProducts = [];
        while ($sectionProductResult && $product = mysqli_fetch_assoc($sectionProductResult)) {
            $sectionProducts[] = $product;
        }

        if ($sectionProducts !== []) {
            $total = home_count_products($mysqli, $categoryId, '');
            $categorySections[] = [
                'category' => $category,
                'products' => $sectionProducts,
                'total'    => $total,
                'hasMore'  => $total > 8,
            ];
        }
    }
}
