<?php
require_once __DIR__ . '/../../../config/site_asset_repository.php';

$assets = get_site_assets($mysqli);
$systemAssetsCssVersion = filemtime(__DIR__ . '/../../css_admin/pages/system-assets-admin.css');
$systemAssetsJsVersion = filemtime(__DIR__ . '/../../js_admin/pages/system-assets-admin.js');

$assetDescriptions = [
    'site_logo' => [
        'icon' => 'fas fa-store',
        'description' => 'Logo xuất hiện ở màn hình chào, thanh điều hướng và chân trang khách hàng.',
        'hint' => 'Khuyến nghị ảnh PNG/JPG nền sáng, tỉ lệ gần vuông để hiển thị đẹp trên mọi màn hình.',
    ],
    'admin_logo' => [
        'icon' => 'fas fa-user-shield',
        'description' => 'Logo hiển thị trong sidebar, trang đăng nhập và khu vực quản trị.',
        'hint' => 'Nên dùng ảnh PNG/JPG dạng vuông để vừa khung logo admin.',
    ],
    'site_favicon' => [
        'icon' => 'fas fa-star',
        'description' => 'Biểu tượng nhỏ trên tab trình duyệt của website và trang quản trị.',
        'hint' => 'Nên dùng ảnh vuông 256x256 hoặc 512x512 để hiển thị rõ ở kích thước nhỏ.',
    ],
];
?>

<link rel="stylesheet" href="css_admin/pages/system-assets-admin.css?v=<?php echo $systemAssetsCssVersion; ?>">

<div class="content-card crud-hero">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon">
                <i class="fas fa-images"></i>
            </div>
            <div>
                <h4 class="crud-title">Quản lý hình ảnh hệ thống</h4>
                <p class="crud-subtitle">Cập nhật logo, favicon và hình ảnh nhận diện dùng chung toàn hệ thống</p>
            </div>
        </div>
    </div>
</div>

<div class="asset-grid">
    <?php foreach ($assets as $asset) {
        $assetKey = (string)$asset['asset_key'];
        $safeAssetKey = htmlspecialchars($assetKey, ENT_QUOTES, 'UTF-8');
        $detail = $assetDescriptions[$assetKey] ?? [
            'icon' => 'fas fa-image',
            'description' => 'Hình ảnh nhận diện đang được hệ thống sử dụng.',
            'hint' => 'Chọn ảnh rõ nét, dung lượng vừa phải để website tải nhanh.',
        ];
        $fileNameId = 'asset-file-name-' . preg_replace('/[^a-z0-9_-]/i', '-', $assetKey);
    ?>
        <div class="asset-card">
            <div class="asset-card-header">
                <div class="asset-icon">
                    <i class="<?php echo htmlspecialchars($detail['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                </div>
                <div class="asset-card-title">
                    <h5><?php echo htmlspecialchars($asset['label'], ENT_QUOTES, 'UTF-8'); ?></h5>
                    <p><?php echo htmlspecialchars($detail['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>

            <div class="asset-card-body">
                <div class="asset-preview-panel">
                    <div class="asset-preview-frame">
                        <img src="<?php echo htmlspecialchars(site_asset_url($mysqli, $assetKey), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($asset['label'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="asset-meta">
                        <strong>Ảnh đang sử dụng</strong>
                        <span><?php echo $safeAssetKey; ?></span>
                        <p><?php echo htmlspecialchars($detail['hint'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>

                <form method="POST" action="modules/quanlyhinhanh/xuly.php" enctype="multipart/form-data">
                    <?php echo admin_csrf_field(); ?>
                    <input type="hidden" name="asset_key" value="<?php echo $safeAssetKey; ?>">
                    <div class="asset-upload-control">
                        <label class="asset-file-label" for="asset-file-<?php echo $safeAssetKey; ?>">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>
                                <strong>Chọn ảnh mới</strong>
                                <span class="asset-file-name" id="<?php echo $fileNameId; ?>">Chưa chọn tệp nào</span>
                            </span>
                        </label>
                        <input
                            type="file"
                            name="hinhanh"
                            id="asset-file-<?php echo $safeAssetKey; ?>"
                            class="asset-file-input"
                            accept="image/*"
                            required
                            data-asset-file-input
                            data-file-name-target="<?php echo $fileNameId; ?>"
                        >
                        <button type="submit" name="capnhathinhanh" class="btn-custom btn-custom-primary">
                            <i class="fas fa-save"></i>
                            <span>Lưu ảnh</span>
                        </button>
                    </div>
                    <p class="asset-help">Ảnh mới sẽ được lưu trong storage/uploads/site bằng tên file rõ nghĩa và tự động áp dụng cho các giao diện đang sử dụng mục ảnh này.</p>
                </form>
            </div>
        </div>
    <?php } ?>
</div>

<script src="js_admin/pages/system-assets-admin.js?v=<?php echo $systemAssetsJsVersion; ?>"></script>
