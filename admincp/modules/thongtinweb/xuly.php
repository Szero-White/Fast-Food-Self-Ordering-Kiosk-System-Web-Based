<?php
include(__DIR__ . '/../../config/config.php');

if (isset($_POST['themlienhe'])) {
    // Lấy dữ liệu từ form
    $noidung = mysqli_real_escape_string($mysqli, $_POST['noidung']);
    $id = 1; // Luôn là 1 cho trang giới thiệu

    // Kiểm tra xem liệu người dùng đã tải lên hình ảnh mới hay không
    if(isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
        $hinhanh = time() . '_' . basename($_FILES['hinhanh']['name']);
        $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
        $target_dir = __DIR__ . '/../../uploads/';
        $target_file = $target_dir . $hinhanh;

        // Di chuyển tệp tải lên vào thư mục đích
        if (move_uploaded_file($hinhanh_tmp, $target_file)) {
            // Cập nhật nội dung và hình ảnh
            $sql_update = "UPDATE tbl_gioithieu SET noidung = '$noidung', hinhanh = '$hinhanh' WHERE id = '$id'";
            mysqli_query($mysqli, $sql_update);
            // Nếu chưa có dữ liệu thì INSERT mới
            if (mysqli_affected_rows($mysqli) == 0) {
                $sql_insert = "INSERT INTO tbl_gioithieu (id, noidung, hinhanh) VALUES ('$id', '$noidung', '$hinhanh')";
                mysqli_query($mysqli, $sql_insert);
            }
            header('Location:../../index.php?action=quanlyweb&query=capnhat');
        } else {
            echo "Lỗi khi tải lên tệp.";
        }
    } else {
        // Chỉ cập nhật nội dung
        $sql_update = "UPDATE tbl_gioithieu SET noidung = '$noidung' WHERE id = '$id'";
        mysqli_query($mysqli, $sql_update);
        // Nếu chưa có dữ liệu thì INSERT mới
        if (mysqli_affected_rows($mysqli) == 0) {
            $sql_insert = "INSERT INTO tbl_gioithieu (id, noidung, hinhanh) VALUES ('$id', '$noidung', '')";
            mysqli_query($mysqli, $sql_insert);
        }
        header('Location:../../index.php?action=quanlyweb&query=capnhat');
    }
}
?>
