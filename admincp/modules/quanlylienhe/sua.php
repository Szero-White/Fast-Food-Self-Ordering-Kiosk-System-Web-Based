<?php
$idLienHe = (int)($_GET['idlienhe'] ?? 0);
$stmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_lienhe WHERE id_lienhe = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $idLienHe);
mysqli_stmt_execute($stmt);
$query_sua_lh = mysqli_stmt_get_result($stmt);
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(79,172,254,0.1) 0%, rgba(0,242,254,0.1) 100%); border: 1px solid rgba(79,172,254,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-eye" style="color: white; font-size: 24px;"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 700; color: #333;">Chi tiết liên hệ</h4>
                <p style="margin: 0; color: #888; font-size: 14px;">Xem và xử lý ý kiến khách hàng</p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Detail -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-envelope-open me-2" style="color: #4facfe;"></i>Nội dung liên hệ</h5>
    </div>
    <div class="card-body-custom">
        <?php
        while ($row = mysqli_fetch_array($query_sua_lh)) {
            // Cập nhật trạng thái thành đã xem
            if ($row['trangthai'] == 'chua_xem') {
                $stmtUpdate = mysqli_prepare($mysqli, "UPDATE tbl_lienhe SET trangthai = 'da_xem' WHERE id_lienhe = ?");
                mysqli_stmt_bind_param($stmtUpdate, 'i', $row['id_lienhe']);
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
        <div class="row">
            <div class="col-lg-5">
                <div class="form-section" style="background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);">
                    <h6 style="font-weight: 600; color: #333; margin-bottom: 20px;">
                        <i class="fas fa-user me-2" style="color: #667eea;"></i>Thông tin người liên hệ
                    </h6>
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user" style="color: white; font-size: 16px;"></i>
                            </div>
                            <div>
                                <small style="color: #888;">Họ tên</small>
                                <div class="info-value"><?php echo $tenLienHe; ?></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope" style="color: white; font-size: 16px;"></i>
                            </div>
                            <div>
                                <small style="color: #888;">Email</small>
                                <div class="info-value"><?php echo $emailLienHe; ?></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-phone" style="color: white; font-size: 16px;"></i>
                            </div>
                            <div>
                                <small style="color: #888;">Số điện thoại</small>
                                <div class="info-value"><?php echo $sdtLienHe; ?></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-tag" style="color: white; font-size: 16px;"></i>
                            </div>
                            <div>
                                <small style="color: #888;">Loại liên hệ</small>
                                <div class="info-value"><?php echo $loaiLienHe; ?></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar" style="color: white; font-size: 16px;"></i>
                            </div>
                            <div>
                                <small style="color: #888;">Ngày gửi</small>
                                <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($row['ngaygui'])) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="form-section">
                    <h6 style="font-weight: 600; color: #333; margin-bottom: 20px;">
                        <i class="fas fa-comment-alt me-2" style="color: #4facfe;"></i>Nội dung tin nhắn
                    </h6>
                    <div style="background: #f8f9fa; border-radius: 12px; padding: 20px; min-height: 200px; line-height: 1.8;">
                        <?php echo nl2br($noiDungLienHe); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-3 mt-4">
            <a href="?action=quanlylienhe&query=lietke" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
            <a href="mailto:<?php echo $mailtoLienHe; ?>" class="btn-custom btn-custom-primary text-decoration-none d-inline-flex align-items-center">
                <i class="fas fa-reply me-2"></i>Trả lời email
            </a>
        </div>
        <?php
        }
        mysqli_stmt_close($stmt);
        ?>
    </div>
</div>
