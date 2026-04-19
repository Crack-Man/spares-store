<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'total_amount' => $this->total_amount,
            'status' => $this->status->label(),
            'items' => OrderItemResource::collection($this->items),
            'created_at' => $this->created_at,
        ];
    }
}
