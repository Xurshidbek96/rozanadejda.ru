<?php

namespace App\Http\Services;

use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Product;
use Intervention\Image\Facades\Image as ImageManager;

class ProductAdminService
{
    public function getAllProducts()
    {
        $products = ProductResource::collection(Product::latest()->paginate(10));

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

        $requestData = $request->except('files');
        $product = Product::create($requestData);
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $filename = time() . '-' . $file->getClientOriginalName();
                $filePath = public_path('images/products/' . $filename);

                // Move the file to the public path
                $file->move(public_path('images/products'), $filename);

                // Open the file with Intervention Image
                $image = ImageManager::make($filePath);

                // Suv belgisini yuklaymiz
                $watermark = ImageManager::make(public_path('images/products/water.png'));

                // Suv belgisining o'lchamini asosiy rasmning o'lchamiga moslashtiramiz
                $watermarkSize = min($image->width() * 0.3, $image->height() * 0.3); // Suv belgisining hajmi rasmning 10% bo'ladi
                $watermark->resize($watermarkSize, $watermarkSize, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // Suv belgisini asosiy rasmga qo'shamiz
                $image->insert($watermark, 'bottom-right', 15, 15);

                // Save the image with the watermark
                $image->save($filePath);

                $images[] = Image::create([
                    'product_id' => $product->id,
                    'filename' => $filename
                ]);
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
        $requestData = $request->except('files');
        $product->update($requestData);

        // Rasmlarni yangilash
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $images = [];
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
                $filename = time() . '-' . $file->getClientOriginalName();
                $filePath = public_path('images/products/' . $filename);

                // Move the file to the public path
                $file->move(public_path('images/products'), $filename);

                // Open the file with Intervention Image
                $image = ImageManager::make($filePath);

                // Suv belgisini yuklaymiz
                $watermark = ImageManager::make(public_path('images/products/water.png'));

                // Suv belgisining o'lchamini asosiy rasmning o'lchamiga moslashtiramiz
                $watermarkSize = min($image->width() * 0.3, $image->height() * 0.3); // Suv belgisining hajmi rasmning 10% bo'ladi
                $watermark->resize($watermarkSize, $watermarkSize, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // Suv belgisini asosiy rasmga qo'shamiz
                $image->insert($watermark, 'bottom-right', 15, 15);

                // Save the image with the watermark
                $image->save($filePath);

                $images[] = Image::create([
                    'product_id' => $product->id,
                    'filename' => $filename
                ]);
            }
        }
        $product->load('images');
        return $product;
    }
}
