<?php

namespace App\Services\Order;

use App\Jobs\ExportOrderJob;
use App\Models\Order;
use App\Support\States\OrderState\ConfirmedStatus;
use App\Support\States\OrderState\OrderState;

class OrderStatusSyncService
{
    public function changeStatus(Order $order, string $status): string
    {
        $targetState = OrderState::resolveByName($status);
        $order->status->transitionTo($targetState);
        
        if ($order->status instanceof ConfirmedStatus) {
            ExportOrderJob::dispatch($order->load('items'));
        }

        return $order->status->label();
    }
}
