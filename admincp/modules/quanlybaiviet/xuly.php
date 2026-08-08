<?php
    include(__DIR__ . '/../../config/config.php');

    $tenbaiviet = $_POST['tenbaiviet'];
    
    $tomtat = $_POST['tomtat'];
    $noidung = $_POST['noidung'];

    $hinhanh = $_FILES['hinhanh']['name'];
    $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
    $hinhanh = time().'_'.$hinhanh;

    
    $danhmuc = $_POST['danhmuc'];
  
    if(isset($_POST['thembaiviet'])){
        $sql_them = "INSERT INTO tbl_baiviet(tenbaiviet,tomtat,noidung,hinhanh,id_danhmuc) 
            VALUE('".$tenbaiviet."','".$tomtat."','".$noidung."','".$hinhanh."','".$danhmuc."')";
        mysqli_query($mysqli,$sql_them);
        move_uploaded_file($hinhanh_tmp,__DIR__ . '/../../uploads/'.$hinhanh);
        header('Location:../../index.php?action=quanlybaiviet&query=them');
    }
    elseif(isset($_POST['suabaiviet'])){
        if(isset($_FILES['hinhanh']) && $_FILES['hinhanh']['size'] > 0){
            $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
            $hinhanh = $_FILES['hinhanh']['name'];
            move_uploaded_file($hinhanh_tmp,__DIR__ . '/../../uploads/'.$hinhanh);
            $sql = "SELECT * FROM tbl_baiviet WHERE id_bv = '$_GET[idbaiviet]' LIMIT 1";
            $query = mysqli_query($mysqli,$sql);
            while ($row = mysqli_fetch_array($query)){
                unlink(__DIR__ . '/../../uploads/'.$row['hinhanh']);
            }
        }
    
        $sql_update = "UPDATE tbl_baiviet SET tenbaiviet = '".$tenbaiviet."', tomtat = '".$tomtat."', noidung = '".$noidung."', id_danhmuc = '".$danhmuc."'";
        if(isset($_FILES['hinhanh']) && $_FILES['hinhanh']['size'] > 0){
            $sql_update .= ", hinhanh = '".$hinhanh."'";
        }
    
        $sql_update .= " WHERE id_bv = '$_GET[idbaiviet]'";
    
        mysqli_query($mysqli,$sql_update);

        header('Location:../../index.php?action=quanlybaiviet&query=them');
    }
    
    else{
        $id = $_GET['idbaiviet'];
        $sql = "SELECT * FROM tbl_baiviet WHERE id_bv = '$id' limit 1";
        $query = mysqli_query($mysqli,$sql);
        while ($row = mysqli_fetch_array($query)) {
            unlink(__DIR__ . '/../../uploads/'.$row['hinhanh']);
        }
        $sql_xoa = "DELETE FROM tbl_baiviet WHERE id_bv ='".$id."'";
        mysqli_query($mysqli,$sql_xoa);
        header('Location:../../index.php?action=quanlybaiviet&query=them');
    }
?>