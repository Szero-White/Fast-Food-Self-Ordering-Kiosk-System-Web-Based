<?php
$sql_gt = "SELECT * FROM tbl_gioithieu WHERE id = 1";
$query_gt = mysqli_query($mysqli, $sql_gt);
$dong = mysqli_fetch_array($query_gt);

if (!$dong) {
    $dong = ['id' => 1, 'noidung' => '', 'hinhanh' => ''];
}

$noiDungGioiThieu = htmlspecialchars($dong['noidung'] ?? '', ENT_QUOTES, 'UTF-8');
$hinhAnhGioiThieu = trim((string)($dong['hinhanh'] ?? ''));
?>

<div class="content-card crud-hero info">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon info">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <h4 class="crud-title">Quản lý giới thiệu</h4>
                <p class="crud-subtitle">Cập nhật nội dung trang giới thiệu hiển thị cho khách hàng</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-edit me-2 crud-card-title-icon info"></i>Nội dung giới thiệu</h5>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="modules/thongtinweb/xuly.php?id=1" enctype="multipart/form-data">
                    <?php echo admin_csrf_field(); ?>
                    <div class="form-group-custom">
                        <label class="form-label-custom">Thông tin giới thiệu</label>
                        <textarea name="noidung" class="form-control-custom crud-long-textarea" rows="15" data-editor><?php echo $noiDungGioiThieu; ?></textarea>
                        <small class="crud-muted">Hỗ trợ HTML. Nội dung sẽ hiển thị trên trang giới thiệu cho khách hàng xem.</small>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Hình ảnh giới thiệu</label>
                        <div class="image-upload" role="button" tabindex="0" data-upload-target="hinhanh">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Bấm để chọn ảnh mới</p>
                            <small class="crud-upload-help">Để trống nếu muốn giữ ảnh hiện tại.</small>
                        </div>
                        <input type="file" name="hinhanh" id="hinhanh" accept="image/*" class="crud-upload-input" data-preview-target="preview-intro-image">

                        <div id="preview-intro-image" class="crud-preview mt-3">
                            <p class="crud-muted">Ảnh mới: <span data-file-name></span></p>
                            <img src="" alt="Ảnh giới thiệu mới xem trước">
                        </div>

                        <?php if ($hinhAnhGioiThieu !== '') { ?>
                        <div class="crud-note-box">
                            <p class="mb-2">Ảnh hiện tại:</p>
                            <img src="<?php echo htmlspecialchars(upload_url($hinhAnhGioiThieu), ENT_QUOTES, 'UTF-8'); ?>" alt="Ảnh giới thiệu hiện tại" class="crud-current-image wide">
                        </div>
                        <?php } ?>
                    </div>

                    <div class="d-flex gap-3 mt-4 flex-wrap">
                        <button type="submit" name="themlienhe" class="btn-custom btn-custom-success">
                            <i class="fas fa-save"></i>
                            <span>Lưu thay đổi</span>
                        </button>
                        <a href="index.php" class="btn-custom btn-custom-secondary text-decoration-none">
                            <i class="fas fa-arrow-left"></i>
                            <span>Quay lại</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-card crud-secondary-panel">
            <div class="card-body-custom">
                <h6 class="crud-entity-title mb-3">
                    <i class="fas fa-lightbulb me-2 text-warning"></i>Gợi ý nội dung
                </h6>
                <ul class="crud-muted">
                    <li>Giới thiệu về nhà hàng</li>
                    <li>Lịch sử hình thành</li>
                    <li>Thông tin liên hệ</li>
                    <li>Giờ mở cửa</li>
                    <li>Địa chỉ chi tiết</li>
                </ul>
                <div class="crud-note-box">
                    <p><i class="fas fa-info-circle me-2"></i>Nội dung sẽ hiển thị trên trang /index.php?quanly=gioithieu cho khách hàng xem.</p>
                </div>
            </div>
        </div>
    </div>
</div>
