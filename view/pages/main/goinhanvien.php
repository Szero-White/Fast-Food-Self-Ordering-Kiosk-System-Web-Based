<?php
require_once __DIR__ . '/../../../config/staff_call_repository.php';

$sessionId = session_id();
if ($sessionId === '') {
    session_start();
    $sessionId = session_id();
}

$staffCallResult = staff_call_create($mysqli, $sessionId);
$staffCall = $staffCallResult['call'];
$isNewCall = (bool)$staffCallResult['is_new'];
$calledAt = !empty($staffCall['ngaygoi'])
    ? date('H:i d/m/Y', strtotime((string)$staffCall['ngaygoi']))
    : date('H:i d/m/Y');
?>

<section class="staff-call-page">
    <div class="staff-call-card">
        <div class="staff-call-icon">
            <i class="fas fa-bell-concierge"></i>
        </div>

        <p class="staff-call-eyebrow">Yêu cầu hỗ trợ</p>
        <h1><?php echo $isNewCall ? 'Đã gọi nhân viên' : 'Đang chờ nhân viên'; ?></h1>
        <p class="staff-call-message">
            <?php if ($isNewCall) { ?>
                Nhân viên đã nhận thông báo và sẽ đến hỗ trợ bạn trong ít phút.
            <?php } else { ?>
                Bạn đã có một yêu cầu đang chờ xử lý. Vui lòng giữ màn hình này hoặc tiếp tục chọn món.
            <?php } ?>
        </p>

        <div class="staff-call-code">
            <span>Mã yêu cầu</span>
            <strong><?php echo htmlspecialchars((string)$staffCall['ma_goi'], ENT_QUOTES, 'UTF-8'); ?></strong>
            <small><?php echo htmlspecialchars($calledAt, ENT_QUOTES, 'UTF-8'); ?></small>
        </div>

        <div class="staff-call-actions">
            <a href="index.php?quanly=index" class="staff-call-btn primary">
                <i class="fas fa-utensils"></i>
                Tiếp tục chọn món
            </a>
            <a href="index.php?quanly=giohang" class="staff-call-btn secondary">
                <i class="fas fa-shopping-cart"></i>
                Xem giỏ hàng
            </a>
        </div>
    </div>
</section>
