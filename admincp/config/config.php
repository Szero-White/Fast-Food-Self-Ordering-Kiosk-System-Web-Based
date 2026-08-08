<?php
    // Mặc định port 3306, nếu XAMPP MySQL chạy port 3307 thì sửa thành "localhost:3307"
    $mysqli = new mysqli("localhost","root","","web_sqli");

    // Set UTF-8 charset để hiển thị tiếng Việt đúng
    $mysqli->set_charset("utf8mb4");

    // Check connection
    if ($mysqli->connect_errno) {
        echo "Kết nối MYSQLI lỗi " . $mysqli->connect_error;
        exit();
    }
?>