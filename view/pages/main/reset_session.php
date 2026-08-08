<?php
session_start();

// Cập nhật đơn hàng thành "Đã hủy" (trangthai = 2) nếu chưa thanh toán
if (isset($_SESSION['id_donhang']) && $_SESSION['id_donhang'] > 0) {
    include(__DIR__ . '/../../config/config.php');
    $id_donhang = $_SESSION['id_donhang'];
    // Chỉ hủy nếu đơn hàng đang ở trạng thái "Đang chọn" (0)
    mysqli_query($mysqli, "UPDATE tbl_donhang SET trangthai = 2 WHERE id = '$id_donhang' AND trangthai = 0");
}

$_SESSION = array();
session_destroy();
echo json_encode(['success' => true]);
?>
