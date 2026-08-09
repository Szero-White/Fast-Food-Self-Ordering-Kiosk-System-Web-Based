<?php
declare(strict_types=1);

require_once __DIR__ . '/order_notification_repository.php';

function kiosk_generate_order_code(): string
{
    return 'FF' . date('YmdHis') . random_int(100, 999);
}

function kiosk_normalize_payment_method(?string $method): string
{
    return in_array($method, ['transfer', 'cash'], true) ? $method : 'cash';
}

function kiosk_cart_total(array $cart): float
{
    $total = 0;

    foreach ($cart as $item) {
        $total += (float)($item['gia'] ?? 0) * (int)($item['soluong'] ?? 0);
    }

    return $total;
}

function kiosk_cart_quantity(array $cart): int
{
    $quantity = 0;

    foreach ($cart as $item) {
        if (!is_array($item)) {
            continue;
        }

        $quantity += max(0, (int)($item['soluong'] ?? 0));
    }

    return $quantity;
}

function kiosk_complete_order(mysqli $mysqli, ?int $orderId, ?string $orderCode, array $cart, string $paymentMethod): array
{
    ensure_order_notification_columns($mysqli);

    if (empty($cart)) {
        throw new RuntimeException('Giỏ hàng trống.');
    }

    $paymentMethod = kiosk_normalize_payment_method($paymentMethod);
    $orderCode = $orderCode !== null && trim($orderCode) !== '' ? $orderCode : kiosk_generate_order_code();
    $customerName = 'Khách Kiosk';
    $total = kiosk_cart_total($cart);

    mysqli_begin_transaction($mysqli);

    try {
        if ($orderId !== null && $orderId > 0) {
            $stmt = mysqli_prepare(
                $mysqli,
                'UPDATE tbl_donhang
                 SET madon = ?, tenkhach = ?, tongtien = ?, trangthai = 1, admin_seen = 0, ngaydat = NOW(), phuongthuc = ?
                 WHERE id = ?'
            );

            if (!$stmt) {
                throw new RuntimeException('Không thể cập nhật đơn hàng.');
            }

            mysqli_stmt_bind_param($stmt, 'ssdsi', $orderCode, $customerName, $total, $paymentMethod, $orderId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $stmt = mysqli_prepare(
                $mysqli,
                'INSERT INTO tbl_donhang (madon, tenkhach, tongtien, trangthai, admin_seen, ngaydat, phuongthuc)
                 VALUES (?, ?, ?, 1, 0, NOW(), ?)'
            );

            if (!$stmt) {
                throw new RuntimeException('Không thể lưu đơn hàng.');
            }

            mysqli_stmt_bind_param($stmt, 'ssds', $orderCode, $customerName, $total, $paymentMethod);
            mysqli_stmt_execute($stmt);
            $orderId = mysqli_insert_id($mysqli);
            mysqli_stmt_close($stmt);
        }

        $deleteStmt = mysqli_prepare($mysqli, 'DELETE FROM tbl_chitietdonhang WHERE id_donhang = ?');
        if (!$deleteStmt) {
            throw new RuntimeException('Không thể làm mới chi tiết đơn hàng.');
        }

        mysqli_stmt_bind_param($deleteStmt, 'i', $orderId);
        mysqli_stmt_execute($deleteStmt);
        mysqli_stmt_close($deleteStmt);

        $detailStmt = mysqli_prepare(
            $mysqli,
            'INSERT INTO tbl_chitietdonhang (id_donhang, id_sanpham, ten_sanpham, gia, soluong, thanhtien)
             VALUES (?, ?, ?, ?, ?, ?)'
        );

        if (!$detailStmt) {
            throw new RuntimeException('Không thể lưu chi tiết đơn hàng.');
        }

        foreach ($cart as $item) {
            $productId = (int)($item['id'] ?? 0);
            $productName = (string)($item['ten'] ?? '');
            $price = (float)($item['gia'] ?? 0);
            $quantity = (int)($item['soluong'] ?? 0);
            $lineTotal = $price * $quantity;

            mysqli_stmt_bind_param($detailStmt, 'iisdid', $orderId, $productId, $productName, $price, $quantity, $lineTotal);
            mysqli_stmt_execute($detailStmt);
        }

        mysqli_stmt_close($detailStmt);
        mysqli_commit($mysqli);

        return [
            'id' => $orderId,
            'code' => $orderCode,
            'total' => $total,
        ];
    } catch (Throwable $exception) {
        mysqli_rollback($mysqli);
        throw $exception;
    }
}
