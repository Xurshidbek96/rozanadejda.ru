<?php

namespace App\Http\Services;

use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
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

    private function detectMediaType(UploadedFile $file): string
    {
        $mime = strtolower((string) $file->getMimeType());

        if (str_starts_with($mime, 'video/')) {
            return 'video';
        }

        if ($mime === 'image/gif') {
            return 'gif';
        }

        return 'image';
    }

    private function storeUploadedMedia(UploadedFile $file, Product $product, int $sortOrder): void
    {
        // Barcha UploadedFile o‘qimlari move() dan oldin — move() tmp faylni yo‘q qiladi.
        $applyWatermark = $this->shouldApplyWatermark($file);
        $mediaType = $this->detectMediaType($file);
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
            'sort_order' => $sortOrder,
            'media_type' => $mediaType,
        ]);
    }

    /**
     * Form-data / JSON da `categories` ba'zan "1,2,3" yoki bitta string sifatida keladi — sync() uchun massiv kerak.
     *
     * @param  mixed  $categories
     * @return array<int, int>
     */
    private function normalizedCategoryIds($categories): array
    {
        if ($categories === null || $categories === '') {
            return [];
        }

        if (is_string($categories)) {
            $categories = preg_split('/\s*,\s*/', $categories, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        }

        if (! is_array($categories)) {
            return [];
        }

        $ids = [];
        foreach ($categories as $item) {
            if (is_array($item)) {
                $ids = array_merge($ids, $this->normalizedCategoryIds($item));

                continue;
            }
            if (is_string($item) && str_contains($item, ',')) {
                $ids = array_merge($ids, $this->normalizedCategoryIds($item));

                continue;
            }
            $id = (int) $item;
            if ($id > 0) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }

    public function getAllProducts()
    {
        $products = ProductResource::collection(Product::latest()->get());

        return $products;
    }

    public function store($request)
    {
        $request->validate([
            'name_uz' => 'unique:products,name_uz',
            'name_ru' => 'unique:products,name_ru',
            'name_en' => 'unique:products,name_en',
            'slug' => 'required',
        ]);

        $requestData = $request->except('files', 'categories');
        $product = Product::create($requestData);
        $product->categories()->attach($this->normalizedCategoryIds($request->input('categories')));
        
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $files = is_array($files) ? $files : [$files];
            $order = 0;
            foreach ($files as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    $this->storeUploadedMedia($file, $product, $order++);
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
            'name_uz' => ['sometimes', Rule::unique('products', 'name_uz')->ignore($product->id)],
            'name_ru' => ['sometimes', Rule::unique('products', 'name_ru')->ignore($product->id)],
            'name_en' => ['sometimes', Rule::unique('products', 'name_en')->ignore($product->id)],
        ]);
        $requestData = $request->except('files', 'categories');
        $product->update($requestData);
        $product->categories()->sync($this->normalizedCategoryIds($request->input('categories')));

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
            $order = 0;
            foreach ($files as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    $this->storeUploadedMedia($file, $product, $order++);
                }
            }
        }
        $product->load('images');
        return $product;
    }
}
