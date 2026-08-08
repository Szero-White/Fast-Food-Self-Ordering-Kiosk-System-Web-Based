<?php
declare(strict_types=1);

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'web_sqli';
$dbPort = getenv('DB_PORT') ?: '';

$host = $dbPort !== '' ? $dbHost . ':' . $dbPort : $dbHost;
$mysqli = new mysqli($host, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_errno) {
    http_response_code(500);
    exit('Ket noi MYSQLI loi: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
$mysqli->query('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');
