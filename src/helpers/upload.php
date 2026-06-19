<?php
/**
 * Image upload helper
 * Uploads to public/assets/images/uploads/, returns relative path
 * (e.g. "images/uploads/abc123.jpg") suitable for the asset() helper.
 */

function handle_image_upload(string $field, ?string $existing = null): array {
    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['path' => $existing, 'uploaded' => false];
    }

    $file = $_FILES[$field];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'Lỗi tải ảnh lên (mã ' . $file['error'] . ')'];
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return ['error' => 'Ảnh quá lớn (tối đa 5MB)'];
    }

    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $extMap  = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if (!in_array($mime, $allowed, true)) {
        return ['error' => 'Định dạng ảnh không hợp lệ (chỉ jpg/png/gif/webp)'];
    }

    $uploadDir = __DIR__ . '/../../public/assets/images/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $ext      = $extMap[$mime];
    $fileName = uniqid('img_', true) . '.' . $ext;
    $target   = $uploadDir . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        return ['error' => 'Không thể lưu ảnh lên server'];
    }

    return ['path' => 'images/uploads/' . $fileName, 'uploaded' => true];
}
