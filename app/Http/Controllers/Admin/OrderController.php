<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getAll()
    {
        $orders = Order::latest()->get();
        return $this->checkData($orders);
    }

    public function getShow(Order $order)
    {
        $data = new OrderResource($order);
        $order->update(['adminShow' => 1]) ;
        return $this->checkData($data) ;
    }

    public function postUpdate(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required'
        ]) ;
        $order->update([
            'status' => $request->status
        ]);

        if($order->status == 'reject'){
            foreach($order->orderItems as $item){
                $product = Product::find($item->product_id);
                $product->update([
                    'quantity' => $product->quantity + $item->quantity
                ]) ;
            }
        }
        return $this->checkData($order);
    }
}
