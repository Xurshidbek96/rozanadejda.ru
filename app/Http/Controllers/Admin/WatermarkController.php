<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\WatermarkService;
use Illuminate\Http\Request;

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
                'message' => 'No watermark file found. Upload via POST /api/admin/watermark or add legacy images/products/water.png',
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
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $this->watermarkService->storeCustomWatermark($request->file('image'));

        return $this->apiResponse([
            'url' => $this->watermarkService->customWatermarkPublicUrl(),
            'message' => 'Watermark updated. New product images will use this file.',
        ]);
    }
}
