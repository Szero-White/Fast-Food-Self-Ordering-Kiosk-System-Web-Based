<?php
if (isset($_GET['quanly'])) {
    $tam = $_GET['quanly'];
} else {
    $tam = "";
}

// Welcome và camon hiển thị full screen không có sidebar
if ($tam == 'welcome') {
    include("main/welcome.php");
} else if ($tam == 'camon') {
    include("main/camon.php");
} else {
?>
<div class="main">
    <div class="maincontent">
        <?php
        if ($tam == 'monan') {
            include("main/tenmonan.php");
        } else if ($tam == 'danhmucsanpham') {
            include("main/danhmuc.php");
        } else if ($tam == 'timkiem') {
            include("main/timkiem.php");
        } else if ($tam == 'sanpham') {
            include("main/sanpham.php");
        } else if ($tam == 'baiviet') {
            include("main/baiviet.php");
        } else if ($tam == 'danhmucbaiviet') {
            include("main/danhmucbaiviet.php");
        } else if ($tam == 'lienhe') {
            include("main/lienhe.php");
        } else if ($tam == 'gioithieu') {
            include("main/gioithieu.php");
        } else if ($tam == 'contact') {
            include("main/contact.php");
        } else if ($tam == 'giohang') {
            include("main/giohang.php");
        } else if ($tam == 'thanhtoan') {
            include("main/thanhtoan.php");
        } else if ($tam == 'index' || $tam == '' || $tam == 'trangchu') {
            include("main/index.php");
        } else {
            include("main/index.php");
        }
        ?>
    </div>
</div>
<div class="clear"></div>
<?php } ?>