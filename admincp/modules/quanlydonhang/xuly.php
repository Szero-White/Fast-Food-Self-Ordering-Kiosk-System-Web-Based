<?php
include(__DIR__ . '/../../config/config.php');

if (isset($_GET['iddonhang']) && ($_GET['action'] ?? '') === 'xoa') {
    // Xóa đơn hàng
    $id = (int)$_GET['iddonhang'];

    $stmt = mysqli_prepare($mysqli, 'DELETE FROM tbl_chitietdonhang WHERE id_donhang = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($mysqli, 'DELETE FROM tbl_donhang WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('Location:../../index.php?action=quanlydonhang&query=lietke');
    exit;
}

if (isset($_POST['capnhatdonhang'])) {
    // Cập nhật trạng thái đơn hàng
    $id = (int)($_GET['iddonhang'] ?? 0);
    $trangthai = (int)($_POST['trangthai'] ?? 0);
    $stmt = mysqli_prepare($mysqli, 'UPDATE tbl_donhang SET trangthai = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'ii', $trangthai, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('Location:../../index.php?action=quanlydonhang&query=lietke');
    exit;
}
?>
