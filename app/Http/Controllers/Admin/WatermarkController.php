<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\WatermarkService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class WatermarkController extends Controller
{
    public function __construct(
        private readonly WatermarkService $watermarkService
    ) {}

    /**
     * Current effective watermark used for product image processing.
     */
    public function show()
    {
        $path = $this->watermarkService->resolveWatermarkAbsolutePath();
        if ($path === null) {
            return $this->apiResponse([
                'configured' => false,
                'url' => null,
                'is_custom' => false,
                'message' => 'No watermark file found. Upload via POST/PUT /api/admin/watermark or add legacy images/products/water.png',
            ]);
        }

        $relative = $this->watermarkService->hasCustomWatermark()
            ? WatermarkService::CUSTOM_RELATIVE
            : WatermarkService::LEGACY_RELATIVE;

        return $this->apiResponse([
            'configured' => true,
            'url' => asset($relative),
            'is_custom' => $this->watermarkService->hasCustomWatermark(),
            'updated_at' => date('c', filemtime($path)),
        ]);
    }

    /**
     * Upload a new watermark (JPEG/PNG/WebP). Stored as PNG; used for future product uploads.
     */
    public function store(Request $request)
    {
        $this->watermarkService->replaceCustomWatermark($this->validatedWatermarkUpload($request));

        return $this->apiResponse([
            'url' => $this->watermarkService->customWatermarkPublicUrl(),
            'message' => 'Watermark saved. New product images will use this file.',
        ]);
    }

    /**
     * Replace custom watermark (same as POST; old file removed before save).
     */
    public function update(Request $request)
    {
        $this->watermarkService->replaceCustomWatermark($this->validatedWatermarkUpload($request));

        return $this->apiResponse([
            'url' => $this->watermarkService->customWatermarkPublicUrl(),
            'message' => 'Watermark updated. New product images will use this file.',
        ]);
    }

    /**
     * Delete custom watermark file from disk. Legacy images/products/water.png is not touched.
     */
    public function destroy()
    {
        $had = $this->watermarkService->hasCustomWatermark();
        $this->watermarkService->removeCustomWatermarkFile();

        return $this->apiResponse([
            'deleted' => $had,
            'message' => $had
                ? 'Custom watermark file removed from disk.'
                : 'No custom watermark file was present.',
        ]);
    }

    private function validatedWatermarkUpload(Request $request): UploadedFile
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        return $request->file('image');
    }
}
