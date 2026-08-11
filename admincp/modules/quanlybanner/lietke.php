<?php
require_once __DIR__ . '/../../../config/banner_repository.php';

$banners = get_all_banners($mysqli);
?>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-list me-2 crud-card-title-icon"></i>Danh sách banner</h5>
    </div>
    <div class="card-body-custom crud-table-body">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="banner-column-index">STT</th>
                        <th class="banner-column-image">Ảnh</th>
                        <th>Thông tin banner</th>
                        <th class="banner-column-sort">Thứ tự</th>
                        <th class="banner-column-status">Trạng thái</th>
                        <th class="banner-column-actions">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($banners as $index => $banner) { ?>
                    <tr>
                        <td><strong>#<?php echo $index + 1; ?></strong></td>
                        <td>
                            <img
                                src="<?php echo htmlspecialchars(banner_image_url($banner), ENT_QUOTES, 'UTF-8'); ?>"
                                alt="<?php echo htmlspecialchars($banner['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                class="crud-banner-thumb"
                            >
                        </td>
                        <td>
                            <div class="crud-entity-title"><?php echo htmlspecialchars($banner['title'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <small class="crud-muted"><?php echo htmlspecialchars((string)$banner['subtitle'], ENT_QUOTES, 'UTF-8'); ?></small>
                            <?php if (!empty($banner['link'])) { ?>
                                <div class="mt-2">
                                    <span class="crud-code"><?php echo htmlspecialchars($banner['link'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                            <?php } ?>
                        </td>
                        <td><?php echo (int)$banner['sort_order']; ?></td>
                        <td>
                            <?php if ((int)$banner['is_active'] === 1) { ?>
                                <span class="crud-pill success">Hiển thị</span>
                            <?php } else { ?>
                                <span class="crud-pill neutral">Ẩn</span>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="action-group crud-action-center">
                                <a href="?action=quanlybanner&query=sua&idbanner=<?php echo (int)$banner['id_banner']; ?>" class="btn-action edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="modules/quanlybanner/xuly.php?idbanner=<?php echo (int)$banner['id_banner']; ?>" class="crud-inline-action">
                                    <?php echo admin_csrf_field(); ?>
                                    <button type="submit" class="btn-action delete" title="Xóa" data-confirm="Bạn có chắc chắn muốn xóa banner này?">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
