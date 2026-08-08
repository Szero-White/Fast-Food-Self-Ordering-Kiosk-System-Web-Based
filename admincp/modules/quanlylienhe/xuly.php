<?php
include(__DIR__ . '/../../config/config.php');

if(isset($_GET['idlienhe'])){
    $id = $_GET['idlienhe'];
    $sql_xoa = "DELETE FROM tbl_lienhe WHERE id_lienhe = '$id'";
    mysqli_query($mysqli, $sql_xoa);
    header('Location:../../index.php?action=quanlylienhe&query=lietke');
}
?>