<?php
include(__DIR__ . '/../../config/config.php');
require_once __DIR__ . '/../../includes/admin_security.php';

admin_require_login('../../login.php');
admin_require_valid_csrf();

function redirect_articles(): void
{
    header('Location:../../index.php?action=quanlybaiviet&query=them');
    exit;
}

function redirect_article_form(?int $articleId = null): void
{
    $url = '../../index.php?action=quanlybaiviet&query=them';
    if ($articleId !== null && $articleId > 0) {
        $url = '../../index.php?action=quanlybaiviet&query=sua&idbaiviet=' . $articleId;
    }

    header('Location:' . $url);
    exit;
}

function normalize_article_text(string $value): string
{
    return trim(preg_replace('/\s+/', ' ', $value) ?? '');
}

function find_article_image(mysqli $mysqli, int $id): ?string
{
    $stmt = mysqli_prepare($mysqli, 'SELECT hinhanh FROM tbl_baiviet WHERE id_bv = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $image);
    $found = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $found ? $image : null;
}

function article_category_exists(mysqli $mysqli, int $categoryId): bool
{
    if ($categoryId <= 0) {
        return false;
    }

    $stmt = mysqli_prepare($mysqli, 'SELECT id_baiviet FROM tbl_danhmucbaiviet WHERE id_baiviet = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $categoryId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    return $exists;
}

function article_title_exists(mysqli $mysqli, string $title, ?int $ignoreId = null): bool
{
    $normalizedTitle = mb_strtolower(normalize_article_text($title), 'UTF-8');
    $sql = 'SELECT id_bv
            FROM tbl_baiviet
            WHERE LOWER(TRIM(tenbaiviet)) = ?
              AND (? IS NULL OR id_bv <> ?)
            LIMIT 1';
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'sii', $normalizedTitle, $ignoreId, $ignoreId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    return $exists;
}

function validate_required_article_fields(mysqli $mysqli, string $title, int $categoryId, ?int $articleId = null): void
{
    $errors = [];

    if ($title === '') {
        $errors[] = 'tiêu đề bài viết';
    }

    if (!article_category_exists($mysqli, $categoryId)) {
        $errors[] = 'danh mục bài viết';
    }

    if ($errors === []) {
        return;
    }

    admin_set_flash('danger', 'Vui lòng kiểm tra lại: ' . implode(', ', $errors) . '.');
    redirect_article_form($articleId);
}

function validate_unique_article(mysqli $mysqli, string $title, ?int $ignoreId = null): void
{
    if (!article_title_exists($mysqli, $title, $ignoreId)) {
        return;
    }

    admin_set_flash('danger', 'Tiêu đề bài viết đã tồn tại. Vui lòng nhập tiêu đề khác để tránh trùng dữ liệu.');
    redirect_article_form($ignoreId);
}

try {
    if (isset($_POST['thembaiviet'])) {
        $tenbaiviet = normalize_article_text((string)($_POST['tenbaiviet'] ?? ''));
        $tomtat = trim($_POST['tomtat'] ?? '');
        $noidung = trim($_POST['noidung'] ?? '');
        $danhmuc = (int)($_POST['danhmuc'] ?? 0);

        validate_required_article_fields($mysqli, $tenbaiviet, $danhmuc);
        validate_unique_article($mysqli, $tenbaiviet);

        $hinhanh = save_uploaded_image($_FILES['hinhanh'] ?? [], 'posts') ?? '';

        $stmt = mysqli_prepare(
            $mysqli,
            'INSERT INTO tbl_baiviet(tenbaiviet, tomtat, noidung, hinhanh, id_danhmuc)
             VALUES (?, ?, ?, ?, ?)'
        );
        mysqli_stmt_bind_param($stmt, 'ssssi', $tenbaiviet, $tomtat, $noidung, $hinhanh, $danhmuc);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        redirect_articles();
    }

    if (isset($_POST['suabaiviet'])) {
        $id = (int)($_GET['idbaiviet'] ?? 0);
        $tenbaiviet = normalize_article_text((string)($_POST['tenbaiviet'] ?? ''));
        $tomtat = trim($_POST['tomtat'] ?? '');
        $noidung = trim($_POST['noidung'] ?? '');
        $danhmuc = (int)($_POST['danhmuc'] ?? 0);

        if ($id <= 0 || find_article_image($mysqli, $id) === null) {
            admin_set_flash('danger', 'Bài viết cần cập nhật không tồn tại.');
            redirect_articles();
        }

        validate_required_article_fields($mysqli, $tenbaiviet, $danhmuc, $id);
        validate_unique_article($mysqli, $tenbaiviet, $id);

        $oldImage = find_article_image($mysqli, $id);
        $newImage = save_uploaded_image($_FILES['hinhanh'] ?? [], 'posts');

        if ($newImage !== null) {
            $stmt = mysqli_prepare(
                $mysqli,
                'UPDATE tbl_baiviet
                 SET tenbaiviet = ?, tomtat = ?, noidung = ?, hinhanh = ?, id_danhmuc = ?
                 WHERE id_bv = ?'
            );
            mysqli_stmt_bind_param($stmt, 'ssssii', $tenbaiviet, $tomtat, $noidung, $newImage, $danhmuc, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            delete_uploaded_image($oldImage);
        } else {
            $stmt = mysqli_prepare(
                $mysqli,
                'UPDATE tbl_baiviet
                 SET tenbaiviet = ?, tomtat = ?, noidung = ?, id_danhmuc = ?
                 WHERE id_bv = ?'
            );
            mysqli_stmt_bind_param($stmt, 'sssii', $tenbaiviet, $tomtat, $noidung, $danhmuc, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        redirect_articles();
    }

    $id = (int)($_GET['idbaiviet'] ?? 0);
    $oldImage = find_article_image($mysqli, $id);

    $stmt = mysqli_prepare($mysqli, 'DELETE FROM tbl_baiviet WHERE id_bv = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    delete_uploaded_image($oldImage);

    redirect_articles();
} catch (RuntimeException $exception) {
    admin_set_flash('danger', $exception->getMessage());
    redirect_articles();
}
