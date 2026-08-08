<?php
require_once __DIR__ . '/../../../config/banner_repository.php';
ensure_banner_table($mysqli);
$bannerStats = get_banner_stats($mysqli);
$displayLimit = (int)$bannerStats['display_limit'];
$displayLimitLabel = $displayLimit > 0 ? $displayLimit . ' ảnh đầu tiên đang bật' : 'Tất cả ảnh đang bật';
?>

<style>
    .banner-stat-card .card-body-custom {
        min-height: 92px;
        padding: 22px 24px;
    }

    .banner-stat-label,
    .banner-config-label {
        margin: 0;
        color: #888;
        font-size: 13px;
        font-weight: 600;
    }

    .banner-stat-number {
        margin: 6px 0 0;
        color: #333;
        font-size: 30px;
        font-weight: 800;
        line-height: 1;
    }

    .banner-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .banner-config-form {
        display: grid;
        grid-template-columns: minmax(260px, 1fr) minmax(260px, 0.85fr) auto;
        gap: 20px;
        align-items: end;
    }

    .banner-config-summary {
        background: rgba(102,126,234,0.08);
        border: 1px solid rgba(102,126,234,0.16);
        border-radius: 12px;
        padding: 14px 16px;
        min-height: 64px;
    }

    .banner-config-summary strong {
        display: block;
        margin-top: 4px;
        color: #333;
        font-size: 18px;
        line-height: 1.2;
    }

    .banner-config-summary small,
    .banner-config-note {
        color: #888;
        font-size: 13px;
    }

    .banner-config-actions {
        display: flex;
        justify-content: flex-end;
    }

    .banner-config-actions .btn-custom {
        min-height: 48px;
        padding: 12px 22px;
        white-space: nowrap;
    }

    @media (max-width: 992px) {
        .banner-config-form {
            grid-template-columns: 1fr;
        }

        .banner-config-actions {
            justify-content: flex-start;
        }
    }
</style>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%); border: 1px solid rgba(102,126,234,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-panorama" style="color: white; font-size: 24px;"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 700; color: #333;">Quản lý banner trang chủ</h4>
                <p style="margin: 0; color: #888; font-size: 14px;">Cập nhật banner hiển thị ở carousel trang khách hàng</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="content-card banner-stat-card" style="margin-bottom: 0;">
            <div class="card-body-custom d-flex align-items-center justify-content-between">
                <div>
                    <p class="banner-stat-label">Tổng ảnh banner</p>
                    <h3 class="banner-stat-number"><?php echo $bannerStats['total']; ?></h3>
                </div>
                <div class="banner-stat-icon" style="background: rgba(102,126,234,0.12); color: #667eea;">
                    <i class="fas fa-images"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card banner-stat-card" style="margin-bottom: 0;">
            <div class="card-body-custom d-flex align-items-center justify-content-between">
                <div>
                    <p class="banner-stat-label">Đang bật</p>
                    <h3 class="banner-stat-number" style="color: #27ae60;"><?php echo $bannerStats['active_total']; ?></h3>
                </div>
                <div class="banner-stat-icon" style="background: rgba(39,174,96,0.12); color: #27ae60;">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card banner-stat-card" style="margin-bottom: 0;">
            <div class="card-body-custom d-flex align-items-center justify-content-between">
                <div>
                    <p class="banner-stat-label">Đang ẩn</p>
                    <h3 class="banner-stat-number" style="color: #7f8c8d;"><?php echo $bannerStats['hidden_total']; ?></h3>
                </div>
                <div class="banner-stat-icon" style="background: rgba(127,140,141,0.12); color: #7f8c8d;">
                    <i class="fas fa-eye-slash"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-sliders-h me-2" style="color: #667eea;"></i>Cấu hình hiển thị banner</h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlybanner/xuly.php" class="banner-config-form">
            <div>
                <div class="form-group-custom" style="margin-bottom: 0;">
                    <label class="form-label-custom">Số ảnh hiển thị trên trang chủ</label>
                    <input type="number" name="visible_limit" class="form-control-custom" min="0" max="20" value="<?php echo $displayLimit; ?>">
                </div>
            </div>
            <div class="banner-config-summary">
                    <p class="banner-config-label">Đang đưa lên trang chủ</p>
                    <strong><?php echo $bannerStats['visible_total']; ?> / <?php echo $bannerStats['active_total']; ?> ảnh</strong>
                    <small style="color: #888;"><?php echo htmlspecialchars($displayLimitLabel, ENT_QUOTES, 'UTF-8'); ?></small>
            </div>
            <div class="banner-config-actions">
                <button type="submit" name="capnhat_cauhinh_banner" class="btn-custom btn-custom-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-save me-2"></i>Lưu cấu hình
                </button>
            </div>
        </form>
        <p class="banner-config-note" style="margin: 14px 0 0;">
            Nhập 0 để hiển thị tất cả banner đang bật. Nếu nhập số lớn hơn số banner đang bật, hệ thống tự hiển thị toàn bộ banner đang bật.
        </p>
    </div>
</div>

<!-- Add Banner Form -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-plus me-2" style="color: #667eea;"></i>Thêm banner mới</h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlybanner/xuly.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tiêu đề <span style="color: #e74c3c;">*</span></label>
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
                        <div class="image-upload" onclick="document.getElementById('banner-image').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Bấm để chọn ảnh banner hoặc kéo thả vào đây</p>
                            <small style="color: #aaa;">Hỗ trợ: JPG, PNG, WEBP. Kích thước khuyến nghị 1200x400.</small>
                            <input type="file" name="hinhanh" id="banner-image" accept="image/*" style="display: none;" onchange="previewBannerImage(this, 'preview-banner-add')">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div id="preview-banner-add" style="display: none;">
                            <p style="color: #888; font-size: 13px; margin-bottom: 10px;">Ảnh đã chọn:</p>
                            <img id="img-preview-banner-add" src="" style="width: 100%; max-width: 240px; height: 110px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" name="thembanner" class="btn-custom btn-custom-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-plus me-2"></i>Thêm banner
                </button>
                <a href="?action=quanlybanner&query=them" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center">
                    <i class="fas fa-redo me-2"></i>Nhập lại
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewBannerImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('img-' + previewId).src = e.target.result;
            document.getElementById(previewId).style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
