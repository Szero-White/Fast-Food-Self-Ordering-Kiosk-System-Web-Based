<?php
require_once __DIR__ . '/menu_product_card.php';
// Lấy danh mục cho bộ lọc
$sql_dm = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
$query_dm = mysqli_query($mysqli, $sql_dm);

// Lấy sản phẩm nổi bật
$sql_featured = "SELECT * FROM tbl_sanpham ORDER BY id_sanpham DESC LIMIT 4";
$query_featured = mysqli_query($mysqli, $sql_featured);

// Phan trang
$page = isset($_GET['trang']) ? max(1, (int)$_GET['trang']) : 1;
$begin = ($page - 1) * 8;

// Bộ lọc danh mục
$filter = "";
if (isset($_GET['danhmuc']) && $_GET['danhmuc'] != "") {
    $id_dm = (int)$_GET['danhmuc'];
    $filter = " AND tbl_sanpham.id_danhmuc = $id_dm";
}

// Tìm kiếm
$search = "";
if (isset($_GET['search']) && $_GET['search'] != "") {
    $keyword = mysqli_real_escape_string($mysqli, trim($_GET['search']));
    $search = " AND tbl_sanpham.tensanpham LIKE '%$keyword%'";
}

$sql_pro = "
    SELECT tbl_sanpham.*, tbl_danhmuc.tendanhmuc
    FROM tbl_sanpham
    INNER JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
    WHERE 1=1 $filter $search
    ORDER BY tbl_sanpham.id_sanpham DESC
    LIMIT $begin,8
";
$query_pro = mysqli_query($mysqli, $sql_pro);

// Đếm tổng sản phẩm
$count_query = mysqli_query($mysqli, "
    SELECT COUNT(*) AS total
    FROM tbl_sanpham
    INNER JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
    WHERE 1=1 $filter $search
");
$count_result = mysqli_fetch_assoc($count_query);
$row_count = $count_result['total'];
$total_pages = ceil($row_count / 8);
$isFilteredView = (isset($_GET['danhmuc']) && $_GET['danhmuc'] !== '') || (isset($_GET['search']) && trim((string) $_GET['search']) !== '');
$categorySections = [];

if (!$isFilteredView) {
    $sectionCategoryResult = mysqli_query($mysqli, "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC");

    while ($category = mysqli_fetch_assoc($sectionCategoryResult)) {
        $categoryId = (int) $category['id_danhmuc'];
        $sectionProductResult = mysqli_query(
            $mysqli,
            "SELECT tbl_sanpham.*, tbl_danhmuc.tendanhmuc
             FROM tbl_sanpham
             INNER JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
             WHERE tbl_sanpham.id_danhmuc = $categoryId
             ORDER BY tbl_sanpham.id_sanpham DESC
             LIMIT 8"
        );

        $sectionProducts = [];
        while ($product = mysqli_fetch_assoc($sectionProductResult)) {
            $sectionProducts[] = $product;
        }

        if ($sectionProducts !== []) {
            $categorySections[] = [
                'category' => $category,
                'products' => $sectionProducts,
            ];
        }
    }
}
