<?php
declare(strict_types=1);

function fetch_cart_product(mysqli $mysqli, int $productId): ?array
{
    $stmt = mysqli_prepare(
        $mysqli,
        'SELECT id_sanpham, tensanpham, giasp, hinhanh
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
        $quantity = max(1, min(10, (int)($_POST['soluong'] ?? 1)));
        $product = fetch_cart_product($mysqli, $productId);

        if ($product !== null) {
            $found = false;

            foreach ($_SESSION['cart'] as &$item) {
                if ((int)$item['id'] === $productId) {
                    $item['soluong'] = min(10, (int)$item['soluong'] + $quantity);
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
                $item['soluong'] = min(10, $quantity);
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
