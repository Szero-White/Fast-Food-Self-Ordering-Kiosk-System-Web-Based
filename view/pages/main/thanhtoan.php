<?php
require_once __DIR__ . '/../../../config/kiosk_order_repository.php';

$cartItems = $_SESSION['cart'] ?? [];
$totalAmount = kiosk_cart_total($cartItems);
$paymentMethod = $_SESSION['payment_method'] ?? null;

function checkout_payment_label(?string $method): string
{
    return $method === 'transfer' ? 'Quét mã QR / Chuyển khoản' : 'Tiền mặt';
}

function checkout_demo_order_code(): string
{
    return isset($_SESSION['madon']) && $_SESSION['madon'] !== ''
        ? (string)$_SESSION['madon']
        : 'ORDER-DEMO';
}

if (empty($cartItems)) {
    ?>
    <div class="checkout-empty-cart">Giỏ hàng trống! Vui lòng thêm món trước khi thanh toán.</div>
    <p class="checkout-empty-actions">
        <a href="index.php?quanly=index" class="checkout-empty-link">Quay lại trang chủ</a>
    </p>
    <?php
    return;
}
?>

<div class="checkout-container">
    <div class="checkout-header">
        <h1>Thanh toán</h1>
        <p><?php echo $paymentMethod ? 'Kiểm tra đơn hàng trước khi hoàn tất' : 'Vui lòng chọn phương thức thanh toán'; ?></p>
    </div>

    <div class="order-summary">
        <h3>Đơn hàng của bạn</h3>
        <?php foreach ($cartItems as $item) {
            $itemName = htmlspecialchars((string)$item['ten'], ENT_QUOTES, 'UTF-8');
            $quantity = (int)$item['soluong'];
            $lineTotal = (float)$item['gia'] * $quantity;
        ?>
            <div class="order-item">
                <span><?php echo $itemName; ?> x<?php echo $quantity; ?></span>
                <span><?php echo number_format($lineTotal, 0, ',', '.'); ?>đ</span>
            </div>
        <?php } ?>
        <div class="order-total">
            Tổng: <?php echo number_format($totalAmount, 0, ',', '.'); ?>đ
        </div>
    </div>

    <?php if ($paymentMethod) { ?>
        <div class="payment-info">
            <p><strong>Phương thức: <?php echo checkout_payment_label($paymentMethod); ?></strong></p>
            <?php if ($paymentMethod === 'transfer') { ?>
                <div class="qr-section">
                    <div class="qr-code" aria-label="Mã QR thanh toán demo">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <div class="qr-payment-detail">
                        <p><strong>Quét mã để thanh toán</strong></p>
                        <span>Mã đơn: <?php echo htmlspecialchars(checkout_demo_order_code(), ENT_QUOTES, 'UTF-8'); ?></span>
                        <span>Số tiền: <?php echo number_format($totalAmount, 0, ',', '.'); ?>đ</span>
                    </div>
                    <p class="checkout-note">Sau khi thanh toán thành công, bấm hoàn tất để gửi đơn sang khu vực quản trị.</p>
                </div>
            <?php } else { ?>
                <div class="cash-payment-detail">
                    <i class="fas fa-money-bill-wave"></i>
                    <p>Khách thanh toán bằng tiền mặt tại quầy thu ngân.</p>
                </div>
            <?php } ?>
        </div>

        <div class="checkout-actions">
            <a href="index.php?quanly=thanhtoan&chonlai=1" class="btn-back">Quay lại chọn phương thức</a>
            <form method="POST" action="index.php?quanly=thanhtoan" class="checkout-inline-form">
                <input type="hidden" name="phuongthuc" value="<?php echo htmlspecialchars($paymentMethod, ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" name="hoantat" class="btn-pay">Hoàn tất thanh toán</button>
            </form>
        </div>
    <?php } else { ?>
        <form method="POST" action="index.php?quanly=thanhtoan">
            <div class="payment-methods">
                <h3>Chọn phương thức thanh toán</h3>

                <label class="payment-option selected">
                    <input type="radio" name="phuongthuc" value="transfer" checked>
                    <span class="payment-option-icon"><i class="fas fa-qrcode"></i></span>
                    <div>
                        <strong>Quét mã QR / Chuyển khoản</strong>
                        <p class="payment-option-note">Momo, ZaloPay, VietQR hoặc ngân hàng</p>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="phuongthuc" value="cash">
                    <span class="payment-option-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <div>
                        <strong>Tiền mặt</strong>
                        <p class="payment-option-note">Thanh toán trực tiếp tại quầy</p>
                    </div>
                </label>
            </div>

            <div class="payment-preview" data-payment-preview="transfer">
                <div class="payment-preview-copy">
                    <span class="checkout-eyebrow">Thanh toán chuyển khoản</span>
                    <h3>Quét mã QR để thanh toán</h3>
                    <p>Dùng ứng dụng ngân hàng, Momo, ZaloPay hoặc ví hỗ trợ quét mã. Đây là mã demo cho môi trường giới thiệu sản phẩm.</p>
                    <div class="payment-meta">
                        <span>Mã đơn: <?php echo htmlspecialchars(checkout_demo_order_code(), ENT_QUOTES, 'UTF-8'); ?></span>
                        <span>Số tiền: <?php echo number_format($totalAmount, 0, ',', '.'); ?>đ</span>
                    </div>
                </div>
                <div class="qr-code qr-code-large" aria-label="Mã QR thanh toán demo">
                    <i class="fas fa-qrcode"></i>
                </div>
            </div>

            <div class="payment-preview payment-preview-hidden" data-payment-preview="cash">
                <div class="payment-preview-copy">
                    <span class="checkout-eyebrow">Thanh toán tại quầy</span>
                    <h3>Nhận đơn và thanh toán tiền mặt</h3>
                    <p>Khách đưa mã đơn cho nhân viên thu ngân, thanh toán trực tiếp và nhận món tại quầy.</p>
                    <div class="payment-meta">
                        <span>Số tiền cần thu: <?php echo number_format($totalAmount, 0, ',', '.'); ?>đ</span>
                    </div>
                </div>
                <div class="cash-preview-icon" aria-hidden="true">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>

            <div class="checkout-actions">
                <a href="index.php?quanly=giohang" class="btn-back">Quay lại giỏ hàng</a>
                <button type="submit" name="thanhtoan" class="btn-pay">Tiếp tục thanh toán</button>
            </div>
        </form>
    <?php } ?>
</div>
