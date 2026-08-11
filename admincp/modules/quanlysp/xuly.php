<?php
include(__DIR__ . '/../../config/config.php');
require_once __DIR__ . '/../../includes/admin_security.php';

admin_require_login('../../login.php');
admin_require_valid_csrf();

function redirect_products(): void
{
    header('Location:../../index.php?action=quanlymonan&query=them');
    exit;
}

function redirect_product_form(?int $productId = null): void
{
    $url = '../../index.php?action=quanlymonan&query=them';
    if ($productId !== null && $productId > 0) {
        $url = '../../index.php?action=quanlymonan&query=sua&idsanpham=' . $productId;
    }

    header('Location:' . $url);
    exit;
}

function normalize_product_text(string $value): string
{
    return trim(preg_replace('/\s+/', ' ', $value) ?? '');
}

function find_product_image(mysqli $mysqli, int $id): ?string
{
    $stmt = mysqli_prepare($mysqli, 'SELECT hinhanh FROM tbl_sanpham WHERE id_sanpham = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $image);
    $found = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $found ? $image : null;
}

function product_field_exists(mysqli $mysqli, string $field, string $value, ?int $ignoreId = null): bool
{
    $allowedFields = ['tensanpham', 'masp'];
    if (!in_array($field, $allowedFields, true)) {
        throw new InvalidArgumentException('Trường kiểm tra không hợp lệ.');
    }

    $normalizedValue = mb_strtolower(normalize_product_text($value), 'UTF-8');
    $sql = "SELECT id_sanpham
            FROM tbl_sanpham
            WHERE LOWER(TRIM($field)) = ?
              AND (? IS NULL OR id_sanpham <> ?)
            LIMIT 1";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'sii', $normalizedValue, $ignoreId, $ignoreId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    return $exists;
}

function category_exists(mysqli $mysqli, int $categoryId): bool
{
    if ($categoryId <= 0) {
        return false;
    }

    $stmt = mysqli_prepare($mysqli, 'SELECT id_danhmuc FROM tbl_danhmuc WHERE id_danhmuc = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $categoryId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    return $exists;
}

function validate_required_product_fields(
    mysqli $mysqli,
    string $name,
    string $code,
    string $price,
    int $quantity,
    int $categoryId,
    ?int $productId = null
): void {
    $errors = [];

    if ($name === '') {
        $errors[] = 'tên món ăn';
    }

    if ($code === '') {
        $errors[] = 'mã món';
    }

    if ($price === '' || !is_numeric($price) || (float)$price <= 0) {
        $errors[] = 'giá bán hợp lệ';
    }

    if ($quantity < 0) {
        $errors[] = 'số lượng không âm';
    }

    if (!category_exists($mysqli, $categoryId)) {
        $errors[] = 'danh mục';
    }

    if ($errors === []) {
        return;
    }

    admin_set_flash('danger', 'Vui lòng kiểm tra lại: ' . implode(', ', $errors) . '.');
    redirect_product_form($productId);
}

function validate_unique_product(mysqli $mysqli, string $name, string $code, ?int $ignoreId = null): void
{
    if (product_field_exists($mysqli, 'tensanpham', $name, $ignoreId)) {
        admin_set_flash('danger', 'Tên món ăn đã tồn tại. Vui lòng nhập tên món khác để tránh trùng dữ liệu.');
        redirect_product_form($ignoreId);
    }

    if (product_field_exists($mysqli, 'masp', $code, $ignoreId)) {
        admin_set_flash('danger', 'Mã món đã tồn tại. Vui lòng dùng mã món khác, ví dụ MON001 hoặc COMBO002.');
        redirect_product_form($ignoreId);
    }
}

try {
    if (isset($_POST['themsanpham'])) {
        $tensanpham = normalize_product_text((string)($_POST['tensanpham'] ?? ''));
        $masp = normalize_product_text((string)($_POST['masp'] ?? ''));
        $giasp = trim($_POST['giasp'] ?? '');
        $soluong = (int)($_POST['soluong'] ?? 0);
        $tomtat = trim($_POST['tomtat'] ?? '');
        $noidung = trim($_POST['noidung'] ?? '');
        $thutu = (int)($_POST['thutu'] ?? 0);
        $danhmuc = (int)($_POST['danhmuc'] ?? 0);

        validate_required_product_fields($mysqli, $tensanpham, $masp, $giasp, $soluong, $danhmuc);
        validate_unique_product($mysqli, $tensanpham, $masp);

        $hinhanh = save_uploaded_image($_FILES['hinhanh'] ?? [], 'products') ?? '';

        $stmt = mysqli_prepare(
            $mysqli,
            'INSERT INTO tbl_sanpham(tensanpham, masp, giasp, soluong, tomtat, noidung, hinhanh, thutu, id_danhmuc)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        mysqli_stmt_bind_param($stmt, 'sssisssii', $tensanpham, $masp, $giasp, $soluong, $tomtat, $noidung, $hinhanh, $thutu, $danhmuc);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        redirect_products();
    }

    if (isset($_POST['suasanpham'])) {
        $id = (int)($_GET['idsanpham'] ?? 0);
        $tensanpham = normalize_product_text((string)($_POST['tensanpham'] ?? ''));
        $masp = normalize_product_text((string)($_POST['masp'] ?? ''));
        $giasp = trim($_POST['giasp'] ?? '');
        $soluong = (int)($_POST['soluong'] ?? 0);
        $tomtat = trim($_POST['tomtat'] ?? '');
        $noidung = trim($_POST['noidung'] ?? '');
        $thutu = (int)($_POST['thutu'] ?? 0);
        $danhmuc = (int)($_POST['danhmuc'] ?? 0);

        if ($id <= 0 || find_product_image($mysqli, $id) === null) {
            admin_set_flash('danger', 'Món ăn cần cập nhật không tồn tại.');
            redirect_products();
        }

        validate_required_product_fields($mysqli, $tensanpham, $masp, $giasp, $soluong, $danhmuc, $id);
        validate_unique_product($mysqli, $tensanpham, $masp, $id);

        $oldImage = find_product_image($mysqli, $id);
        $newImage = save_uploaded_image($_FILES['hinhanh'] ?? [], 'products');

        if ($newImage !== null) {
            $stmt = mysqli_prepare(
                $mysqli,
                'UPDATE tbl_sanpham
                 SET tensanpham = ?, masp = ?, giasp = ?, soluong = ?, tomtat = ?, noidung = ?, hinhanh = ?, thutu = ?, id_danhmuc = ?
                 WHERE id_sanpham = ?'
            );
            mysqli_stmt_bind_param($stmt, 'sssisssiii', $tensanpham, $masp, $giasp, $soluong, $tomtat, $noidung, $newImage, $thutu, $danhmuc, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            delete_uploaded_image($oldImage);
        } else {
            $stmt = mysqli_prepare(
                $mysqli,
                'UPDATE tbl_sanpham
                 SET tensanpham = ?, masp = ?, giasp = ?, soluong = ?, tomtat = ?, noidung = ?, thutu = ?, id_danhmuc = ?
                 WHERE id_sanpham = ?'
            );
            mysqli_stmt_bind_param($stmt, 'sssissiii', $tensanpham, $masp, $giasp, $soluong, $tomtat, $noidung, $thutu, $danhmuc, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        redirect_products();
    }

    $id = (int)($_GET['idsanpham'] ?? 0);
    $oldImage = find_product_image($mysqli, $id);

    $stmt = mysqli_prepare($mysqli, 'DELETE FROM tbl_sanpham WHERE id_sanpham = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    delete_uploaded_image($oldImage);

    redirect_products();
} catch (RuntimeException $exception) {
    admin_set_flash('danger', $exception->getMessage());
    redirect_products();
}
