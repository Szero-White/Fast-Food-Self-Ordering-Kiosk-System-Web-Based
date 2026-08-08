<?php
include(__DIR__ . '/../../config/config.php');

if(isset($_GET['iddonhang']) && $_GET['action']=='xoa'){
    // Xóa đơn hàng
    $id = $_GET['iddonhang'];
    $sql_xoa = "DELETE FROM tbl_donhang WHERE id = '$id'";
    mysqli_query($mysqli, $sql_xoa);
    $sql_xoa_chitiet = "DELETE FROM tbl_chitietdonhang WHERE id_donhang = '$id'";
    mysqli_query($mysqli, $sql_xoa_chitiet);
    header('Location:../../index.php?action=quanlydonhang&query=lietke');
}

if(isset($_POST['capnhatdonhang'])){
    // Cập nhật trạng thái đơn hàng
    $id = $_GET['iddonhang'];
    $trangthai = $_POST['trangthai'];
    $sql_update = "UPDATE tbl_donhang SET trangthai = '$trangthai' WHERE id = '$id'";
    mysqli_query($mysqli, $sql_update);
    header('Location:../../index.php?action=quanlydonhang&query=lietke');
}
?>