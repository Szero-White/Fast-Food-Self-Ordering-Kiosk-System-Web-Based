<?php
require_once __DIR__ . '/../../../config/site_asset_repository.php';
$assets = get_site_assets($mysqli);

$assetDescriptions = [
    'site_logo' => [
        'icon' => 'fas fa-store',
        'description' => 'Logo xuất hiện ở màn hình chào, thanh điều hướng và chân trang khách hàng.',
        'hint' => 'Khuyến nghị ảnh PNG/JPG nền sáng, tỉ lệ gần vuông để hiển thị đẹp trên mọi màn hình.',
    ],
];
?>

<style>
    .asset-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 22px;
    }

    .asset-card {
        border: 1px solid rgba(245, 87, 108, 0.16);
        border-radius: 16px;
        background: linear-gradient(135deg, #ffffff 0%, #fbfbff 100%);
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .asset-card-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 22px 24px;
        border-bottom: 1px solid #eef0f5;
    }

    .asset-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .asset-card-title h5 {
        margin: 0;
        color: #222;
        font-weight: 700;
    }

    .asset-card-title p {
        margin: 4px 0 0;
        color: #888;
        font-size: 13px;
        line-height: 1.5;
    }

    .asset-card-body {
        padding: 24px;
    }

    .asset-preview-panel {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 18px;
        border-radius: 14px;
        background: #f8f9ff;
        border: 1px solid #edf0fb;
        margin-bottom: 22px;
    }

    .asset-preview-frame {
        width: 138px;
        height: 96px;
        border-radius: 14px;
        background: white;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        flex-shrink: 0;
    }

    .asset-preview-frame img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .asset-meta strong {
        display: block;
        color: #333;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .asset-meta span {
        display: inline-block;
        font-family: monospace;
        color: #f5576c;
        background: rgba(245, 87, 108, 0.1);
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .asset-meta p {
        color: #777;
        font-size: 13px;
        line-height: 1.55;
        margin: 0;
    }

    .asset-upload-control {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 14px;
        align-items: stretch;
    }

    .asset-file-input {
        display: none;
    }

    .asset-file-label {
        min-height: 62px;
        border: 2px dashed #e1e5f0;
        border-radius: 16px;
        background: white;
        padding: 12px 16px;
        color: #555;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.25s ease;
    }

    .asset-file-label:hover {
        border-color: #f5576c;
        box-shadow: 0 8px 20px rgba(245, 87, 108, 0.08);
        transform: translateY(-1px);
    }

    .asset-file-label i {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: rgba(245, 87, 108, 0.1);
        color: #f5576c;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .asset-file-label strong {
        display: block;
        color: #333;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .asset-file-name {
        display: block;
        color: #888;
        font-size: 13px;
        line-height: 1.35;
        max-width: 420px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .asset-help {
        color: #888;
        font-size: 13px;
        line-height: 1.55;
        margin: 12px 0 0;
    }

    @media (max-width: 768px) {
        .asset-preview-panel,
        .asset-upload-control {
            grid-template-columns: 1fr;
        }

        .asset-preview-panel {
            flex-direction: column;
            align-items: flex-start;
        }

        .asset-upload-control .btn-custom {
            justify-content: center;
            width: 100%;
        }
    }
</style>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(240,147,251,0.1) 0%, rgba(245,87,108,0.1) 100%); border: 1px solid rgba(245,87,108,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-images" style="color: white; font-size: 24px;"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 700; color: #333;">Quản lý hình ảnh hệ thống</h4>
                <p style="margin: 0; color: #888; font-size: 14px;">Cập nhật logo và hình ảnh nhận diện hiển thị trên website</p>
            </div>
        </div>
    </div>
</div>

<div class="asset-grid">
    <?php foreach ($assets as $asset) {
        $detail = $assetDescriptions[$asset['asset_key']] ?? [
            'icon' => 'fas fa-image',
            'description' => 'Hình ảnh nhận diện đang được hệ thống sử dụng.',
            'hint' => 'Chọn ảnh rõ nét, dung lượng vừa phải để website tải nhanh.',
        ];
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
                        <img src="<?php echo htmlspecialchars(site_asset_url($mysqli, $asset['asset_key']), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($asset['label'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="asset-meta">
                        <strong>Ảnh đang sử dụng</strong>
                        <span><?php echo htmlspecialchars($asset['asset_key'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <p><?php echo htmlspecialchars($detail['hint'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>

                <form method="POST" action="modules/quanlyhinhanh/xuly.php" enctype="multipart/form-data">
                    <input type="hidden" name="asset_key" value="<?php echo htmlspecialchars($asset['asset_key'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="asset-upload-control">
                        <label class="asset-file-label" for="asset-file-<?php echo htmlspecialchars($asset['asset_key'], ENT_QUOTES, 'UTF-8'); ?>">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>
                                <strong>Chọn ảnh mới</strong>
                                <span class="asset-file-name" id="asset-file-name-<?php echo htmlspecialchars($asset['asset_key'], ENT_QUOTES, 'UTF-8'); ?>">Chưa chọn tệp nào</span>
                            </span>
                        </label>
                        <input type="file" name="hinhanh" id="asset-file-<?php echo htmlspecialchars($asset['asset_key'], ENT_QUOTES, 'UTF-8'); ?>" class="asset-file-input" accept="image/*" required onchange="updateAssetFileName(this, 'asset-file-name-<?php echo htmlspecialchars($asset['asset_key'], ENT_QUOTES, 'UTF-8'); ?>')">
                        <button type="submit" name="capnhathinhanh" class="btn-custom btn-custom-primary" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-save me-2"></i>Lưu ảnh
                        </button>
                    </div>
                    <p class="asset-help">Ảnh mới sẽ được lưu trong storage/uploads/site và tự động áp dụng cho giao diện khách hàng.</p>
                </form>
            </div>
        </div>
    <?php } ?>
</div>

<script>
function updateAssetFileName(input, targetId) {
    var target = document.getElementById(targetId);
    if (!target) {
        return;
    }

    target.textContent = input.files && input.files.length > 0 ? input.files[0].name : 'Chưa chọn tệp nào';
}
</script>
