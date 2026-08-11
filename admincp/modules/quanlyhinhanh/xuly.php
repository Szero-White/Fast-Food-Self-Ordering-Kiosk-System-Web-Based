<?php
include(__DIR__ . '/../../config/config.php');
require_once __DIR__ . '/../../includes/admin_security.php';
require_once __DIR__ . '/../../../config/site_asset_repository.php';

admin_require_login('../../login.php');
admin_require_valid_csrf();
ensure_site_asset_table($mysqli);

function redirect_site_assets(): void
{
    header('Location:../../index.php?action=quanlyhinhanh&query=capnhat');
    exit;
}

try {
    if (!isset($_POST['capnhathinhanh'])) {
        redirect_site_assets();
    }

    $assetKey = trim($_POST['asset_key'] ?? '');
    $asset = find_site_asset($mysqli, $assetKey);

    if ($asset === null) {
        redirect_site_assets();
    }

    $newImage = save_uploaded_image($_FILES['hinhanh'] ?? [], 'site', $assetKey);

    if ($newImage === null) {
        redirect_site_assets();
    }

    $imageSource = 'upload';
    $stmt = mysqli_prepare(
        $mysqli,
        'UPDATE tbl_site_asset SET image_path = ?, image_source = ? WHERE asset_key = ?'
    );
    mysqli_stmt_bind_param($stmt, 'sss', $newImage, $imageSource, $assetKey);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (($asset['image_source'] ?? '') === 'upload') {
        delete_uploaded_image($asset['image_path'] ?? '');
    }

    redirect_site_assets();
} catch (RuntimeException $exception) {
    http_response_code(400);
    echo htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
}
