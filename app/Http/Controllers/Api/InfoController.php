<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function getAllCategories(){
        $categories = Category::all() ;
        return $this->checkData($categories);
    }

    public function getCategoryProducts($id)
    {
        $products = ProductResource::collection(Product::where('category_id', $id)->latest()->get());
        $data['total'] = $products->count();
        $data['products'] = $products;
        return $this->checkData($data);
    }

    public function getAllProducts()
    {
        $products = ProductResource::collection(Product::latest()->get());
        return $this->checkData($products);
    }

    public function getshowProduct(Product $product)
    {
        $data = new ProductResource($product);
        return $this->checkData($data);
    }

    public function getSearchProduct(Request $request){
        return $this->search($request);
    }

    public function postOrderCreate(Request $request){
        // Create new order
        $order = Order::create([
            'user_name' => $request->user_name,
            'user_phone' => $request->user_phone,
            'status' => 'waiting'
        ]);
        $full_price = 0 ;

        // Create order items
        foreach($request->products as $item){
            $product = Product::find($item['id']);
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'total' => $item['quantity'] * $product->price,
            ]);
            $product->update([
                'quantity' => $product->quantity - $item['quantity']
            ]) ;
            $full_price += $orderItem->total;
        }

        // Update order
        $order->update([
            'full_price' => $full_price
        ]);
        return $this->checkData($order);
    }
}
