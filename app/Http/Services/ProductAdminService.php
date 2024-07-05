<?php

namespace App\Http\Services;

use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Product;

class ProductAdminService
{
    public function getAllProducts(){
        $products = ProductResource::collection(Product::latest()->paginate(10));

        return $products;
    }

    public function store($request){

        $request->validate([
            'name_uz' => 'unique:products,name_uz,except,id',
            'name_ru' => 'unique:products,name_ru,except,id',
            'name_en' => 'unique:products,name_en,except,id',
            'slug' => 'required'
        ]) ;

        $requestData = $request->except('files');
        $product = Product::create($requestData);
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $filename = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('images/products'), $filename);
                $images[] = Image::create([
                    'product_id' => $product->id,
                    'filename' => $filename
                ]);
            }
        }
        else $images = null;

        return $product ;
    }

    public function update($request, $product){
        // return $request ;
        $request->validate([
            'name_uz' => 'unique:products,name_uz,except,id',
            'name_ru' => 'unique:products,name_ru,except,id',
            'name_en' => 'unique:products,name_en,except,id',
            // 'slug' => 'required'
        ]) ;
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
                $file->move(public_path('images/products'), $filename);

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

