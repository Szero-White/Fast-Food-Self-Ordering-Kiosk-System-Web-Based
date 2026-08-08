<?php
    include(__DIR__ . '/../../config/config.php');

    $tensanpham = $_POST['tensanpham'];
    $masp = $_POST['masp'];
    $giasp = $_POST['giasp'];
    $soluong = $_POST['soluong'];
    $tomtat = $_POST['tomtat'];
    $noidung = $_POST['noidung'];

    $hinhanh = $_FILES['hinhanh']['name'];
    $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
    $hinhanh = time().'_'.$hinhanh;

    $thutu = $_POST['thutu'];
    $danhmuc = $_POST['danhmuc'];
  
    if(isset($_POST['themsanpham'])){
        $sql_them = "INSERT INTO tbl_sanpham(tensanpham,masp,giasp,soluong,tomtat,noidung,hinhanh,thutu,id_danhmuc) 
            VALUE('".$tensanpham."','".$masp."','".$giasp."','".$soluong."','".$tomtat."','".$noidung."','".$hinhanh."','".$thutu."','".$danhmuc."')";
        mysqli_query($mysqli,$sql_them);
        move_uploaded_file($hinhanh_tmp,__DIR__ . '/../../uploads/'.$hinhanh);
        header('Location:../../index.php?action=quanlymonan&query=them');
    }
    elseif(isset($_POST['suasanpham'])){
        $hinhanh_new = null;
        if(isset($_FILES['hinhanh']) && $_FILES['hinhanh']['size'] > 0){
            $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
            $hinhanh_name = $_FILES['hinhanh']['name'];
            $hinhanh_new = time().'_'.$hinhanh_name;  // Thêm timestamp
            
            // Upload file mới
            if(move_uploaded_file($hinhanh_tmp,__DIR__ . '/../../uploads/'.$hinhanh_new)){
                // Xóa file cũ
                $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham = '$_GET[idsanpham]' LIMIT 1";
                $query = mysqli_query($mysqli,$sql);
                while ($row = mysqli_fetch_array($query)){
                    $old_file = __DIR__ . '/../../uploads/'.$row['hinhanh'];
                    if(file_exists($old_file) && $row['hinhanh']){
                        unlink($old_file);
                    }
                }
            }
        }
    
        $sql_update = "UPDATE tbl_sanpham SET tensanpham = '".$tensanpham."', masp = '".$masp."', giasp = '".$giasp."', soluong = '".$soluong."', tomtat = '".$tomtat."', noidung = '".$noidung."', thutu = '".$thutu."', id_danhmuc = '".$danhmuc."'";
        if($hinhanh_new){
            $sql_update .= ", hinhanh = '".$hinhanh_new."'";
        }
    
        $sql_update .= " WHERE id_sanpham = '$_GET[idsanpham]'";
    
        mysqli_query($mysqli,$sql_update);

        header('Location:../../index.php?action=quanlymonan&query=them');
    }
    
    else{
        $id = $_GET['idsanpham'];
        $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham = '$id' limit 1";
        $query = mysqli_query($mysqli,$sql);
        while ($row = mysqli_fetch_array($query)) {
            unlink(__DIR__ . '/../../uploads/'.$row['hinhanh']);
        }
        $sql_xoa = "DELETE FROM tbl_sanpham WHERE id_sanpham ='".$id."'";
        mysqli_query($mysqli,$sql_xoa);
        header('Location:../../index.php?action=quanlymonan&query=them');
    }
?>