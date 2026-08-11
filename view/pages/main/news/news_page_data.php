<?php
require_once __DIR__ . '/news_article_card.php';

const NEWS_PAGE_SIZE = 8;

$selectedNewsCategoryId = isset($_GET['id']) ? max(0, (int) $_GET['id']) : 0;
$currentPage = isset($_GET['trang']) ? max(1, (int) $_GET['trang']) : 1;

$newsSections = [];
$pageTitle    = 'Tin tức & Khuyến mãi';
$pageSubtitle = 'Cập nhật ưu đãi, sự kiện và thông tin mới nhất từ FastFood';
$totalPages   = 1;

if ($selectedNewsCategoryId > 0) {
    // ── Single-category view — paginated ──────────────────────────────────
    $stmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_danhmucbaiviet WHERE id_baiviet = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $selectedNewsCategoryId);
    mysqli_stmt_execute($stmt);
    $category = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ($category) {
        // Count total articles
        $countStmt = mysqli_prepare(
            $mysqli,
            'SELECT COUNT(*) AS total FROM tbl_baiviet WHERE id_danhmuc = ?'
        );
        mysqli_stmt_bind_param($countStmt, 'i', $selectedNewsCategoryId);
        mysqli_stmt_execute($countStmt);
        $totalArticles = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($countStmt))['total'] ?? 0);
        mysqli_stmt_close($countStmt);

        $totalPages  = max(1, (int) ceil($totalArticles / NEWS_PAGE_SIZE));
        $currentPage = min($currentPage, $totalPages);
        $offset      = ($currentPage - 1) * NEWS_PAGE_SIZE;
        $pageSize    = NEWS_PAGE_SIZE;

        $articleStmt = mysqli_prepare(
            $mysqli,
            'SELECT tbl_baiviet.*, tbl_danhmucbaiviet.tendanhmucbv
             FROM tbl_baiviet
             INNER JOIN tbl_danhmucbaiviet ON tbl_baiviet.id_danhmuc = tbl_danhmucbaiviet.id_baiviet
             WHERE tbl_baiviet.id_danhmuc = ?
             ORDER BY tbl_baiviet.id_bv DESC
             LIMIT ?, ?'
        );
        mysqli_stmt_bind_param($articleStmt, 'iii', $selectedNewsCategoryId, $offset, $pageSize);
        mysqli_stmt_execute($articleStmt);
        $articleResult = mysqli_stmt_get_result($articleStmt);

        $articles = [];
        while ($article = mysqli_fetch_assoc($articleResult)) {
            $articles[] = $article;
        }
        mysqli_stmt_close($articleStmt);

        $pageTitle    = htmlspecialchars($category['tendanhmucbv'], ENT_QUOTES, 'UTF-8');
        $pageSubtitle = $totalArticles . ' bài viết';
        $newsSections[] = [
            'category'    => $category,
            'articles'    => $articles,
            'totalPages'  => $totalPages,
            'currentPage' => $currentPage,
        ];
    }
} else {
    // ── All-categories view — 8 articles per section, link to full category ─
    // Fetch all categories first and close the result set before running
    // per-category prepared statements (avoids "Commands out of sync").
    $categoryResult = mysqli_query(
        $mysqli,
        'SELECT * FROM tbl_danhmucbaiviet ORDER BY thutu ASC, id_baiviet DESC'
    );

    $allCategories = [];
    while ($category = mysqli_fetch_assoc($categoryResult)) {
        $allCategories[] = $category;
    }
    mysqli_free_result($categoryResult);

    $pageSize = NEWS_PAGE_SIZE;

    foreach ($allCategories as $category) {
        $categoryId = (int) $category['id_baiviet'];

        $articleStmt = mysqli_prepare(
            $mysqli,
            'SELECT tbl_baiviet.*, tbl_danhmucbaiviet.tendanhmucbv
             FROM tbl_baiviet
             INNER JOIN tbl_danhmucbaiviet ON tbl_baiviet.id_danhmuc = tbl_danhmucbaiviet.id_baiviet
             WHERE tbl_baiviet.id_danhmuc = ?
             ORDER BY tbl_baiviet.id_bv DESC
             LIMIT ?'
        );
        mysqli_stmt_bind_param($articleStmt, 'ii', $categoryId, $pageSize);
        mysqli_stmt_execute($articleStmt);
        $articleResult = mysqli_stmt_get_result($articleStmt);

        $articles = [];
        while ($article = mysqli_fetch_assoc($articleResult)) {
            $articles[] = $article;
        }
        mysqli_stmt_close($articleStmt);

        // Count to know whether a "xem thêm" link is needed
        $countStmt = mysqli_prepare(
            $mysqli,
            'SELECT COUNT(*) AS total FROM tbl_baiviet WHERE id_danhmuc = ?'
        );
        mysqli_stmt_bind_param($countStmt, 'i', $categoryId);
        mysqli_stmt_execute($countStmt);
        $total = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($countStmt))['total'] ?? 0);
        mysqli_stmt_close($countStmt);

        if ($articles !== []) {
            $newsSections[] = [
                'category'    => $category,
                'articles'    => $articles,
                'totalPages'  => 1,
                'currentPage' => 1,
                'hasMore'     => $total > NEWS_PAGE_SIZE,
                'total'       => $total,
            ];
        }
    }
}
