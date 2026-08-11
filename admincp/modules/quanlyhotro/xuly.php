<?php
include(__DIR__ . '/../../config/config.php');
require_once __DIR__ . '/../../includes/admin_security.php';
require_once __DIR__ . '/../../../config/staff_call_repository.php';

admin_require_login('../../login.php');
admin_require_valid_csrf();

$idGoi  = isset($_GET['idgoi'])  ? (int)$_GET['idgoi']  : 0;
$action = isset($_GET['action']) ? (string)$_GET['action'] : '';

if ($idGoi > 0 && $action === 'daxuly') {
    staff_call_mark_handled($mysqli, $idGoi);
}

header('Location: ../../index.php?action=quanlyhotro&query=lietke');
exit;
