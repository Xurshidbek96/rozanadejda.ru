<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function dashboard(){
        $dashboard['accepted_orders'] = Order::where('status', 'accept')->count() ;
        $orders = Order::where('status', 'accept')->get() ;

        $products_quantity = 0 ;
        $full_price = 0 ;
        foreach ($orders as $order) {
            $orderItems = OrderItem::where('order_id', $order->id)->get();
            foreach ($orderItems as $orderItem) {
                $products_quantity += $orderItem->quantity;
            }

            $full_price += $order->full_price ;
        }

        $dashboard['products_quantity'] = $products_quantity ;
        $dashboard['full_price'] = $full_price ;

        return $this->checkData($dashboard) ;
    }

    public function getAllSlides(){
        $slides = Slide::all() ;
        return $this->checkData($slides);
    }
    public function getAllCategories(){
        $categories = Category::all() ;
        return $this->checkData($categories);
    }

    public function getCategoryProducts($id)
    {
        $products = ProductResource::collection(
            Product::whereHas('categories', function ($query) use ($id) {
                $query->where('category_id', $id);
            })->latest()->get()
        );
        $data['total'] = $products->count();
        $data['products'] = $products;
        return $this->checkData($data);
    }

    public function getAllProducts()
    {
        $products = ProductResource::collection(Product::latest()->get());
        return $this->checkData([
            'total' => $products->count(),
            'products' => $products,
        ]);
    }

    public function getshowProduct($slug)
    {
        $product = Product::where('slug', $slug)->firstOr();
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
            'user_email' => $request->user_email,
            'user_address' => $request->user_address,
            'street' => $request->street,
            'home_number' => $request->home_number,
            'postal_code' => $request->postal_code,
            'status' => 'waiting'
        ]);
        $full_price = 0 ;

        // Create order items
        foreach($request->products as $item){
            $product = Product::find($item['product_id']);
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
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

    public function getDailyOrderDelete(){
        $deleted = Order::where('status', 'reject')->delete();
        return $this->checkData(['deleted' => $deleted]);
    }
}
