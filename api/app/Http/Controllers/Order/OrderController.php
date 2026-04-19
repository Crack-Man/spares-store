<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\OrderListRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Order\OrderResource;
use App\Models\Customer;
use App\Models\Order;
use App\Services\Logging\LogService;
use App\Services\Order\OrderService;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderController extends Controller
{
    protected $orderService;
    protected $logService;
    
    public function __construct(OrderService $orderService, LogService $logService)
    {
        $this->orderService = $orderService;
        $this->logService = $logService;
    }
    
    public function createOrder(CreateOrderRequest $request): JsonResource
    {
        // TODO: можно сделать поиск Customer по авторизации без ожидания от запроса client_id
        $customer = Customer::findOrFail($request['customer_id']);
        $this->logService->save('orders', $customer->phone, $request->all());
        $order = $this->orderService->createOrder($request);
        $order->load('customer');

        return (new OrderResource($order))->additional([
            'customer' => new CustomerResource($order->customer),
        ]);
    }
    
    public function getOrders(OrderListRequest $request)
    {
        $orders = $this->orderService->getOrders($request)
            ->paginate(10);

        // TODO: можно сделать поиск Customer по авторизации без ожидания от запроса client_id
        $customer = Customer::findOrFail($request->customer_id);

        return OrderResource::collection($orders)->additional([
            'customer' => new CustomerResource($customer),
        ]);
    }
    
    public function showOrder($id)
    {
        // TODO: можно сделать поиск Customer по авторизации без ожидания от запроса client_id
        $order = Order::with(['customer', 'items'])->findOrFail($id);

        return (new OrderResource($order))->additional([
            'customer' => new CustomerResource($order->customer),
        ]);
    }
}
