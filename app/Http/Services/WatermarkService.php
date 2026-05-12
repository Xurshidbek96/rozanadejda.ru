<?php

namespace App\Http\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image as ImageManager;

class WatermarkService
{
    public const CUSTOM_RELATIVE = 'images/watermark/current.png';

    public const LEGACY_RELATIVE = 'images/products/water.png';

    /**
     * Absolute path to the PNG file used when compositing watermarks on product photos.
     * Prefers admin-uploaded custom watermark; falls back to legacy bundled asset.
     */
    public function resolveWatermarkAbsolutePath(): ?string
    {
        $custom = public_path(self::CUSTOM_RELATIVE);
        if (is_file($custom)) {
            return $custom;
        }

        $legacy = public_path(self::LEGACY_RELATIVE);

        return is_file($legacy) ? $legacy : null;
    }

    public function hasCustomWatermark(): bool
    {
        return is_file(public_path(self::CUSTOM_RELATIVE));
    }

    /**
     * Persist a new watermark image (re-encoded as PNG for consistent alpha compositing).
     */
    public function storeCustomWatermark(UploadedFile $file): void
    {
        $directory = public_path('images/watermark');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $target = public_path(self::CUSTOM_RELATIVE);
        $image = ImageManager::make($file->getRealPath());
        $image->save($target, 90, 'png');
    }

    public function customWatermarkPublicUrl(): string
    {
        return asset(self::CUSTOM_RELATIVE);
    }
}
