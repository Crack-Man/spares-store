<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Sync\ExportOrderService;
use Illuminate\Support\Facades\Log;

class ExportOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Order $order)
    {
    }

    public function handle(ExportOrderService $exportOrderService): void
    {
        $response = $exportOrderService->export($this->order);

        Log::info('Заказ выгружен', [
            'order_id' => $this->order->id,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    }
}
