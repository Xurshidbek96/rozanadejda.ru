<?php

namespace App\Http\Services;

use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image as ImageManager;

class ProductAdminService
{
    public function __construct(
        private readonly WatermarkService $watermarkService
    ) {}

    /**
     * Only these types get the PNG watermark (GIF animation and video stay untouched).
     */
    private function shouldApplyWatermark(UploadedFile $file): bool
    {
        $mime = strtolower((string) $file->getMimeType());

        return in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'], true);
    }

    private function storeUploadedMedia(UploadedFile $file, Product $product): void
    {
        $applyWatermark = $this->shouldApplyWatermark($file);
        $safeBase = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $filename = time() . '-' . uniqid('', true) . '-' . $safeBase;
        $directory = public_path('images/products');
        $filePath = $directory . '/' . $filename;

        $file->move($directory, $filename);

        if ($applyWatermark) {
            $watermarkPath = $this->watermarkService->resolveWatermarkAbsolutePath();
            if ($watermarkPath !== null) {
                $image = ImageManager::make($filePath);
                $watermark = ImageManager::make($watermarkPath);
                $watermarkSize = min($image->width() * 0.3, $image->height() * 0.3);
                $watermark->resize($watermarkSize, $watermarkSize, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->insert($watermark, 'bottom-right', 15, 15);
                $image->save($filePath);
            }
        }

        Image::create([
            'product_id' => $product->id,
            'filename' => $filename,
        ]);
    }

    public function getAllProducts()
    {
        $products = ProductResource::collection(Product::latest()->get());

        return $products;
    }

    public function store($request)
    {
        $request->validate([
            'name_uz' => 'unique:products,name_uz,except,id',
            'name_ru' => 'unique:products,name_ru,except,id',
            'name_en' => 'unique:products,name_en,except,id',
            'slug' => 'required'
        ]);

        $requestData = $request->except('files', 'categories');
        $product = Product::create($requestData);
        $product->categories()->attach($request->categories);
        
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $files = is_array($files) ? $files : [$files];
            foreach ($files as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    $this->storeUploadedMedia($file, $product);
                }
            }
        } else {
            $images = null;
        }

        return $product;
    }

    public function update($request, $product)
    {
        // return $request ;
        $request->validate([
            'name_uz' => 'unique:products,name_uz,except,id',
            'name_ru' => 'unique:products,name_ru,except,id',
            'name_en' => 'unique:products,name_en,except,id',
            // 'slug' => 'required'
        ]);
        $requestData = $request->except('files', 'categories');
        $product->update($requestData);
        $product->categories()->sync($request->categories);

        // Rasmlarni yangilash
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $files = is_array($files) ? $files : [$files];
            // Eskilarni o'chirish va ma'lumotlar bazasidan o'chirish
            $product->images->each(function ($image) {
                $imagePath = public_path('images/products') . '/' . $image->filename;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $image->delete();
            });

            // Yangi rasmlarni qo'shish
            foreach ($files as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    $this->storeUploadedMedia($file, $product);
                }
            }
        }
        $product->load('images');
        return $product;
    }
}
