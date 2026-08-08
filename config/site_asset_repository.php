<?php
declare(strict_types=1);

function default_site_assets(): array
{
    return [
        'site_logo' => [
            'label' => 'Logo trang web',
            'image_path' => 'brand/logo.jpg',
            'image_source' => 'asset',
        ],
    ];
}

function ensure_site_asset_table(mysqli $mysqli): void
{
    static $ready = false;
    if ($ready) {
        return;
    }

    mysqli_query(
        $mysqli,
        "CREATE TABLE IF NOT EXISTS tbl_site_asset (
            asset_key VARCHAR(100) NOT NULL,
            label VARCHAR(200) NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            image_source ENUM('asset','upload') NOT NULL DEFAULT 'asset',
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (asset_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $stmt = mysqli_prepare(
        $mysqli,
        'INSERT IGNORE INTO tbl_site_asset (asset_key, label, image_path, image_source)
         VALUES (?, ?, ?, ?)'
    );

    foreach (default_site_assets() as $key => $asset) {
        mysqli_stmt_bind_param($stmt, 'ssss', $key, $asset['label'], $asset['image_path'], $asset['image_source']);
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    $ready = true;
}

function get_site_assets(mysqli $mysqli): array
{
    ensure_site_asset_table($mysqli);

    $assets = [];
    $result = mysqli_query($mysqli, 'SELECT * FROM tbl_site_asset ORDER BY asset_key ASC');

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $assets[$row['asset_key']] = $row;
        }
    }

    return $assets;
}

function find_site_asset(mysqli $mysqli, string $key): ?array
{
    ensure_site_asset_table($mysqli);

    $stmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_site_asset WHERE asset_key = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $key);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $assetKey, $label, $imagePath, $imageSource, $updatedAt);
    $found = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$found) {
        return null;
    }

    return [
        'asset_key' => $assetKey,
        'label' => $label,
        'image_path' => $imagePath,
        'image_source' => $imageSource,
        'updated_at' => $updatedAt,
    ];
}

function site_asset_url(mysqli $mysqli, string $key): string
{
    $asset = find_site_asset($mysqli, $key);

    if ($asset === null) {
        $defaults = default_site_assets();
        $fallback = $defaults[$key]['image_path'] ?? 'placeholders/news-placeholder.jpg';
        return asset_url($fallback);
    }

    if (($asset['image_source'] ?? 'asset') === 'upload') {
        return upload_url($asset['image_path'] ?? '');
    }

    return asset_url($asset['image_path'] ?? 'placeholders/news-placeholder.jpg');
}
