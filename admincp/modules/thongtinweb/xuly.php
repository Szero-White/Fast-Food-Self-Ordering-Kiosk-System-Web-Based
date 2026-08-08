<?php
include(__DIR__ . '/../../config/config.php');

function redirect_site_info(): void
{
    header('Location:../../index.php?action=quanlyweb&query=capnhat');
    exit;
}

function find_site_info_image(mysqli $mysqli, int $id): ?string
{
    $stmt = mysqli_prepare($mysqli, 'SELECT hinhanh FROM tbl_gioithieu WHERE id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $image);
    $found = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $found ? $image : null;
}

try {
    if (!isset($_POST['themlienhe'])) {
        redirect_site_info();
    }

    $id = 1;
    $noidung = trim($_POST['noidung'] ?? '');
    $oldImage = find_site_info_image($mysqli, $id);
    $newImage = save_uploaded_image($_FILES['hinhanh'] ?? [], 'site');

    if ($newImage !== null) {
        $stmt = mysqli_prepare(
            $mysqli,
            'INSERT INTO tbl_gioithieu (id, noidung, hinhanh)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE noidung = VALUES(noidung), hinhanh = VALUES(hinhanh)'
        );
        mysqli_stmt_bind_param($stmt, 'iss', $id, $noidung, $newImage);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        delete_uploaded_image($oldImage);
    } else {
        $stmt = mysqli_prepare(
            $mysqli,
            'INSERT INTO tbl_gioithieu (id, noidung, hinhanh)
             VALUES (?, ?, "")
             ON DUPLICATE KEY UPDATE noidung = VALUES(noidung)'
        );
        mysqli_stmt_bind_param($stmt, 'is', $id, $noidung);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    redirect_site_info();
} catch (RuntimeException $exception) {
    http_response_code(400);
    echo htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
}
