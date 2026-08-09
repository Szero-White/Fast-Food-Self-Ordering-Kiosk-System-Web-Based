<?php
require_once __DIR__ . '/news_article_card.php';

$selectedNewsCategoryId = isset($_GET['id']) ? max(0, (int) $_GET['id']) : 0;
$newsSections = [];
$pageTitle = 'Tin tức & Khuyến mãi';
$pageSubtitle = 'Cập nhật ưu đãi, sự kiện và thông tin mới nhất từ FastFood';

if ($selectedNewsCategoryId > 0) {
    $categoryStmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_danhmucbaiviet WHERE id_baiviet = ? LIMIT 1');
    mysqli_stmt_bind_param($categoryStmt, 'i', $selectedNewsCategoryId);
    mysqli_stmt_execute($categoryStmt);
    $category = mysqli_fetch_assoc(mysqli_stmt_get_result($categoryStmt));
    mysqli_stmt_close($categoryStmt);

    if ($category) {
        $articleStmt = mysqli_prepare(
            $mysqli,
            'SELECT tbl_baiviet.*, tbl_danhmucbaiviet.tendanhmucbv
             FROM tbl_baiviet
             INNER JOIN tbl_danhmucbaiviet ON tbl_baiviet.id_danhmuc = tbl_danhmucbaiviet.id_baiviet
             WHERE tbl_baiviet.id_danhmuc = ?
             ORDER BY tbl_baiviet.id_bv DESC'
        );
        mysqli_stmt_bind_param($articleStmt, 'i', $selectedNewsCategoryId);
        mysqli_stmt_execute($articleStmt);
        $articleResult = mysqli_stmt_get_result($articleStmt);

        $articles = [];
        while ($article = mysqli_fetch_assoc($articleResult)) {
            $articles[] = $article;
        }
        mysqli_stmt_close($articleStmt);

        $pageTitle = $category['tendanhmucbv'];
        $newsSections[] = [
            'category' => $category,
            'articles' => $articles,
        ];
    }
} else {
    $categoryResult = mysqli_query($mysqli, 'SELECT * FROM tbl_danhmucbaiviet ORDER BY thutu ASC, id_baiviet DESC');

    while ($category = mysqli_fetch_assoc($categoryResult)) {
        $categoryId = (int) $category['id_baiviet'];
        $articleResult = mysqli_query(
            $mysqli,
            "SELECT tbl_baiviet.*, tbl_danhmucbaiviet.tendanhmucbv
             FROM tbl_baiviet
             INNER JOIN tbl_danhmucbaiviet ON tbl_baiviet.id_danhmuc = tbl_danhmucbaiviet.id_baiviet
             WHERE tbl_baiviet.id_danhmuc = $categoryId
             ORDER BY tbl_baiviet.id_bv DESC"
        );

        $articles = [];
        while ($article = mysqli_fetch_assoc($articleResult)) {
            $articles[] = $article;
        }

        if ($articles !== []) {
            $newsSections[] = [
                'category' => $category,
                'articles' => $articles,
            ];
        }
    }
}
