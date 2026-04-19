<?php

namespace App\Http\Controllers\Sync;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sync\SyncOrderStatusRequest;
use App\Models\Order;
use App\Support\States\OrderState\OrderState;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;

class OrderSyncController extends Controller
{
    public function syncOrderStatus(SyncOrderStatusRequest $request, $id)
    {
        // TODO: сделать логирование аналогично оформлению заказа
        $order = Order::findOrFail($id);

        $targetState = OrderState::resolveByName($request->status);

        try {
            $order->status->transitionTo($targetState);
        } catch (CouldNotPerformTransition $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Переход из «{$order->status->label()}» в «{$request->status}» недопустим",
            ], 422);
        }

        return response()->json([
            'message' => 'Статус заказа успешно синхронизирован',
            'order_id' => $order->id,
            'status' => $order->status->label(),
        ]);
    }
}
