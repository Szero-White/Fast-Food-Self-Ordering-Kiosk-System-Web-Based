<?php

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__));
}

if (!defined('UPLOAD_STORAGE_DIR')) {
    define('UPLOAD_STORAGE_DIR', PROJECT_ROOT . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'uploads');
}

function app_base_url(): string
{
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $base = preg_replace('#/(admincp|view)(/.*)?$#', '', $scriptName);

    if ($base === null || $base === '/') {
        return '';
    }

    return rtrim($base, '/');
}

function public_url(string $path): string
{
    return app_base_url() . '/' . ltrim($path, '/');
}

function asset_url(string $path): string
{
    return public_url('view/assets/' . ltrim($path, '/'));
}

function asset_path(string $path): string
{
    return PROJECT_ROOT . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($path, '/'));
}

function encode_url_path(string $path): string
{
    $segments = array_filter(explode('/', str_replace('\\', '/', $path)), 'strlen');

    return implode('/', array_map('rawurlencode', $segments));
}

function clean_upload_path(string $filename): string
{
    $filename = str_replace('\\', '/', trim($filename));
    $parts = array_filter(explode('/', $filename), static function ($part) {
        return $part !== '' && $part !== '.' && $part !== '..';
    });

    return implode('/', $parts);
}

function upload_url(?string $filename, string $fallback = 'placeholders/news-placeholder.jpg'): string
{
    $filename = clean_upload_path((string)$filename);

    if ($filename === '') {
        return asset_url($fallback);
    }

    if (!is_file(upload_path($filename))) {
        $seedPath = 'seed/uploads/' . basename($filename);
        if (is_file(asset_path($seedPath))) {
            return asset_url($seedPath);
        }

        return asset_url($fallback);
    }

    return public_url('storage/uploads/' . encode_url_path($filename));
}

function upload_path(?string $filename = ''): string
{
    $filename = clean_upload_path((string)$filename);

    if ($filename === '') {
        return UPLOAD_STORAGE_DIR;
    }

    return UPLOAD_STORAGE_DIR . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $filename);
}

function ensure_upload_dir(string $category = ''): void
{
    $dir = upload_path($category);

    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('Không thể tạo thư mục upload.');
    }
}

function sanitize_file_base(string $name): string
{
    $base = pathinfo($name, PATHINFO_FILENAME);
    $base = preg_replace('/[^A-Za-z0-9_-]+/', '-', $base);
    $base = trim((string)$base, '-_');

    $base = $base !== '' ? strtolower($base) : 'image';

    return substr($base, 0, 50);
}

function detect_image_mime(string $tmpPath): string
{
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo !== false) {
            $mime = finfo_file($finfo, $tmpPath);
            finfo_close($finfo);
            if (is_string($mime) && $mime !== '') {
                return $mime;
            }
        }
    }

    $imageInfo = @getimagesize($tmpPath);
    return is_array($imageInfo) && isset($imageInfo['mime']) ? (string)$imageInfo['mime'] : '';
}

function save_uploaded_image(array $file, string $category = 'misc', ?string $namePrefix = null): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Tải ảnh lên thất bại.');
    }

    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        throw new RuntimeException('Ảnh quá lớn. Dung lượng tối đa là 5MB.');
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    $tmpPath = (string)($file['tmp_name'] ?? '');
    if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
        throw new RuntimeException('Ảnh tải lên không hợp lệ.');
    }

    $mime = detect_image_mime($tmpPath);

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Chỉ cho phép ảnh JPG, PNG và WEBP.');
    }

    if (@getimagesize($tmpPath) === false) {
        throw new RuntimeException('File tải lên không phải là ảnh hợp lệ.');
    }

    $category = clean_upload_path($category);
    $base = sanitize_file_base((string)($file['name'] ?? 'image'));
    $prefix = '';
    if (is_string($namePrefix) && trim($namePrefix) !== '') {
        $prefix = sanitize_file_base(str_replace('_', '-', $namePrefix));
    }

    $filenameParts = array_filter([
        $prefix,
        date('YmdHis'),
        bin2hex(random_bytes(4)),
        $base,
    ]);
    $filename = implode('-', $filenameParts) . '.' . $allowed[$mime];
    $storedPath = $category !== '' ? $category . '/' . $filename : $filename;

    ensure_upload_dir($category);

    if (!move_uploaded_file($tmpPath, upload_path($storedPath))) {
        throw new RuntimeException('Không thể lưu ảnh đã tải lên.');
    }

    return $storedPath;
}

function delete_uploaded_image(?string $filename): void
{
    $filename = clean_upload_path((string)$filename);
    if ($filename === '') {
        return;
    }

    if (strpos($filename, '/') === false) {
        return;
    }

    $baseDir = realpath(upload_path());
    $filePath = realpath(upload_path($filename));

    if ($baseDir === false || $filePath === false || strpos($filePath, $baseDir) !== 0) {
        return;
    }

    if (is_file($filePath)) {
        unlink($filePath);
    }
}
