<?php
include(__DIR__ . '/../../config/config.php');
require_once __DIR__ . '/../../includes/admin_security.php';

admin_require_login('../../login.php');
admin_require_valid_csrf();

function redirect_post_categories(?int $categoryId = null): void
{
    $url = '../../index.php?action=quanlydanhmucbaiviet&query=them';
    if ($categoryId !== null && $categoryId > 0) {
        $url = '../../index.php?action=quanlydanhmucbaiviet&query=sua&idbaiviet=' . $categoryId;
    }

    header('Location:' . $url);
    exit;
}

function normalize_post_category_text(string $value): string
{
    return trim(preg_replace('/\s+/', ' ', $value) ?? '');
}

function post_category_exists_by_name(mysqli $mysqli, string $name, ?int $ignoreId = null): bool
{
    $normalizedName = mb_strtolower(normalize_post_category_text($name), 'UTF-8');
    $sql = 'SELECT id_baiviet
            FROM tbl_danhmucbaiviet
            WHERE LOWER(TRIM(tendanhmucbv)) = ?
              AND (? IS NULL OR id_baiviet <> ?)
            LIMIT 1';
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'sii', $normalizedName, $ignoreId, $ignoreId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    return $exists;
}

function validate_post_category(mysqli $mysqli, string $name, ?int $ignoreId = null): void
{
    if ($name === '') {
        admin_set_flash('danger', 'Vui lòng nhập tên danh mục bài viết.');
        redirect_post_categories($ignoreId);
    }

    if (post_category_exists_by_name($mysqli, $name, $ignoreId)) {
        admin_set_flash('danger', 'Tên danh mục bài viết đã tồn tại. Vui lòng nhập tên danh mục khác.');
        redirect_post_categories($ignoreId);
    }
}

$tendanhmucbv = normalize_post_category_text((string)($_POST['tendanhmucbaiviet'] ?? ''));
$thutu = (int)($_POST['thutu'] ?? 0);

if (isset($_POST['themdanhmucbaiviet'])) {
    validate_post_category($mysqli, $tendanhmucbv);

    $stmt = mysqli_prepare($mysqli, 'INSERT INTO tbl_danhmucbaiviet(tendanhmucbv, thutu) VALUES (?, ?)');
    mysqli_stmt_bind_param($stmt, 'si', $tendanhmucbv, $thutu);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} elseif (isset($_POST['suadanhmucbaiviet'])) {
    $id = (int)($_GET['idbaiviet'] ?? 0);
    validate_post_category($mysqli, $tendanhmucbv, $id);

    $stmt = mysqli_prepare($mysqli, 'UPDATE tbl_danhmucbaiviet SET tendanhmucbv = ?, thutu = ? WHERE id_baiviet = ?');
    mysqli_stmt_bind_param($stmt, 'sii', $tendanhmucbv, $thutu, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} else {
    $id = (int)($_GET['idbaiviet'] ?? 0);
    $stmt = mysqli_prepare($mysqli, 'DELETE FROM tbl_danhmucbaiviet WHERE id_baiviet = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

redirect_post_categories();
?>
