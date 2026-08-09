<?php
require_once __DIR__ . '/../../../config/banner_repository.php';

ensure_banner_table($mysqli);

$bannerCssVersion = filemtime(__DIR__ . '/../../css_admin/pages/banner-admin.css');
$bannerStats = get_banner_stats($mysqli);
$displayLimit = (int)$bannerStats['display_limit'];
$displayLimitLabel = $displayLimit > 0 ? $displayLimit . ' ảnh đầu tiên đang bật' : 'Tất cả ảnh đang bật';
?>

<link rel="stylesheet" href="css_admin/pages/banner-admin.css?v=<?php echo $bannerCssVersion; ?>">

<div class="content-card crud-hero">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon">
                <i class="fas fa-panorama"></i>
            </div>
            <div>
                <h4 class="crud-title">Quản lý banner trang chủ</h4>
                <p class="crud-subtitle">Cập nhật banner hiển thị ở carousel trang khách hàng</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="content-card banner-stat-card mb-0">
            <div class="card-body-custom d-flex align-items-center justify-content-between">
                <div>
                    <p class="banner-stat-label">Tổng ảnh banner</p>
                    <h3 class="banner-stat-number"><?php echo (int)$bannerStats['total']; ?></h3>
                </div>
                <div class="banner-stat-icon primary">
                    <i class="fas fa-images"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card banner-stat-card mb-0">
            <div class="card-body-custom d-flex align-items-center justify-content-between">
                <div>
                    <p class="banner-stat-label">Đang bật</p>
                    <h3 class="banner-stat-number success"><?php echo (int)$bannerStats['active_total']; ?></h3>
                </div>
                <div class="banner-stat-icon success">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card banner-stat-card mb-0">
            <div class="card-body-custom d-flex align-items-center justify-content-between">
                <div>
                    <p class="banner-stat-label">Đang ẩn</p>
                    <h3 class="banner-stat-number neutral"><?php echo (int)$bannerStats['hidden_total']; ?></h3>
                </div>
                <div class="banner-stat-icon neutral">
                    <i class="fas fa-eye-slash"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-sliders-h me-2 crud-card-title-icon"></i>Cấu hình hiển thị banner</h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlybanner/xuly.php" class="banner-config-form">
            <div class="form-group-custom banner-config-input-group">
                <label class="form-label-custom">Số ảnh hiển thị trên trang chủ</label>
                <input type="number" name="visible_limit" class="form-control-custom" min="0" max="20" value="<?php echo $displayLimit; ?>">
            </div>
            <div class="banner-config-summary">
                <p class="banner-config-label">Đang đưa lên trang chủ</p>
                <strong><?php echo (int)$bannerStats['visible_total']; ?> / <?php echo (int)$bannerStats['active_total']; ?> ảnh</strong>
                <small><?php echo htmlspecialchars($displayLimitLabel, ENT_QUOTES, 'UTF-8'); ?></small>
            </div>
            <div class="banner-config-actions">
                <button type="submit" name="capnhat_cauhinh_banner" class="btn-custom btn-custom-primary">
                    <i class="fas fa-save"></i>
                    <span>Lưu cấu hình</span>
                </button>
            </div>
        </form>
        <p class="banner-config-note">
            Nhập 0 để hiển thị tất cả banner đang bật. Nếu nhập số lớn hơn số banner đang bật, hệ thống tự hiển thị toàn bộ banner đang bật.
        </p>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-plus me-2 crud-card-title-icon"></i>Thêm banner mới</h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlybanner/xuly.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tiêu đề <span class="crud-required">*</span></label>
                        <input type="text" name="title" class="form-control-custom" placeholder="Nhập tiêu đề banner" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Liên kết khi bấm banner</label>
                        <input type="text" name="link" class="form-control-custom" placeholder="VD: index.php?quanly=trangchu">
                    </div>
                </div>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Mô tả ngắn</label>
                <input type="text" name="subtitle" class="form-control-custom" placeholder="Nhập mô tả ngắn cho banner">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" class="form-control-custom" value="0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Trạng thái</label>
                        <select name="is_active" class="form-control-custom">
                            <option value="1">Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Ảnh banner</label>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="image-upload" role="button" tabindex="0" data-upload-target="banner-image">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Bấm để chọn ảnh banner hoặc kéo thả vào đây</p>
                            <small class="crud-upload-help">Hỗ trợ: JPG, PNG, WEBP. Kích thước khuyến nghị 1200x400.</small>
                        </div>
                        <input type="file" name="hinhanh" id="banner-image" accept="image/*" class="crud-upload-input" data-preview-target="preview-banner-add">
                    </div>
                    <div class="col-md-4">
                        <div id="preview-banner-add" class="crud-preview banner">
                            <p class="crud-muted">Ảnh đã chọn: <span data-file-name></span></p>
                            <img src="" alt="Ảnh banner xem trước">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 flex-wrap">
                <button type="submit" name="thembanner" class="btn-custom btn-custom-primary">
                    <i class="fas fa-plus"></i>
                    <span>Thêm banner</span>
                </button>
                <a href="?action=quanlybanner&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
                    <i class="fas fa-redo"></i>
                    <span>Nhập lại</span>
                </a>
            </div>
        </form>
    </div>
</div>
