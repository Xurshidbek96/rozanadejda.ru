<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = ProductResource::collection(Product::latest()->paginate(10));
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
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
        } else $images = null;
        return $this->checkData($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $data =  new ProductResource($product);
        return $this->checkData($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function productUpdate(ProductUpdateRequest $request, Product $product)
    {
        // return $request ;
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
        // Ma'lumotlarni yangilangan holatda o'qish
        $product->load('images');
        return $this->checkData($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            $imagePath = public_path('images/products') . '/' . $image->filename;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $data = $product->delete();

        return $this->checkData($data);
    }

    public function searchProduct(Request $request){
        return $this->search($request);
    }
}
