<?php
    $mysqli = new mysqli("localhost","root","","web_sqli");

    // Check connection
    if ($mysqli->connect_errno) {
        echo "Kết nối MYSQLI lỗi " . $mysqli->connect_error;
        exit();
    }

    // Set charset UTF-8
    $mysqli->set_charset("utf8mb4");
    $mysqli->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
?>