<?php
declare(strict_types=1);

namespace App;

use finfo;

class Upload
{
    private const MAX_SIZE = 10_485_760; // 10MB
    private const ALLOWED = [
        'application/pdf' => ['pdf'],
        'application/msword' => ['doc'],
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
        'application/vnd.ms-excel' => ['xls'],
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['xlsx'],
        'application/vnd.ms-powerpoint' => ['ppt'],
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => ['pptx'],
        'image/jpeg' => ['jpg','jpeg'],
        'image/png' => ['png'],
        'image/webp' => ['webp'],
        'video/mp4' => ['mp4'],
        'audio/mpeg' => ['mp3'],
    ];

    public static function handle(string $key, bool $required, string $uploadDir): array
    {
        $file = $_FILES[$key] ?? null;
        if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return $required ? ['error' => 'File required'] : ['path' => null];
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Upload failed'];
        }
        if ($file['size'] > self::MAX_SIZE) {
            return ['error' => 'File too large'];
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!isset(self::ALLOWED[$mime])) {
            return ['error' => 'Invalid file type'];
        }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED[$mime], true)) {
            return ['error' => 'Extension mismatch'];
        }
        if (preg_match('/\.[a-z0-9]+\./i', $file['name'])) {
            return ['error' => 'Invalid filename'];
        }
        $uuid = self::uuidv4();
        $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
        $destDir = rtrim($uploadDir, '/') . '/' . current_user_id();
        if (!is_dir($destDir)) {
            mkdir($destDir, 0750, true);
        }
        $destPath = $destDir . '/' . $uuid . '_' . $safeBase . '.' . $ext;
        if (file_exists($destPath)) {
            $archive = rtrim($uploadDir, '/') . '/archive';
            if (!is_dir($archive)) {
                mkdir($archive, 0750, true);
            }
            $archivePath = $archive . '/' . time() . '_' . basename($destPath);
            rename($destPath, $archivePath);
        }
        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            return ['error' => 'Failed to save file'];
        }
        chmod($destPath, 0640);
        return ['path' => $destPath];
    }

    private static function uuidv4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
