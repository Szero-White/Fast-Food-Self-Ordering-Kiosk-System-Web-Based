<?php
declare(strict_types=1);

function fetch_cart_product(mysqli $mysqli, int $productId): ?array
{
    $stmt = mysqli_prepare(
        $mysqli,
        'SELECT id_sanpham, tensanpham, giasp, hinhanh, soluong
         FROM tbl_sanpham
         WHERE id_sanpham = ?
         LIMIT 1'
    );

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    return $product ?: null;
}

function handle_cart_request(mysqli $mysqli, string $currentPage): void
{
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_POST['them_giohang'])) {
        $productId = (int)($_POST['id_sanpham'] ?? 0);
        $quantity = max(1, (int)($_POST['soluong'] ?? 1));
        $product = fetch_cart_product($mysqli, $productId);

        if ($product !== null) {
            $quantity = kiosk_clamp_cart_quantity($quantity, (int)($product['soluong'] ?? 0));
            if ($quantity <= 0) {
                kiosk_redirect('index.php?quanly=giohang');
            }

            $found = false;

            foreach ($_SESSION['cart'] as &$item) {
                if ((int)$item['id'] === $productId) {
                    $item['soluong'] = kiosk_clamp_cart_quantity(
                        (int)$item['soluong'] + $quantity,
                        (int)($product['soluong'] ?? 0)
                    );
                    $found = true;
                    break;
                }
            }
            unset($item);

            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => (int)$product['id_sanpham'],
                    'ten' => (string)$product['tensanpham'],
                    'gia' => (float)$product['giasp'],
                    'hinhanh' => (string)$product['hinhanh'],
                    'soluong' => $quantity,
                ];
            }
        }

        kiosk_redirect('index.php?quanly=giohang');
    }

    if (isset($_POST['capnhat'])) {
        $productId = (int)($_POST['id'] ?? 0);
        $quantity = (int)($_POST['soluong'] ?? 1);

        foreach ($_SESSION['cart'] as $key => &$item) {
            if ((int)$item['id'] !== $productId) {
                continue;
            }

            if ($quantity > 0) {
                $product = fetch_cart_product($mysqli, $productId);
                $updatedQuantity = kiosk_clamp_cart_quantity(
                    $quantity,
                    $product !== null ? (int)($product['soluong'] ?? 0) : null
                );

                if ($updatedQuantity > 0) {
                    $item['soluong'] = $updatedQuantity;
                } else {
                    unset($_SESSION['cart'][$key]);
                }
            } else {
                unset($_SESSION['cart'][$key]);
            }

            break;
        }
        unset($item);

        $_SESSION['cart'] = array_values($_SESSION['cart']);
        kiosk_redirect('index.php?quanly=giohang');
    }

    if (isset($_GET['xoa'])) {
        $productId = (int)$_GET['xoa'];

        foreach ($_SESSION['cart'] as $key => $item) {
            if ((int)$item['id'] === $productId) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }

        $_SESSION['cart'] = array_values($_SESSION['cart']);
        kiosk_redirect('index.php?quanly=giohang');
    }

    if ($currentPage === 'thanhtoan' && empty($_SESSION['cart'])) {
        kiosk_redirect('index.php?quanly=giohang');
    }
}
