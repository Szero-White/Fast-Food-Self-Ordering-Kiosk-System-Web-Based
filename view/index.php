<?php
// Start session at the very beginning - before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("config/config.php");

// Xử lý bắt đầu phiên kiosk
if (isset($_GET['start']) && $_GET['start'] == 1) {
    $_SESSION['kiosk_started'] = true;
    $_SESSION['kiosk_start_time'] = time();
    header("Location: index.php");
    exit();
}

// Xử lý reset session
if (isset($_GET['reset']) && $_GET['reset'] == 1) {
    $_SESSION = array();
    session_destroy();
    session_start();
    header("Location: index.php");
    exit();
}

// Kiểm tra phiên kiosk - nếu chưa bắt đầu, hiển thị welcome
if (!isset($_SESSION['kiosk_started']) || $_SESSION['kiosk_started'] !== true) {
    include("pages/main/welcome.php");
    exit();
}

// Xử lý thêm vào giỏ hàng (phải trước mọi output)
if (isset($_POST['them_giohang'])) {
    $id_sanpham = $_POST['id_sanpham'];
    $ten_sanpham = $_POST['ten_sanpham'];
    $giasp = $_POST['giasp'];
    $hinhanh = $_POST['hinhanh'];
    $soluong = isset($_POST['soluong']) ? intval($_POST['soluong']) : 1;
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Kiểm tra món đã có chưa
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id_sanpham) {
            $item['soluong'] += $soluong;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = array(
            'id' => $id_sanpham,
            'ten' => $ten_sanpham,
            'gia' => $giasp,
            'hinhanh' => $hinhanh,
            'soluong' => $soluong
        );
    }
    
    // Chuyển đến trang giỏ hàng
    header("Location: index.php?quanly=giohang");
    exit();
}

// Xử lý cập nhật số lượng (phải trước mọi output)
if (isset($_POST['capnhat'])) {
    $id = $_POST['id'];
    $soluong = intval($_POST['soluong']);
    
    if ($soluong > 0) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $id) {
                $item['soluong'] = $soluong;
                break;
            }
        }
    } else {
        // Xóa nếu số lượng = 0
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    
    header("Location: index.php?quanly=giohang");
    exit();
}

// Xử lý xóa món (phải trước mọi output)
if (isset($_GET['xoa'])) {
    $id = $_GET['xoa'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: index.php?quanly=giohang");
    exit();
}

// Xử lý thanh toán (phải trước mọi output)
if (isset($_POST['thanhtoan'])) {
    include("config/config.php");
    
    $tongtien = 0;
    foreach ($_SESSION['cart'] as $item) {
        $tongtien += $item['gia'] * $item['soluong'];
    }
    
    // UPDATE đơn hàng đã có thay vì INSERT mới
    $madon = $_SESSION['madon'] ?? 'FF' . date('YmdHis') . rand(100, 999);
    $id_donhang = $_SESSION['id_donhang'] ?? 0;
    
    if ($id_donhang > 0) {
        // Lấy phương thức thanh toán từ POST
        $phuongthuc = $_POST['phuongthuc'] ?? 'cash';
        // Update đơn hàng: trangthai = 1 (Hoàn thành) + phương thức thanh toán
        $sql = "UPDATE tbl_donhang SET tongtien = '$tongtien', trangthai = 1, ngaydat = NOW(), phuongthuc = '$phuongthuc' WHERE id = '$id_donhang'";
        mysqli_query($mysqli, $sql);
        
        // Xóa chi tiết cũ (nếu có) và thêm chi tiết mới
        mysqli_query($mysqli, "DELETE FROM tbl_chitietdonhang WHERE id_donhang = '$id_donhang'");
        foreach ($_SESSION['cart'] as $item) {
            $id_sp = $item['id'];
            $ten_sp = mysqli_real_escape_string($mysqli, $item['ten']);
            $gia = $item['gia'];
            $soluong = $item['soluong'];
            $thanhtien = $gia * $soluong;
            $sql_ct = "INSERT INTO tbl_chitietdonhang (id_donhang, id_sanpham, ten_sanpham, gia, soluong, thanhtien) 
                       VALUES ('$id_donhang', '$id_sp', '$ten_sp', '$gia', '$soluong', '$thanhtien')";
            mysqli_query($mysqli, $sql_ct);
        }
    } else {
        // Fallback: Nếu không có đơn hàng trong session thì tạo mới
        $phuongthuc = $_POST['phuongthuc'] ?? 'cash';
        $sql = "INSERT INTO tbl_donhang (madon, tenkhach, tongtien, trangthai, ngaydat, phuongthuc) 
                VALUES ('$madon', 'Khach Kiosk', '$tongtien', 1, NOW(), '$phuongthuc')";
        mysqli_query($mysqli, $sql);
        $id_donhang = mysqli_insert_id($mysqli);
        foreach ($_SESSION['cart'] as $item) {
            $id_sp = $item['id'];
            $ten_sp = mysqli_real_escape_string($mysqli, $item['ten']);
            $gia = $item['gia'];
            $soluong = $item['soluong'];
            $thanhtien = $gia * $soluong;
            $sql_ct = "INSERT INTO tbl_chitietdonhang (id_donhang, id_sanpham, ten_sanpham, gia, soluong, thanhtien) 
                       VALUES ('$id_donhang', '$id_sp', '$ten_sp', '$gia', '$soluong', '$thanhtien')";
            mysqli_query($mysqli, $sql_ct);
        }
    }
    
    $_SESSION['payment_success'] = true;
    $_SESSION['madon'] = $madon;
    header("Location: index.php?quanly=camon");
    exit();
}

// Check giỏ hàng trống khi vào trang thanh toán
if (isset($_GET['quanly']) && $_GET['quanly'] == 'thanhtoan') {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        header("Location: index.php?quanly=giohang");
        exit();
    }
}

// Nếu người dùng bấm "BẮT ĐẦU" từ welcome page
if (isset($_GET['start']) && $_GET['start'] == '1') {
    $_SESSION['kiosk_started'] = true;
    $_SESSION['start_time'] = time();
    
    // Tạo đơn hàng mới với trạng thái "Đang chọn" (0)
    include("config/config.php");
    $madon = 'FF' . date('YmdHis') . rand(100, 999);
    $sql = "INSERT INTO tbl_donhang (madon, tenkhach, tongtien, trangthai, ngaydat) 
            VALUES ('$madon', 'Khach Kiosk', 0, 0, NOW())";
    mysqli_query($mysqli, $sql);
    $_SESSION['madon'] = $madon;
    $_SESSION['id_donhang'] = mysqli_insert_id($mysqli);
    
    header("Location: index.php?quanly=index");
    exit();
}

// Nếu vào welcome page - hiển thị full screen không qua layout
if (isset($_GET['quanly']) && $_GET['quanly'] == 'welcome') {
    include("pages/main/welcome.php");
    exit();
}

if (isset($_GET['quanly']) && $_GET['quanly'] == 'camon') {
    include("pages/main/camon.php");
    exit();
}

// Nếu chưa bấm BẮT ĐẦU và không phải đang ở welcome/camon -> chuyển về welcome
if (!isset($_SESSION['kiosk_started']) || $_SESSION['kiosk_started'] !== true) {
    if (!isset($_GET['quanly']) || $_GET['quanly'] != 'welcome') {
        header("Location: index.php?quanly=welcome");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quảng cáo thực đơn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styl.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Chatbot Widget -->
    <?php include("pages/chatbot.php"); ?>
    
    <div class="wrapper">
        <?php
        include("config/config.php");
        include("pages/header.php");
        include("pages/menu.php");
        include("pages/main.php");
        // Footer loaded by individual pages
        include("pages/footer.php");
        ?>
    </div>
    
</body>

</html>