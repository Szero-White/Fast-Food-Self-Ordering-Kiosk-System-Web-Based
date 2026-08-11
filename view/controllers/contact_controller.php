<?php
declare(strict_types=1);

function contact_generate_form_token(): string
{
    $token = bin2hex(random_bytes(16));
    $_SESSION['contact_form_token'] = $token;

    return $token;
}

function contact_redirect_to_form(): void
{
    kiosk_redirect('index.php?quanly=lienhe');
}

function handle_contact_request(mysqli $mysqli, string $currentPage): void
{
    if (!in_array($currentPage, ['lienhe', 'contact'], true) || ($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return;
    }

    $postedToken = (string)($_POST['contact_form_token'] ?? '');
    $sessionToken = (string)($_SESSION['contact_form_token'] ?? '');
    unset($_SESSION['contact_form_token']);

    $ten = trim((string)($_POST['ten'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $sodienthoai = trim((string)($_POST['sodienthoai'] ?? ''));
    $loai = trim((string)($_POST['loai'] ?? ''));
    $noidung = trim((string)($_POST['noidung'] ?? ''));

    $_SESSION['contact_form_old'] = [
        'ten' => $ten,
        'email' => $email,
        'sodienthoai' => $sodienthoai,
        'loai' => $loai,
        'noidung' => $noidung,
    ];

    if ($postedToken === '' || $sessionToken === '' || !hash_equals($sessionToken, $postedToken)) {
        $_SESSION['contact_form_error'] = 'Phiên gửi liên hệ không hợp lệ. Vui lòng tải lại trang và gửi lại.';
        contact_redirect_to_form();
    }

    if ($ten === '' || $email === '' || $loai === '' || $noidung === '') {
        $_SESSION['contact_form_error'] = 'Vui lòng nhập đầy đủ họ tên, email, loại liên hệ và nội dung.';
        contact_redirect_to_form();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_form_error'] = 'Email không hợp lệ. Vui lòng kiểm tra lại.';
        contact_redirect_to_form();
    }

    $stmt = mysqli_prepare(
        $mysqli,
        'INSERT INTO tbl_lienhe (thongtinlienhe, hinhanh, ngaygui, trangthai, ten, email, sodienthoai, loai, noidung)
         VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?)'
    );

    if (!$stmt) {
        $_SESSION['contact_form_error'] = 'Không thể gửi liên hệ lúc này. Vui lòng thử lại sau.';
        contact_redirect_to_form();
    }

    $trangthai = 'chua_xem';
    $thongtinlienhe = "$ten | $email | $sodienthoai | $loai";
    $emptyImage = '';

    mysqli_stmt_bind_param(
        $stmt,
        'ssssssss',
        $thongtinlienhe,
        $emptyImage,
        $trangthai,
        $ten,
        $email,
        $sodienthoai,
        $loai,
        $noidung
    );

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        $_SESSION['contact_form_error'] = 'Có lỗi xảy ra khi gửi liên hệ. Vui lòng thử lại sau.';
        contact_redirect_to_form();
    }

    mysqli_stmt_close($stmt);

    unset($_SESSION['contact_form_old']);
    $_SESSION['contact_form_success'] = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.';

    contact_redirect_to_form();
}
