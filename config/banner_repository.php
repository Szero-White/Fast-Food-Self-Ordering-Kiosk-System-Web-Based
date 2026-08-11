<?php
declare(strict_types=1);

function default_banner_rows(): array
{
    return [
        [
            'title' => 'FastFood Kiosk',
            'subtitle' => 'Đặt món nhanh, trải nghiệm như kiosk tại cửa hàng.',
            'image_path' => 'banners/BANNER+BOGO3+SING+NEW.jpg',
            'image_source' => 'asset',
            'sort_order' => 1,
            'is_active' => 1,
        ],
        [
            'title' => 'Món ngon mỗi ngày',
            'subtitle' => 'Hiển thị banner theo dữ liệu quản trị, không cần sửa code.',
            'image_path' => 'banners/0fb9079d3185fd513de3f57ca8d8cec2a429c34d.jpeg',
            'image_source' => 'asset',
            'sort_order' => 2,
            'is_active' => 1,
        ],
        [
            'title' => 'Ưu đãi nổi bật',
            'subtitle' => 'Admin có thể cập nhật banner trực tiếp trên trang quản trị.',
            'image_path' => 'banners/BANNER+LIME+(1).png',
            'image_source' => 'asset',
            'sort_order' => 3,
            'is_active' => 1,
        ],
    ];
}

function ensure_banner_table(mysqli $mysqli): void
{
    static $ready = false;
    if ($ready) {
        return;
    }

    mysqli_query(
        $mysqli,
        "CREATE TABLE IF NOT EXISTS tbl_banner (
            id_banner INT(11) NOT NULL AUTO_INCREMENT,
            title VARCHAR(200) NOT NULL,
            subtitle VARCHAR(255) DEFAULT NULL,
            image_path VARCHAR(255) NOT NULL,
            image_source ENUM('asset','upload') NOT NULL DEFAULT 'asset',
            link VARCHAR(255) DEFAULT NULL,
            sort_order INT(11) NOT NULL DEFAULT 0,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id_banner)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    mysqli_query(
        $mysqli,
        "CREATE TABLE IF NOT EXISTS tbl_banner_setting (
            id TINYINT(1) NOT NULL DEFAULT 1,
            visible_limit INT(11) NOT NULL DEFAULT 0,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    mysqli_query(
        $mysqli,
        "INSERT IGNORE INTO tbl_banner_setting (id, visible_limit) VALUES (1, 0)"
    );

    $countResult = mysqli_query($mysqli, 'SELECT COUNT(*) AS total FROM tbl_banner');
    $row = $countResult ? mysqli_fetch_assoc($countResult) : ['total' => 0];

    if ((int)($row['total'] ?? 0) === 0) {
        $stmt = mysqli_prepare(
            $mysqli,
            'INSERT INTO tbl_banner (title, subtitle, image_path, image_source, sort_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?)'
        );

        foreach (default_banner_rows() as $banner) {
            $title = $banner['title'];
            $subtitle = $banner['subtitle'];
            $imagePath = $banner['image_path'];
            $imageSource = $banner['image_source'];
            $sortOrder = $banner['sort_order'];
            $isActive = $banner['is_active'];

            mysqli_stmt_bind_param(
                $stmt,
                'ssssii',
                $title,
                $subtitle,
                $imagePath,
                $imageSource,
                $sortOrder,
                $isActive
            );
            mysqli_stmt_execute($stmt);
        }

        mysqli_stmt_close($stmt);
    }

    $ready = true;
}

function get_active_banners(mysqli $mysqli): array
{
    ensure_banner_table($mysqli);

    $banners = [];
    $limit = get_banner_display_limit($mysqli);
    $limitClause = $limit > 0 ? ' LIMIT ' . $limit : '';
    $result = mysqli_query($mysqli, 'SELECT * FROM tbl_banner WHERE is_active = 1 ORDER BY sort_order ASC, id_banner ASC' . $limitClause);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $banners[] = $row;
        }
    }

    return $banners;
}

function get_all_banners(mysqli $mysqli): array
{
    ensure_banner_table($mysqli);

    $banners = [];
    $result = mysqli_query($mysqli, 'SELECT * FROM tbl_banner ORDER BY sort_order ASC, id_banner ASC');

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $banners[] = $row;
        }
    }

    return $banners;
}

function get_banner_stats(mysqli $mysqli): array
{
    ensure_banner_table($mysqli);

    $result = mysqli_query(
        $mysqli,
        'SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active_total,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) AS hidden_total
        FROM tbl_banner'
    );

    $row = $result ? mysqli_fetch_assoc($result) : [];

    $total = (int)($row['total'] ?? 0);
    $activeTotal = (int)($row['active_total'] ?? 0);
    $hiddenTotal = (int)($row['hidden_total'] ?? 0);
    $displayLimit = get_banner_display_limit($mysqli);
    $visibleTotal = $displayLimit > 0 ? min($activeTotal, $displayLimit) : $activeTotal;

    return [
        'total' => $total,
        'active_total' => $activeTotal,
        'hidden_total' => $hiddenTotal,
        'display_limit' => $displayLimit,
        'visible_total' => $visibleTotal,
    ];
}

function get_banner_display_limit(mysqli $mysqli): int
{
    ensure_banner_table($mysqli);

    $result = mysqli_query($mysqli, 'SELECT visible_limit FROM tbl_banner_setting WHERE id = 1 LIMIT 1');
    $row = $result ? mysqli_fetch_assoc($result) : [];

    return max(0, (int)($row['visible_limit'] ?? 0));
}

function update_banner_display_limit(mysqli $mysqli, int $visibleLimit): void
{
    ensure_banner_table($mysqli);

    $visibleLimit = max(0, $visibleLimit);
    $stmt = mysqli_prepare(
        $mysqli,
        'INSERT INTO tbl_banner_setting (id, visible_limit)
         VALUES (1, ?)
         ON DUPLICATE KEY UPDATE visible_limit = VALUES(visible_limit)'
    );
    mysqli_stmt_bind_param($stmt, 'i', $visibleLimit);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function find_banner(mysqli $mysqli, int $id): ?array
{
    ensure_banner_table($mysqli);

    $stmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_banner WHERE id_banner = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $banner = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    return $banner ?: null;
}

function banner_image_url(array $banner): string
{
    if (($banner['image_source'] ?? 'asset') === 'upload') {
        return upload_url($banner['image_path'] ?? '');
    }

    return asset_url($banner['image_path'] ?? 'placeholders/news-placeholder.jpg');
}
