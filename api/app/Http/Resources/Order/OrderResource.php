<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Order
 */
class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'total_amount' => $this->total_amount,
            'status' => $this->status->label(),
            'items' => OrderItemResource::collection($this->items),
            'created_at' => $this->created_at,
            'confirmed_at' => $this->confirmed_at,
            'shipped_at' => $this->shipped_at,
        ];
    }
}
