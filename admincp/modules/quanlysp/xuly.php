<?php
include(__DIR__ . '/../../config/config.php');

function redirect_products(): void
{
    header('Location:../../index.php?action=quanlymonan&query=them');
    exit;
}

function find_product_image(mysqli $mysqli, int $id): ?string
{
    $stmt = mysqli_prepare($mysqli, 'SELECT hinhanh FROM tbl_sanpham WHERE id_sanpham = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $image);
    $found = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $found ? $image : null;
}

try {
    if (isset($_POST['themsanpham'])) {
        $tensanpham = trim($_POST['tensanpham'] ?? '');
        $masp = trim($_POST['masp'] ?? '');
        $giasp = trim($_POST['giasp'] ?? '');
        $soluong = (int)($_POST['soluong'] ?? 0);
        $tomtat = trim($_POST['tomtat'] ?? '');
        $noidung = trim($_POST['noidung'] ?? '');
        $thutu = (int)($_POST['thutu'] ?? 0);
        $danhmuc = (int)($_POST['danhmuc'] ?? 0);
        $hinhanh = save_uploaded_image($_FILES['hinhanh'] ?? [], 'products') ?? '';

        $stmt = mysqli_prepare(
            $mysqli,
            'INSERT INTO tbl_sanpham(tensanpham, masp, giasp, soluong, tomtat, noidung, hinhanh, thutu, id_danhmuc)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        mysqli_stmt_bind_param($stmt, 'sssisssii', $tensanpham, $masp, $giasp, $soluong, $tomtat, $noidung, $hinhanh, $thutu, $danhmuc);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        redirect_products();
    }

    if (isset($_POST['suasanpham'])) {
        $id = (int)($_GET['idsanpham'] ?? 0);
        $tensanpham = trim($_POST['tensanpham'] ?? '');
        $masp = trim($_POST['masp'] ?? '');
        $giasp = trim($_POST['giasp'] ?? '');
        $soluong = (int)($_POST['soluong'] ?? 0);
        $tomtat = trim($_POST['tomtat'] ?? '');
        $noidung = trim($_POST['noidung'] ?? '');
        $thutu = (int)($_POST['thutu'] ?? 0);
        $danhmuc = (int)($_POST['danhmuc'] ?? 0);
        $oldImage = find_product_image($mysqli, $id);
        $newImage = save_uploaded_image($_FILES['hinhanh'] ?? [], 'products');

        if ($newImage !== null) {
            $stmt = mysqli_prepare(
                $mysqli,
                'UPDATE tbl_sanpham
                 SET tensanpham = ?, masp = ?, giasp = ?, soluong = ?, tomtat = ?, noidung = ?, hinhanh = ?, thutu = ?, id_danhmuc = ?
                 WHERE id_sanpham = ?'
            );
            mysqli_stmt_bind_param($stmt, 'sssisssiii', $tensanpham, $masp, $giasp, $soluong, $tomtat, $noidung, $newImage, $thutu, $danhmuc, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            delete_uploaded_image($oldImage);
        } else {
            $stmt = mysqli_prepare(
                $mysqli,
                'UPDATE tbl_sanpham
                 SET tensanpham = ?, masp = ?, giasp = ?, soluong = ?, tomtat = ?, noidung = ?, thutu = ?, id_danhmuc = ?
                 WHERE id_sanpham = ?'
            );
            mysqli_stmt_bind_param($stmt, 'sssissiii', $tensanpham, $masp, $giasp, $soluong, $tomtat, $noidung, $thutu, $danhmuc, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        redirect_products();
    }

    $id = (int)($_GET['idsanpham'] ?? 0);
    $oldImage = find_product_image($mysqli, $id);

    $stmt = mysqli_prepare($mysqli, 'DELETE FROM tbl_sanpham WHERE id_sanpham = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    delete_uploaded_image($oldImage);

    redirect_products();
} catch (RuntimeException $exception) {
    http_response_code(400);
    echo htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
}
