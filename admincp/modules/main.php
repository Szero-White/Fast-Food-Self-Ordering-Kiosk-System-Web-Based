<div class="clear"></div>
<div class="main">
    <?php
        if(isset($_GET['action']) && $_GET['query']){
            $tam = $_GET['action'];
            $query = $_GET['query'];
        }else{
            $tam = "";
            $query = "";
        }
        if($tam=='quanlydanhmucsp' && $query=="them"){
            include(__DIR__ . "/quanlydanhmuc/them.php");
            include(__DIR__ . "/quanlydanhmuc/lietke.php");
        }
        else if($tam=='quanlydanhmucsp' && $query=="sua"){
            include(__DIR__ . "/quanlydanhmuc/sua.php");
        }
        else if($tam=='quanlymonan' && $query=="them"){
            include(__DIR__ . "/quanlysp/them.php");
            include(__DIR__ . "/quanlysp/lietke.php");
        }
        else if($tam=='quanlymonan' && $query=="sua"){
            include(__DIR__ . "/quanlysp/sua.php");
        }
        else if($tam=='quanlydanhmucbaiviet' && $query=="them"){
            include(__DIR__ . "/quanlydanhmucbaiviet/them.php");
            include(__DIR__ . "/quanlydanhmucbaiviet/lietke.php");
        }
        else if($tam=='quanlydanhmucbaiviet' && $query=="sua"){
            include(__DIR__ . "/quanlydanhmucbaiviet/sua.php");
        }
        else if($tam=='quanlybaiviet' && $query=="them"){
            include(__DIR__ . "/quanlybaiviet/them.php");
            include(__DIR__ . "/quanlybaiviet/lietke.php");
        }
        else if($tam=='quanlybaiviet' && $query=="sua"){
            include(__DIR__ . "/quanlybaiviet/sua.php");
        }
        else if($tam=='quanlyweb' && $query=="capnhat"){
            include(__DIR__ . "/thongtinweb/quanly.php");
        }
        else if($tam=='quanlynguoidung' && $query=="lietke"){
            include(__DIR__ . "/quanlynguoidung/lietke.php");
        }
        else if($tam=='quanlydonhang' && $query=="lietke"){
            include(__DIR__ . "/quanlydonhang/lietke.php");
        }
        else if($tam=='quanlydonhang' && $query=="xem"){
            include(__DIR__ . "/quanlydonhang/xem.php");
        }
        else if($tam=='quanlylienhe' && $query=="lietke"){
            include(__DIR__ . "/quanlylienhe/lietke.php");
        }
        else if($tam=='quanlylienhe' && $query=="sua"){
            include(__DIR__ . "/quanlylienhe/sua.php");
        }
        else if($tam=='quanlychatbot' && $query=="lietke"){
            include(__DIR__ . "/quanlychatbot/lietke.php");
        }
        else if($tam=='thongke' && $query=="xem"){
            include(__DIR__ . "/thongke/xem.php");
        }
        else{
            include(__DIR__ . "/dashboard.php");
        }
    ?>
</div>  