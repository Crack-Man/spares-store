<?php

namespace App\Services\Order;

use App\Jobs\ExportOrderJob;
use App\Models\Order;
use App\Support\States\OrderState\ConfirmedStatus;
use App\Support\States\OrderState\OrderState;
use App\Support\States\OrderState\ShippedStatus;

class OrderStatusSyncService
{
    public function changeStatus(Order $order, string $status): string
    {
        $targetState = OrderState::resolveByName($status);
        $order->status->transitionTo($targetState);

        switch ($order->status::class) {
            case ConfirmedStatus::class:
                ExportOrderJob::dispatch($order->load('items'));
                $order->update(['confirmed_at' => now()]);
                break;

            case ShippedStatus::class:
                $order->update(['shipped_at' => now()]);
                break;
            
            default:
                break;
        }

        return $order->status->label();
    }
}
