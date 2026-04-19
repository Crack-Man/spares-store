<?php

namespace App\Services\Sync;

use App\Models\Order;
use App\Services\Sync\HttpRequestService;

class ExportOrderService
{
    public function __construct(protected HttpRequestService $requestService)
    {
    }

    public function export(Order $order): \Illuminate\Http\Client\Response
    {
        return $this->sendRequest($this->buildPayload($order));
    }

    public function buildPayload(Order $order): array
    {
        return [
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'total_amount' => $order->total_amount,
            'status' => $order->status->label(),
            'items' => $order->items->map(fn ($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
            ])->toArray(),
        ];
    }

    public function sendRequest(array $data): \Illuminate\Http\Client\Response
    {
        $url = config('services.export.order_url');

        return $this->requestService->post($url, $data);
    }
}
