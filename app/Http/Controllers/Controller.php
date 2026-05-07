<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Standard response format for API endpoints
     */
    protected function apiResponse($data, bool $status = true): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $status,
            'data' => $data
        ]);
    }

    /**
     * Backward-compatible alias used by existing controllers.
     */
    protected function checkData($data, bool $status = true): \Illuminate\Http\JsonResponse
    {
        return $this->apiResponse($data, $status);
    }

    /**
     * Shared product search response used in admin/info controllers.
     */
    protected function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $search = (string) $request->query('search', $request->query('q', ''));
        $products = ProductResource::collection(Product::query()->search($search)->latest()->get());

        return $this->apiResponse([
            'total' => $products->count(),
            'products' => $products,
        ]);
    }

    /**
     * Upload file to specified directory
     */
    protected function uploadFile(string $fileInputName, string $directory): string
    {
        $file = request()->file($fileInputName);
        $fileName = time() . '-' . $file->getClientOriginalName();
        $file->move(public_path($directory), $fileName);
        
        return $fileName;
    }

    /**
     * Delete file from specified directory
     */
    protected function deleteFile(string $directory, ?string $fileName): void
    {
        if ($fileName && file_exists(public_path($directory . $fileName))) {
            unlink(public_path($directory . $fileName));
        }
    }
}
