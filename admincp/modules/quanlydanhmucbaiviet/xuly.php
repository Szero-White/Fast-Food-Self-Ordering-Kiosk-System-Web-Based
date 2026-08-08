<?php
include(__DIR__ . '/../../config/config.php');

$tendanhmucbv = trim($_POST['tendanhmucbaiviet'] ?? '');
$thutu = (int)($_POST['thutu'] ?? 0);

if (isset($_POST['themdanhmucbaiviet'])) {
    $stmt = mysqli_prepare($mysqli, 'INSERT INTO tbl_danhmucbaiviet(tendanhmucbv, thutu) VALUES (?, ?)');
    mysqli_stmt_bind_param($stmt, 'si', $tendanhmucbv, $thutu);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} elseif (isset($_POST['suadanhmucbaiviet'])) {
    $id = (int)($_GET['idbaiviet'] ?? 0);
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

header('Location:../../index.php?action=quanlydanhmucbaiviet&query=them');
exit;
?>
