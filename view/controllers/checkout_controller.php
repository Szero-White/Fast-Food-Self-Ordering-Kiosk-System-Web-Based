<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/kiosk_order_repository.php';

function handle_checkout_request(mysqli $mysqli, string $currentPage): void
{
    if ($currentPage !== 'thanhtoan') {
        unset($_SESSION['payment_method']);
        return;
    }

    if (isset($_GET['chonlai'])) {
        unset($_SESSION['payment_method']);
        return;
    }

    if (isset($_POST['thanhtoan'])) {
        $_SESSION['payment_method'] = kiosk_normalize_payment_method($_POST['phuongthuc'] ?? 'cash');
        return;
    }

    if (!isset($_POST['hoantat'])) {
        return;
    }

    $result = kiosk_complete_order(
        $mysqli,
        isset($_SESSION['id_donhang']) ? (int)$_SESSION['id_donhang'] : null,
        isset($_SESSION['madon']) ? (string)$_SESSION['madon'] : null,
        $_SESSION['cart'] ?? [],
        $_POST['phuongthuc'] ?? $_SESSION['payment_method'] ?? 'cash'
    );

    $_SESSION['payment_success'] = true;
    $_SESSION['madon'] = $result['code'];
    $_SESSION['id_donhang'] = $result['id'];

    kiosk_redirect('index.php?quanly=camon');
}
