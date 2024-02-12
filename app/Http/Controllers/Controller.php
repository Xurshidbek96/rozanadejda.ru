<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function checkData($data){
        if (!$data )
            return response()->json(['status' => false, 'data' => null]);

        return response()->json(['status' => true, 'data' => $data]);
    }

    public function search($request){
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $products = Product::where('name_uz', 'like', '%' . $search . '%')
            ->orWhere('name_ru', 'like', '%' . $search . '%')
            ->orWhere('name_en', 'like', '%' . $search . '%') ;

        $data['total'] = $products->count();
        $data['products'] = $products->latest()->get();
        return $this->checkData($data);
    }

    public function upload_file($file_name, $folder)
    {
        $file = request()->file($file_name);
        $fileName = time() . '-' . $file->getClientOriginalName();
        $file->move($folder, $fileName);
        return $fileName;
    }

    public function unlink_file($folder, $file_name)
    {
        if (isset($file_name) && file_exists(public_path($folder . $file_name))) {
            unlink(public_path($folder . $file_name));
        }
    }
}
