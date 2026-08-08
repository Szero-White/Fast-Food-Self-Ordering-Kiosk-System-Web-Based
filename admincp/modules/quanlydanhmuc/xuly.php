<?php
include(__DIR__ . '/../../config/config.php');

$tenloaisp = trim($_POST['tendanhmuc'] ?? '');
$thutu = (int)($_POST['thutu'] ?? 0);

if (isset($_POST['themdanhmuc'])) {
    $stmt = mysqli_prepare($mysqli, 'INSERT INTO tbl_danhmuc(tendanhmuc, thutu) VALUES (?, ?)');
    mysqli_stmt_bind_param($stmt, 'si', $tenloaisp, $thutu);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} elseif (isset($_POST['suadanhmuc'])) {
    $id = (int)($_GET['iddanhmuc'] ?? 0);
    $stmt = mysqli_prepare($mysqli, 'UPDATE tbl_danhmuc SET tendanhmuc = ?, thutu = ? WHERE id_danhmuc = ?');
    mysqli_stmt_bind_param($stmt, 'sii', $tenloaisp, $thutu, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} else {
    $id = (int)($_GET['iddanhmuc'] ?? 0);
    $stmt = mysqli_prepare($mysqli, 'DELETE FROM tbl_danhmuc WHERE id_danhmuc = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header('Location:../../index.php?action=quanlydanhmucsp&query=them');
exit;
?>
