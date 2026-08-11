<?php
include(__DIR__ . '/../../config/config.php');
require_once __DIR__ . '/../../includes/admin_security.php';
require_once __DIR__ . '/../../../config/banner_repository.php';

admin_require_login('../../login.php');
admin_require_valid_csrf();
ensure_banner_table($mysqli);

function redirect_banners(): void
{
    header('Location:../../index.php?action=quanlybanner&query=them');
    exit;
}

try {
    if (isset($_POST['capnhat_cauhinh_banner'])) {
        $visibleLimit = (int)($_POST['visible_limit'] ?? 0);
        update_banner_display_limit($mysqli, $visibleLimit);
        redirect_banners();
    }

    if (isset($_POST['thembanner'])) {
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = (int)($_POST['is_active'] ?? 1);
        $imagePath = save_uploaded_image($_FILES['hinhanh'] ?? [], 'banners');
        $imageSource = 'upload';

        if ($imagePath === null) {
            $defaults = default_banner_rows();
            $imagePath = $defaults[0]['image_path'];
            $imageSource = 'asset';
        }

        $stmt = mysqli_prepare(
            $mysqli,
            'INSERT INTO tbl_banner (title, subtitle, image_path, image_source, link, sort_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        mysqli_stmt_bind_param($stmt, 'sssssii', $title, $subtitle, $imagePath, $imageSource, $link, $sortOrder, $isActive);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        redirect_banners();
    }

    if (isset($_POST['suabanner'])) {
        $id = (int)($_GET['idbanner'] ?? 0);
        $banner = find_banner($mysqli, $id);
        if ($banner === null) {
            redirect_banners();
        }

        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = (int)($_POST['is_active'] ?? 1);
        $newImage = save_uploaded_image($_FILES['hinhanh'] ?? [], 'banners');

        if ($newImage !== null) {
            $imageSource = 'upload';
            $stmt = mysqli_prepare(
                $mysqli,
                'UPDATE tbl_banner
                 SET title = ?, subtitle = ?, image_path = ?, image_source = ?, link = ?, sort_order = ?, is_active = ?
                 WHERE id_banner = ?'
            );
            mysqli_stmt_bind_param($stmt, 'sssssiii', $title, $subtitle, $newImage, $imageSource, $link, $sortOrder, $isActive, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if (($banner['image_source'] ?? '') === 'upload') {
                delete_uploaded_image($banner['image_path'] ?? '');
            }
        } else {
            $stmt = mysqli_prepare(
                $mysqli,
                'UPDATE tbl_banner
                 SET title = ?, subtitle = ?, link = ?, sort_order = ?, is_active = ?
                 WHERE id_banner = ?'
            );
            mysqli_stmt_bind_param($stmt, 'sssiii', $title, $subtitle, $link, $sortOrder, $isActive, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        redirect_banners();
    }

    $id = (int)($_GET['idbanner'] ?? 0);
    $banner = find_banner($mysqli, $id);

    if ($banner !== null) {
        $stmt = mysqli_prepare($mysqli, 'DELETE FROM tbl_banner WHERE id_banner = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if (($banner['image_source'] ?? '') === 'upload') {
            delete_uploaded_image($banner['image_path'] ?? '');
        }
    }

    redirect_banners();
} catch (RuntimeException $exception) {
    http_response_code(400);
    echo htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
}
