<?php
require_once __DIR__ . '/../../../config/banner_repository.php';

$bannerCssVersion = filemtime(__DIR__ . '/../../css_admin/pages/banner-admin.css');
$banner = find_banner($mysqli, (int)($_GET['idbanner'] ?? 0));

if ($banner === null) {
    echo '<div class="content-card"><div class="card-body-custom">Banner không tồn tại.</div></div>';
    return;
}
?>

<link rel="stylesheet" href="css_admin/pages/banner-admin.css?v=<?php echo $bannerCssVersion; ?>">

<div class="content-card crud-hero">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h4 class="crud-title">Sửa banner</h4>
                <p class="crud-subtitle">Cập nhật nội dung và hình ảnh banner trang chủ</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-pen me-2 crud-card-title-icon"></i>Thông tin banner</h5>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="modules/quanlybanner/xuly.php?idbanner=<?php echo (int)$banner['id_banner']; ?>" enctype="multipart/form-data">
                    <?php echo admin_csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Tiêu đề <span class="crud-required">*</span></label>
                                <input type="text" name="title" class="form-control-custom" value="<?php echo htmlspecialchars($banner['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Liên kết khi bấm banner</label>
                                <input type="text" name="link" class="form-control-custom" value="<?php echo htmlspecialchars((string)$banner['link'], ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Mô tả ngắn</label>
                        <input type="text" name="subtitle" class="form-control-custom" value="<?php echo htmlspecialchars((string)$banner['subtitle'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Thứ tự hiển thị</label>
                                <input type="number" name="sort_order" class="form-control-custom" value="<?php echo (int)$banner['sort_order']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Trạng thái</label>
                                <select name="is_active" class="form-control-custom">
                                    <option value="1" <?php echo (int)$banner['is_active'] === 1 ? 'selected' : ''; ?>>Hiển thị</option>
                                    <option value="0" <?php echo (int)$banner['is_active'] === 0 ? 'selected' : ''; ?>>Ẩn</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Ảnh banner mới</label>
                        <div class="image-upload" role="button" tabindex="0" data-upload-target="banner-image-edit">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Bấm để chọn ảnh banner mới</p>
                            <small class="crud-upload-help">Để trống nếu không muốn thay đổi ảnh hiện tại.</small>
                        </div>
                        <input type="file" name="hinhanh" id="banner-image-edit" accept="image/*" class="crud-upload-input" data-preview-target="preview-banner-edit">
                        <div id="preview-banner-edit" class="crud-preview banner mt-3">
                            <p class="crud-muted">Ảnh mới: <span data-file-name></span></p>
                            <img src="" alt="Ảnh banner mới xem trước">
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4 flex-wrap">
                        <button type="submit" name="suabanner" class="btn-custom btn-custom-primary">
                            <i class="fas fa-save"></i>
                            <span>Lưu thay đổi</span>
                        </button>
                        <a href="index.php?action=quanlybanner&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
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
                    <i class="fas fa-image me-2 crud-card-title-icon"></i>Ảnh hiện tại
                </h6>
                <img
                    src="<?php echo htmlspecialchars(banner_image_url($banner), ENT_QUOTES, 'UTF-8'); ?>"
                    alt="<?php echo htmlspecialchars($banner['title'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="crud-banner-current-image"
                >
                <div class="crud-note-box">
                    <p><i class="fas fa-info-circle me-2"></i>Banner đang hiển thị trên carousel trang chủ nếu trạng thái là Hiển thị.</p>
                </div>
            </div>
        </div>
    </div>
</div>
