<?php

namespace App\Core;

class Upload
{
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public static function save(array $file, string $prefix = 'file'): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        // Empty optional file field (name blank)
        if (trim((string) ($file['name'] ?? '')) === '' || (int) ($file['size'] ?? 0) <= 0) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException(self::uploadErrorMessage((int) $file['error']));
        }

        $maxBytes = (int) config('upload_max_mb', 10) * 1024 * 1024;
        if (($file['size'] ?? 0) > $maxBytes) {
            throw new \RuntimeException('File too large. Max ' . config('upload_max_mb') . 'MB.');
        }

        $allowed = self::allowedExtensions();
        $ext = self::detectExtension($file);
        if ($ext === '' || !in_array($ext, $allowed, true)) {
            throw new \RuntimeException(
                'Invalid file type' . ($ext !== '' ? " (.{$ext})" : '') .
                '. Allowed: ' . implode(', ', $allowed)
            );
        }

        $dir = upload_dir_path();
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (!is_writable($dir)) {
            throw new \RuntimeException('Upload folder is not writable. Check uploads/ permissions.');
        }

        $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $fullPath = $dir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new \RuntimeException('Could not save uploaded file.');
        }

        if (self::isImageExtension($ext)) {
            $optimized = self::optimizeImage($fullPath, $prefix, $dir);
            if ($optimized !== null) {
                if ($optimized !== $filename) {
                    @unlink($fullPath);
                }
                return $optimized;
            }
        }

        return $filename;
    }

    private static function isImageExtension(string $ext): bool
    {
        return in_array($ext, self::IMAGE_EXTENSIONS, true);
    }

    /** @return list<string> */
    private static function allowedExtensions(): array
    {
        $allowed = config('allowed_upload_ext', ['jpg', 'jpeg', 'png', 'webp', 'pdf']);
        if (!is_array($allowed) || $allowed === []) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
        }
        $allowed = array_map(static fn($e) => strtolower(trim((string) $e)), $allowed);
        // Common aliases
        if (in_array('jpg', $allowed, true) || in_array('jpeg', $allowed, true)) {
            $allowed[] = 'jfif';
            $allowed[] = 'jpe';
        }
        return array_values(array_unique($allowed));
    }

    private static function detectExtension(array $file): string
    {
        $name = (string) ($file['name'] ?? '');
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $ext = preg_replace('/[^a-z0-9]/', '', $ext) ?? '';

        // Map aliases
        $aliases = [
            'jfif' => 'jpg',
            'jpe' => 'jpg',
            'jpeg' => 'jpeg',
        ];
        if (isset($aliases[$ext])) {
            // keep jpeg as jpeg, jfif as jpg for storage
            if ($ext === 'jfif' || $ext === 'jpe') {
                $ext = 'jpg';
            }
        }

        if ($ext !== '') {
            return $ext;
        }

        // Fallback: detect from MIME / file contents when extension missing
        $mime = (string) ($file['type'] ?? '');
        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp !== '' && is_file($tmp) && function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $detected = finfo_file($finfo, $tmp) ?: '';
                finfo_close($finfo);
                if ($detected !== '') {
                    $mime = $detected;
                }
            }
        }

        return match (strtolower($mime)) {
            'image/jpeg', 'image/jpg', 'image/pjpeg' => 'jpg',
            'image/png', 'image/x-png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'application/pdf', 'application/x-pdf', 'application/acrobat', 'applications/vnd.pdf', 'text/pdf' => 'pdf',
            default => '',
        };
    }

    private static function uploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File too large for server limits.',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded. Please try again.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server missing temporary upload folder.',
            UPLOAD_ERR_CANT_WRITE => 'Server failed to write uploaded file.',
            UPLOAD_ERR_EXTENSION => 'Upload blocked by server extension.',
            default => 'File upload failed.',
        };
    }

    private static function optimizeImage(string $sourcePath, string $prefix, string $dir): ?string
    {
        if (!extension_loaded('gd') || !function_exists('imagewebp')) {
            return basename($sourcePath);
        }

        $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        $image = self::loadImage($sourcePath, $ext);
        if ($image === null) {
            return basename($sourcePath);
        }

        // Photos need EXIF orientation; signatures often already correct in pixel data
        // and EXIF/auto-rotate was flipping them upside down.
        if ($prefix !== 'signature') {
            $image = self::applyExifOrientation($image, $sourcePath, $ext);
        }
        if ($prefix === 'photo') {
            $image = self::cropPassportPhoto($image);
        } elseif ($prefix === 'signature') {
            $image = self::normalizeSignature($image);
        } else {
            $image = self::resizeIfNeeded($image);
        }

        $webpName = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.webp';
        $webpPath = $dir . '/' . $webpName;
        $quality = (int) config('upload_webp_quality', 82);

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        if (!imagewebp($image, $webpPath, max(50, min(100, $quality)))) {
            imagedestroy($image);
            return basename($sourcePath);
        }

        imagedestroy($image);
        return $webpName;
    }

    /** @return \GdImage|resource|null */
    private static function loadImage(string $path, string $ext)
    {
        return match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path) ?: null,
            'png' => @imagecreatefrompng($path) ?: null,
            'gif' => @imagecreatefromgif($path) ?: null,
            'webp' => @imagecreatefromwebp($path) ?: null,
            default => null,
        };
    }

    /** @param \GdImage|resource $image */
    private static function applyExifOrientation($image, string $path, string $ext)
    {
        if (!in_array($ext, ['jpg', 'jpeg'], true) || !function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        if (empty($exif['Orientation'])) {
            return $image;
        }

        return match ((int) $exif['Orientation']) {
            3 => imagerotate($image, 180, 0) ?: $image,
            6 => imagerotate($image, -90, 0) ?: $image,
            8 => imagerotate($image, 90, 0) ?: $image,
            default => $image,
        };
    }

    /** @param \GdImage|resource $image */
    private static function resizeIfNeeded($image)
    {
        $maxW = (int) config('upload_image_max_width', 1600);
        $maxH = (int) config('upload_image_max_height', 1600);
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxW && $height <= $maxH) {
            return $image;
        }

        $ratio = min($maxW / $width, $maxH / $height);
        $newW = max(1, (int) round($width * $ratio));
        $newH = max(1, (int) round($height * $ratio));

        $resized = imagecreatetruecolor($newW, $newH);
        if ($resized === false) {
            return $image;
        }

        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefilledrectangle($resized, 0, 0, $newW, $newH, $transparent);

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $width, $height);
        imagedestroy($image);

        return $resized;
    }

    /**
     * Scale signature to a clear print size without rotating (avoids upside-down images).
     * @param \GdImage|resource $image
     * @return \GdImage|resource
     */
    private static function normalizeSignature($image)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        if ($width < 1 || $height < 1) {
            return $image;
        }

        // Wide canvas so signature stays readable on admission form
        $outW = 700;
        $outH = 220;
        $ratio = min($outW / $width, $outH / $height);
        $newW = max(1, (int) round($width * $ratio));
        $newH = max(1, (int) round($height * $ratio));

        $final = imagecreatetruecolor($outW, $outH);
        if ($final === false) {
            return self::resizeIfNeeded($image);
        }

        $white = imagecolorallocate($final, 255, 255, 255);
        imagefilledrectangle($final, 0, 0, $outW, $outH, $white);

        $dstX = (int) round(($outW - $newW) / 2);
        $dstY = (int) round(($outH - $newH) / 2);
        imagecopyresampled($final, $image, $dstX, $dstY, 0, 0, $newW, $newH, $width, $height);
        imagedestroy($image);

        return $final;
    }

    /** @param \GdImage|resource $image */
    private static function cropPassportPhoto($image)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        if ($width < 1 || $height < 1) {
            return $image;
        }

        $targetRatio = 100 / 128;
        $sourceRatio = $width / $height;

        if ($sourceRatio > $targetRatio) {
            $cropW = (int) round($height * $targetRatio);
            $cropH = $height;
            $srcX = (int) round(($width - $cropW) / 2);
            $srcY = 0;
        } else {
            $cropW = $width;
            $cropH = (int) round($width / $targetRatio);
            $srcX = 0;
            $srcY = (int) round(($height - $cropH) * 0.2);
            if ($srcY + $cropH > $height) {
                $srcY = max(0, $height - $cropH);
            }
        }

        $outW = 400;
        $outH = 512;
        $final = imagecreatetruecolor($outW, $outH);
        if ($final === false) {
            return self::resizeIfNeeded($image);
        }

        $white = imagecolorallocate($final, 255, 255, 255);
        imagefilledrectangle($final, 0, 0, $outW, $outH, $white);
        imagecopyresampled($final, $image, 0, 0, $srcX, $srcY, $outW, $outH, $cropW, $cropH);
        imagedestroy($image);

        return $final;
    }
}
