<?php
require_once __DIR__ . '/../../../config/banner_repository.php';
$banners = get_all_banners($mysqli);
?>

<!-- Banner Table -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-list me-2" style="color: #667eea;"></i>Danh sách banner</h5>
    </div>
    <div class="card-body-custom" style="padding: 0;">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">STT</th>
                        <th style="width: 190px;">Ảnh</th>
                        <th>Thông tin banner</th>
                        <th style="width: 110px;">Thứ tự</th>
                        <th style="width: 130px;">Trạng thái</th>
                        <th style="width: 120px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($banners as $index => $banner) { ?>
                    <tr>
                        <td><strong>#<?php echo $index + 1; ?></strong></td>
                        <td>
                            <img src="<?php echo htmlspecialchars(banner_image_url($banner), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($banner['title'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 150px; height: 74px; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #333;"><?php echo htmlspecialchars($banner['title'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <small style="color: #888;"><?php echo htmlspecialchars((string)$banner['subtitle'], ENT_QUOTES, 'UTF-8'); ?></small>
                            <?php if (!empty($banner['link'])) { ?>
                                <div style="margin-top: 6px;">
                                    <span style="font-family: monospace; background: rgba(102,126,234,0.1); padding: 4px 10px; border-radius: 6px; color: #667eea; font-size: 12px;"><?php echo htmlspecialchars($banner['link'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                            <?php } ?>
                        </td>
                        <td><?php echo (int)$banner['sort_order']; ?></td>
                        <td>
                            <?php if ((int)$banner['is_active'] === 1) { ?>
                                <span style="background: rgba(39,174,96,0.12); color: #27ae60; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Hiển thị</span>
                            <?php } else { ?>
                                <span style="background: rgba(149,165,166,0.12); color: #7f8c8d; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Ẩn</span>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="action-group" style="justify-content: center;">
                                <a href="?action=quanlybanner&query=sua&idbanner=<?php echo (int)$banner['id_banner']; ?>" class="btn-action edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="modules/quanlybanner/xuly.php?idbanner=<?php echo (int)$banner['id_banner']; ?>" class="btn-action delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
