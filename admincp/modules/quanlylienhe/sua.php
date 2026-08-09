<?php
$idLienHe = (int)($_GET['idlienhe'] ?? 0);
$stmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_lienhe WHERE id_lienhe = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $idLienHe);
mysqli_stmt_execute($stmt);
$query_sua_lh = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($query_sua_lh);

if (!$row) {
    mysqli_stmt_close($stmt);
    echo '<div class="content-card"><div class="card-body-custom">Liên hệ không tồn tại.</div></div>';
    return;
}

if (($row['trangthai'] ?? '') === 'chua_xem') {
    $stmtUpdate = mysqli_prepare($mysqli, "UPDATE tbl_lienhe SET trangthai = 'da_xem' WHERE id_lienhe = ?");
    mysqli_stmt_bind_param($stmtUpdate, 'i', $idLienHe);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);
}

$tenLienHe = htmlspecialchars($row['ten'] ?: '(Chưa có tên)', ENT_QUOTES, 'UTF-8');
$emailLienHe = htmlspecialchars($row['email'] ?: '(Chưa có email)', ENT_QUOTES, 'UTF-8');
$sdtLienHe = htmlspecialchars($row['sodienthoai'] ?: '(Chưa có SĐT)', ENT_QUOTES, 'UTF-8');
$loaiLienHe = htmlspecialchars($row['loai'] ?: '(Chưa phân loại)', ENT_QUOTES, 'UTF-8');
$noiDungLienHe = htmlspecialchars($row['noidung'] ?: '(Chưa có nội dung)', ENT_QUOTES, 'UTF-8');
$mailtoLienHe = htmlspecialchars($row['email'] ?: '', ENT_QUOTES, 'UTF-8');
?>

<div class="content-card crud-hero contact">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon contact">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <h4 class="crud-title">Chi tiết liên hệ</h4>
                <p class="crud-subtitle">Xem và xử lý ý kiến khách hàng</p>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-envelope-open me-2 crud-card-title-icon contact"></i>Nội dung liên hệ</h5>
    </div>
    <div class="card-body-custom">
        <div class="row">
            <div class="col-lg-5">
                <div class="form-section contact-detail-panel">
                    <h6 class="crud-entity-title mb-4">
                        <i class="fas fa-user me-2 crud-card-title-icon"></i>Thông tin người liên hệ
                    </h6>
                    <div class="contact-info-list">
                        <div class="contact-info-item">
                            <span class="contact-info-icon"><i class="fas fa-user"></i></span>
                            <div>
                                <small class="contact-info-label">Họ tên</small>
                                <div class="info-value"><?php echo $tenLienHe; ?></div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <span class="contact-info-icon email"><i class="fas fa-envelope"></i></span>
                            <div>
                                <small class="contact-info-label">Email</small>
                                <div class="info-value"><?php echo $emailLienHe; ?></div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <span class="contact-info-icon phone"><i class="fas fa-phone"></i></span>
                            <div>
                                <small class="contact-info-label">Số điện thoại</small>
                                <div class="info-value"><?php echo $sdtLienHe; ?></div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <span class="contact-info-icon type"><i class="fas fa-tag"></i></span>
                            <div>
                                <small class="contact-info-label">Loại liên hệ</small>
                                <div class="info-value"><?php echo $loaiLienHe; ?></div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <span class="contact-info-icon date"><i class="fas fa-calendar"></i></span>
                            <div>
                                <small class="contact-info-label">Ngày gửi</small>
                                <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($row['ngaygui'])); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="form-section">
                    <h6 class="crud-entity-title mb-4">
                        <i class="fas fa-comment-alt me-2 crud-card-title-icon contact"></i>Nội dung tin nhắn
                    </h6>
                    <div class="contact-message-box">
                        <?php echo nl2br($noiDungLienHe); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-3 mt-4 flex-wrap">
            <a href="?action=quanlylienhe&query=lietke" class="btn-custom btn-custom-secondary text-decoration-none">
                <i class="fas fa-arrow-left"></i>
                <span>Quay lại danh sách</span>
            </a>
            <a href="mailto:<?php echo $mailtoLienHe; ?>" class="btn-custom btn-custom-primary text-decoration-none">
                <i class="fas fa-reply"></i>
                <span>Trả lời email</span>
            </a>
        </div>
    </div>
</div>
<?php mysqli_stmt_close($stmt); ?>
