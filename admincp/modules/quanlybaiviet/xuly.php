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

try {
    if (isset($_POST['thembaiviet'])) {
        $tenbaiviet = trim($_POST['tenbaiviet'] ?? '');
        $tomtat = trim($_POST['tomtat'] ?? '');
        $noidung = trim($_POST['noidung'] ?? '');
        $danhmuc = (int)($_POST['danhmuc'] ?? 0);
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
        $tenbaiviet = trim($_POST['tenbaiviet'] ?? '');
        $tomtat = trim($_POST['tomtat'] ?? '');
        $noidung = trim($_POST['noidung'] ?? '');
        $danhmuc = (int)($_POST['danhmuc'] ?? 0);
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
    http_response_code(400);
    echo htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
}
