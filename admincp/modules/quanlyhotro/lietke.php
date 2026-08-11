<?php
require_once __DIR__ . '/../../../config/staff_call_repository.php';

$staffCallCssVersion = filemtime(__DIR__ . '/../../css_admin/pages/staff-call-admin.css');
$staffCalls = staff_call_fetch_recent($mysqli, 100);
$pendingCount = 0;

foreach ($staffCalls as $staffCall) {
    if ((int)$staffCall['trangthai'] === 0) {
        $pendingCount++;
    }
}
?>

<link rel="stylesheet" href="css_admin/pages/staff-call-admin.css?v=<?php echo $staffCallCssVersion; ?>">

<div class="content-card staff-call-hero-card">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="staff-call-page-icon">
                    <i class="fas fa-bell-concierge"></i>
                </div>
                <div>
                    <h4 class="staff-call-page-title">Yêu cầu gọi nhân viên</h4>
                    <p class="staff-call-page-subtitle">Theo dõi khách đang cần hỗ trợ tại kiosk</p>
                </div>
            </div>
            <div class="staff-call-summary">
                <span>Đang chờ</span>
                <strong><?php echo $pendingCount; ?></strong>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-headset me-2 staff-call-section-icon"></i>Hàng chờ hỗ trợ</h5>
    </div>
    <div class="card-body-custom staff-call-table-body">
        <?php if (empty($staffCalls)) { ?>
            <div class="staff-call-empty">
                <i class="fas fa-circle-check"></i>
                <strong>Chưa có yêu cầu hỗ trợ</strong>
                <span>Khi khách bấm “Gọi NV”, yêu cầu sẽ xuất hiện ở đây.</span>
            </div>
        <?php } else { ?>
            <div class="table-container">
                <table class="custom-table staff-call-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã yêu cầu</th>
                            <th>Thời gian gọi</th>
                            <th>Ghi chú</th>
                            <th>IP</th>
                            <th class="staff-call-status-column">Trạng thái</th>
                            <th class="staff-call-action-column">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staffCalls as $staffCall) {
                            $isPending = (int)$staffCall['trangthai'] === 0;
                            $statusClass = $isPending ? 'pending' : 'handled';
                            $statusLabel = $isPending ? 'Đang chờ' : 'Đã xử lý';
                        ?>
                            <tr id="staff-call-<?php echo (int)$staffCall['id_goi']; ?>" class="<?php echo $isPending ? 'staff-call-row-pending' : ''; ?>">
                                <td><strong>#<?php echo (int)$staffCall['id_goi']; ?></strong></td>
                                <td>
                                    <span class="staff-call-code"><?php echo htmlspecialchars((string)$staffCall['ma_goi'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </td>
                                <td>
                                    <span class="staff-call-time"><?php echo date('d/m/Y H:i', strtotime((string)$staffCall['ngaygoi'])); ?></span>
                                    <?php if (!$isPending && !empty($staffCall['ngayxuly'])) { ?>
                                        <small>Hoàn tất lúc <?php echo date('H:i', strtotime((string)$staffCall['ngayxuly'])); ?></small>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (trim((string)($staffCall['ghi_chu'] ?? '')) !== '') { ?>
                                        <?php echo htmlspecialchars((string)$staffCall['ghi_chu'], ENT_QUOTES, 'UTF-8'); ?>
                                    <?php } else { ?>
                                        <span class="staff-call-muted">Khách cần hỗ trợ tại kiosk</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo htmlspecialchars((string)($staffCall['ip_address'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <span class="staff-call-status <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                                </td>
                                <td>
                                    <?php if ($isPending) { ?>
                                        <form method="POST" action="modules/quanlyhotro/xuly.php?idgoi=<?php echo (int)$staffCall['id_goi']; ?>&action=daxuly" class="crud-inline-action">
                                            <?php echo admin_csrf_field(); ?>
                                            <button type="submit" class="staff-call-action done">
                                                <i class="fas fa-check"></i>
                                                Đã xử lý
                                            </button>
                                        </form>
                                    <?php } else { ?>
                                        <span class="staff-call-action disabled">
                                            <i class="fas fa-check-double"></i>
                                            Hoàn tất
                                        </span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>
