<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'user_phone' => $this->user_phone,
            'user_email' => $this->email ,
            'user_address' => $this->email ,
            'street' => $this->street ,
            'home_number' => $this->street ,
            'postal_code' => $this->postal_code ,
            'full_price' => $this->full_price,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'order_items' => OrderItemResource::collection($this->orderItems)
        ];
    }
}
