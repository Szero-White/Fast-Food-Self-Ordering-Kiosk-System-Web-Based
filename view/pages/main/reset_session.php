<?php
session_start();

if (isset($_SESSION['id_donhang']) && $_SESSION['id_donhang'] > 0) {
    include(__DIR__ . '/../../../config/config.php');
    $orderId = (int)$_SESSION['id_donhang'];
    $stmt = mysqli_prepare($mysqli, 'UPDATE tbl_donhang SET trangthai = 2 WHERE id = ? AND trangthai = 0');

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $orderId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

$_SESSION = [];
session_destroy();
echo json_encode(['success' => true]);
