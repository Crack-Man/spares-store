<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Catalog\CategoryController;
use App\Http\Controllers\Catalog\ProductController;
use App\Http\Controllers\Order\OrderController;

Route::prefix('catalog')->group(function () {
    Route::get('/categories', [CategoryController::class, 'getCategories']);
    Route::get('/products', [ProductController::class, 'getProducts']);
});

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'createOrder']);
    Route::get('/', [OrderController::class, 'getOrders']);
    Route::get('/{id}', [OrderController::class, 'showOrder']);
});

// Отдельный эндпоинт вне группы orders, чтобы разделить логику обновления статуса заказа как процесс синхронизации с внешней системой
Route::prefix('sync')->group(function () {
    Route::patch('/orders/{id}/status', [OrderController::class, 'syncOrderStatus']);
});