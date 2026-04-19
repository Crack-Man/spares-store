<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\Order\OrderListRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(Request $request): Order
    {
        $order = DB::transaction(function () use ($request) {
            $order = $this->storeOrder($request);
            $this->storeOrderItems($order, $request->items);
            $this->updateTotalAmount($order);
            return $order;
        });

        return $order;
    }

    public function getOrders(OrderListRequest $request): \Illuminate\Database\Eloquent\Builder
    {
        return Order::query()
            ->with(['customer', 'items'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('customer_id'), fn ($query) => $query->where('customer_id', $request->customer_id))
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date_to))
            ->orderByDesc('created_at');
    }

    private function storeOrder(Request $request): Order
    {
        return Order::create([
            'customer_id' => $request->customer_id,
            'total_amount' => 0,
        ]);
    }

    private function storeOrderItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            $product = Product::where('id', $item['product_id'])
                ->active()
                ->firstOrFail();

            if ($product->stock_quantity < $item['quantity']) {
                throw new \Exception(
                    "Not enough stock for product {$product->name} (id={$product->id}) " .
                    "in order #{$order->id} by phone {$order->customer->phone}: requested={$item['quantity']} " .
                    "available={$product->stock_quantity}"
                );
            }

            $unitPrice = $product->price;
            $totalPrice = $unitPrice * $item['quantity'];

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ]);

            $product->decrement('stock_quantity', $item['quantity']);
        }
    }

    private function updateTotalAmount(Order $order): void
    {
        $order->update([
            'total_amount' => $order->items()->sum('total_price'),
        ]);
    }
}