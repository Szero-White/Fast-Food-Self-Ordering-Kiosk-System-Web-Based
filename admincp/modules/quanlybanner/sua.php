<?php
require_once __DIR__ . '/../../../config/banner_repository.php';
$banner = find_banner($mysqli, (int)($_GET['idbanner'] ?? 0));

if ($banner === null) {
    echo '<div class="content-card"><div class="card-body-custom">Banner không tồn tại.</div></div>';
    return;
}
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%); border: 1px solid rgba(102,126,234,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-edit" style="color: white; font-size: 24px;"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 700; color: #333;">Sửa banner</h4>
                <p style="margin: 0; color: #888; font-size: 14px;">Cập nhật nội dung và hình ảnh banner trang chủ</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-pen me-2" style="color: #667eea;"></i>Thông tin banner</h5>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="modules/quanlybanner/xuly.php?idbanner=<?php echo (int)$banner['id_banner']; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Tiêu đề <span style="color: #e74c3c;">*</span></label>
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
                        <div class="image-upload" onclick="document.getElementById('banner-image-edit').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Bấm để chọn ảnh banner mới</p>
                            <small style="color: #aaa;">Để trống nếu không muốn thay đổi ảnh hiện tại.</small>
                            <input type="file" name="hinhanh" id="banner-image-edit" accept="image/*" style="display: none;">
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" name="suabanner" class="btn-custom btn-custom-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-save me-2"></i>Lưu thay đổi
                        </button>
                        <a href="index.php?action=quanlybanner&query=them" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-card" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="card-body-custom">
                <h6 style="font-weight: 700; color: #333; margin-bottom: 15px;"><i class="fas fa-image me-2" style="color: #667eea;"></i>Ảnh hiện tại</h6>
                <img src="<?php echo htmlspecialchars(banner_image_url($banner), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($banner['title'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div style="margin-top: 16px; padding: 15px; background: white; border-radius: 12px; border-left: 4px solid #667eea;">
                    <p style="color: #667eea; font-size: 13px; margin: 0;"><i class="fas fa-info-circle me-2"></i>Banner đang hiển thị trên carousel trang chủ nếu trạng thái là Hiển thị.</p>
                </div>
            </div>
        </div>
    </div>
</div>
