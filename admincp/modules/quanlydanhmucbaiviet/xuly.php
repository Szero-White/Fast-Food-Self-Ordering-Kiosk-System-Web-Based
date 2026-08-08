<?php
    include(__DIR__ . '/../../config/config.php');

    $tendanhmucbv = $_POST['tendanhmucbaiviet'];
    $thutu = $_POST['thutu'];

    if(isset($_POST['themdanhmucbaiviet'])){
        $sql_them = "INSERT INTO tbl_danhmucbaiviet(tendanhmucbv,thutu) 
            VALUES('".$tendanhmucbv."','".$thutu."')";
        mysqli_query($mysqli,$sql_them);
        header('Location:../../index.php?action=quanlydanhmucbaiviet&query=them');
    }
    elseif(isset($_POST['suadanhmucbaiviet'])){
        $sql_update = "UPDATE tbL_danhmucbaiviet SET tendanhmucbv = '".$tendanhmucbv."',thutu = '".$thutu."' WHERE id_baiviet = '$_GET[idbaiviet]'";
        mysqli_query($mysqli,$sql_update);
        header('Location:../../index.php?action=quanlydanhmucbaiviet&query=them');
    }
    else{
        $id = $_GET['idbaiviet'];
        $sql_xoa = "DELETE FROM tbl_danhmucbaiviet WHERE id_baiviet ='".$id."'";
        mysqli_query($mysqli,$sql_xoa);
        header('Location:../../index.php?action=quanlydanhmucbaiviet&query=them');
    }
?>