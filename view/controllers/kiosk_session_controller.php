<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/kiosk_order_repository.php';

function kiosk_redirect(string $url): void
{
    header('Location: ' . $url);
    exit();
}

function handle_kiosk_session_request(mysqli $mysqli, string $currentPage): void
{
    if (isset($_GET['reset']) && (string)$_GET['reset'] === '1') {
        $_SESSION = [];
        session_destroy();
        session_start();
        kiosk_redirect('index.php?quanly=welcome');
    }

    if (isset($_GET['start']) && (string)$_GET['start'] === '1') {
        $_SESSION['kiosk_started'] = true;
        $_SESSION['kiosk_start_time'] = time();
        $_SESSION['madon'] = kiosk_generate_order_code();
        unset($_SESSION['id_donhang']);
        $_SESSION['cart'] = [];

        kiosk_redirect('index.php?quanly=index');
    }

    if ($currentPage === 'welcome') {
        include __DIR__ . '/../pages/main/welcome.php';
        exit();
    }

    if ($currentPage === 'camon') {
        include __DIR__ . '/../pages/main/camon.php';
        exit();
    }

    if (!isset($_SESSION['kiosk_started']) || $_SESSION['kiosk_started'] !== true) {
        include __DIR__ . '/../pages/main/welcome.php';
        exit();
    }
}
