<?php
require_once __DIR__ . '/home/menu_product_card.php';

const DANHMUC_PAGE_SIZE = 8;

$categoryId = isset($_GET['id']) ? max(0, (int) $_GET['id']) : 0;
$currentPage = isset($_GET['trang']) ? max(1, (int) $_GET['trang']) : 1;
$offset = ($currentPage - 1) * DANHMUC_PAGE_SIZE;

$category = null;
$products = [];
$totalProducts = 0;
$totalPages = 1;

if ($categoryId > 0) {
    $stmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_danhmuc WHERE id_danhmuc = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $categoryId);
    mysqli_stmt_execute($stmt);
    $category = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
}

if ($category) {
    // Đếm tổng để tính phân trang
    $countStmt = mysqli_prepare(
        $mysqli,
        'SELECT COUNT(*) AS total
         FROM tbl_sanpham
         WHERE id_danhmuc = ?'
    );
    mysqli_stmt_bind_param($countStmt, 'i', $categoryId);
    mysqli_stmt_execute($countStmt);
    $totalProducts = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($countStmt))['total'] ?? 0);
    mysqli_stmt_close($countStmt);

    $totalPages = max(1, (int) ceil($totalProducts / DANHMUC_PAGE_SIZE));
    $currentPage = min($currentPage, $totalPages);
    $offset = ($currentPage - 1) * DANHMUC_PAGE_SIZE;

    $productStmt = mysqli_prepare(
        $mysqli,
        'SELECT tbl_sanpham.*, tbl_danhmuc.tendanhmuc
         FROM tbl_sanpham
         INNER JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
         WHERE tbl_sanpham.id_danhmuc = ?
         ORDER BY tbl_sanpham.id_sanpham DESC
         LIMIT ?, ?'
    );
    $pageSize = DANHMUC_PAGE_SIZE;
    mysqli_stmt_bind_param($productStmt, 'iii', $categoryId, $offset, $pageSize);
    mysqli_stmt_execute($productStmt);
    $productResult = mysqli_stmt_get_result($productStmt);

    while ($product = mysqli_fetch_assoc($productResult)) {
        $products[] = $product;
    }

    mysqli_stmt_close($productStmt);
}
