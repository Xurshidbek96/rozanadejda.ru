<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Services\ProductAdminService;

class ProductController extends Controller
{

    public $productAdminService;
    public function __construct(ProductAdminService $productAdminService)
    {
        $this->productAdminService = $productAdminService;
    }

    public function index()
    {
        return $this->productAdminService->getAllProducts();
    }

    public function store(ProductStoreRequest $request)
    {
        // return $request ;
        $data = $this->productAdminService->store($request);
        return $this->checkData($data);
    }

    public function show(Product $product)
    {
        $data =  new ProductResource($product);
        return $this->checkData($data);
    }

    public function productUpdate(ProductUpdateRequest $request, Product $product)
    {
        $data = $this->productAdminService->update($request, $product);
        return $this->checkData($data);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        return $this->productUpdate($request, $product);
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            $imagePath = public_path('images/products') . '/' . $image->filename;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $product->categories()->detach();
        $product->images()->delete();
        $data = $product->delete();

        return $this->checkData($data);
    }

    public function searchProduct(Request $request){
        return $this->search($request);
    }
}
