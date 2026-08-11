<?php
include(__DIR__ . '/../../config/config.php');
require_once __DIR__ . '/../../includes/admin_security.php';

admin_require_login('../../login.php');
admin_require_valid_csrf();

if (isset($_GET['idlienhe'])) {
    $id = (int)$_GET['idlienhe'];
    $stmt = mysqli_prepare($mysqli, 'DELETE FROM tbl_lienhe WHERE id_lienhe = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('Location:../../index.php?action=quanlylienhe&query=lietke');
    exit;
}
?>
