<?php
declare(strict_types=1);

function ensure_order_notification_columns(mysqli $mysqli): void
{
    static $ready = false;
    if ($ready) {
        return;
    }

    $result = mysqli_query($mysqli, "SHOW COLUMNS FROM tbl_donhang LIKE 'admin_seen'");
    if ($result && mysqli_num_rows($result) === 0) {
        mysqli_query(
            $mysqli,
            'ALTER TABLE tbl_donhang
             ADD admin_seen TINYINT(1) NOT NULL DEFAULT 1 AFTER trangthai'
        );
    }

    if ($result) {
        mysqli_free_result($result);
    }

    $paymentMethodResult = mysqli_query($mysqli, "SHOW COLUMNS FROM tbl_donhang LIKE 'phuongthuc'");
    if ($paymentMethodResult && mysqli_num_rows($paymentMethodResult) === 0) {
        mysqli_query(
            $mysqli,
            "ALTER TABLE tbl_donhang
             ADD phuongthuc VARCHAR(50) DEFAULT NULL AFTER ngaydat"
        );
    }

    if ($paymentMethodResult) {
        mysqli_free_result($paymentMethodResult);
    }

    $ready = true;
}

function mark_order_seen(mysqli $mysqli, int $orderId): void
{
    ensure_order_notification_columns($mysqli);

    $stmt = mysqli_prepare($mysqli, 'UPDATE tbl_donhang SET admin_seen = 1 WHERE id = ?');
    if (!$stmt) {
        return;
    }

    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
