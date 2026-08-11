<?php
declare(strict_types=1);

function ensure_staff_call_table(mysqli $mysqli): void
{
    static $ready = false;
    if ($ready) {
        return;
    }

    mysqli_query(
        $mysqli,
        "CREATE TABLE IF NOT EXISTS tbl_goi_nhanvien (
            id_goi INT AUTO_INCREMENT PRIMARY KEY,
            ma_goi VARCHAR(32) NOT NULL UNIQUE,
            session_id VARCHAR(128) NOT NULL,
            ghi_chu VARCHAR(255) DEFAULT NULL,
            ip_address VARCHAR(45) DEFAULT NULL,
            trangthai TINYINT(1) NOT NULL DEFAULT 0,
            ngaygoi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ngayxuly DATETIME DEFAULT NULL,
            INDEX idx_goi_trangthai_ngaygoi (trangthai, ngaygoi),
            INDEX idx_goi_session_trangthai (session_id, trangthai)
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
    );

    $ready = true;
}

function staff_call_generate_code(): string
{
    return 'NV' . date('YmdHis') . random_int(100, 999);
}

function staff_call_fetch_pending_for_session(mysqli $mysqli, string $sessionId): ?array
{
    ensure_staff_call_table($mysqli);

    $stmt = mysqli_prepare(
        $mysqli,
        'SELECT id_goi, ma_goi, ghi_chu, trangthai, ngaygoi
         FROM tbl_goi_nhanvien
         WHERE session_id = ? AND trangthai = 0
         ORDER BY ngaygoi DESC
         LIMIT 1'
    );

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, 's', $sessionId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    return $row ?: null;
}

function staff_call_create(mysqli $mysqli, string $sessionId, string $note = ''): array
{
    ensure_staff_call_table($mysqli);

    $existingCall = staff_call_fetch_pending_for_session($mysqli, $sessionId);
    if ($existingCall !== null) {
        return [
            'is_new' => false,
            'call' => $existingCall,
        ];
    }

    $code = staff_call_generate_code();
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
    $note = trim($note);

    $stmt = mysqli_prepare(
        $mysqli,
        'INSERT INTO tbl_goi_nhanvien (ma_goi, session_id, ghi_chu, ip_address, trangthai, ngaygoi)
         VALUES (?, ?, ?, ?, 0, NOW())'
    );

    if (!$stmt) {
        throw new RuntimeException('Không thể lưu yêu cầu gọi nhân viên.');
    }

    mysqli_stmt_bind_param($stmt, 'ssss', $code, $sessionId, $note, $ipAddress);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return [
        'is_new' => true,
        'call' => [
            'id_goi' => mysqli_insert_id($mysqli),
            'ma_goi' => $code,
            'ghi_chu' => $note,
            'trangthai' => 0,
            'ngaygoi' => date('Y-m-d H:i:s'),
        ],
    ];
}

function staff_call_count_pending(mysqli $mysqli): int
{
    ensure_staff_call_table($mysqli);

    $result = mysqli_query($mysqli, 'SELECT COUNT(*) AS tong FROM tbl_goi_nhanvien WHERE trangthai = 0');
    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    return (int)($row['tong'] ?? 0);
}

function staff_call_fetch_pending(mysqli $mysqli, int $limit = 3): array
{
    ensure_staff_call_table($mysqli);

    $limit = max(1, min(20, $limit));
    $result = mysqli_query(
        $mysqli,
        "SELECT id_goi, ma_goi, ghi_chu, ip_address, trangthai, ngaygoi
         FROM tbl_goi_nhanvien
         WHERE trangthai = 0
         ORDER BY ngaygoi DESC
         LIMIT {$limit}"
    );

    if (!$result) {
        return [];
    }

    $calls = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $calls[] = $row;
    }

    mysqli_free_result($result);

    return $calls;
}

function staff_call_fetch_recent(mysqli $mysqli, int $limit = 100): array
{
    ensure_staff_call_table($mysqli);

    $limit = max(1, min(300, $limit));
    $result = mysqli_query(
        $mysqli,
        "SELECT id_goi, ma_goi, ghi_chu, ip_address, trangthai, ngaygoi, ngayxuly
         FROM tbl_goi_nhanvien
         ORDER BY ngaygoi DESC
         LIMIT {$limit}"
    );

    if (!$result) {
        return [];
    }

    $calls = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $calls[] = $row;
    }

    mysqli_free_result($result);

    return $calls;
}

function staff_call_mark_handled(mysqli $mysqli, int $callId): void
{
    ensure_staff_call_table($mysqli);

    $stmt = mysqli_prepare(
        $mysqli,
        'UPDATE tbl_goi_nhanvien
         SET trangthai = 1, ngayxuly = NOW()
         WHERE id_goi = ?'
    );

    if (!$stmt) {
        return;
    }

    mysqli_stmt_bind_param($stmt, 'i', $callId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
