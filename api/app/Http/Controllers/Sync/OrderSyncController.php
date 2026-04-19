<?php

namespace App\Http\Controllers\Sync;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sync\SyncOrderStatusRequest;
use App\Models\Order;
use App\Support\States\OrderState\OrderState;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;
use App\Services\Order\OrderStatusSyncService;

class OrderSyncController extends Controller
{
    public function __construct(protected OrderStatusSyncService $statusSyncService)
    {
    }

    public function syncOrderStatus(SyncOrderStatusRequest $request, $id)
    {
        $order = Order::findOrFail($id);

        try {
            $label = $this->statusSyncService->changeStatus($order, $request->status);
        } catch (CouldNotPerformTransition $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Переход из «{$order->status->label()}» в «{$request->status}» недопустим",
            ], 422);
        }

        return response()->json([
            'message' => 'Статус заказа успешно синхронизирован',
            'order_id' => $order->id,
            'status' => $label,
        ]);
    }
}
