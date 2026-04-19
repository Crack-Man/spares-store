<?php

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('logging');

    $this->category = Category::factory()->create();
    $this->customer = Customer::factory()->create();
});

it('создаёт заказ, уменьшает остатки, считает сумму и возвращает корректный ответ', function () {
    $product1 = Product::factory()->create([
        'category_id' => $this->category->id,
        'price' => 1500,
        'stock_quantity' => 10,
    ]);

    $product2 = Product::factory()->create([
        'category_id' => $this->category->id,
        'price' => 3000,
        'stock_quantity' => 5,
    ]);

    $response = $this->postJson('/api/v1/orders', [
        'customer_id' => $this->customer->id,
        'items' => [
            ['product_id' => $product1->id, 'quantity' => 2],
            ['product_id' => $product2->id, 'quantity' => 3],
        ],
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.status', 'Новый')
        ->assertJsonPath('data.total_amount', '12000.00');

    expect($product1->fresh()->stock_quantity)->toBe(8);
    expect($product2->fresh()->stock_quantity)->toBe(2);

    $this->assertDatabaseHas('orders', [
        'customer_id' => $this->customer->id,
        'total_amount' => 12000,
    ]);

    $this->assertDatabaseHas('order_items', [
        'product_id' => $product1->id,
        'quantity' => 2,
        'unit_price' => 1500,
        'total_price' => 3000,
    ]);

    $this->assertDatabaseHas('order_items', [
        'product_id' => $product2->id,
        'quantity' => 3,
        'unit_price' => 3000,
        'total_price' => 9000,
    ]);
});

it('выдаёт ошибку при недостаточном остатке', function () {
    $product = Product::factory()->create([
        'category_id' => $this->category->id,
        'price' => 500,
        'stock_quantity' => 2,
    ]);

    $response = $this->postJson('/api/v1/orders', [
        'customer_id' => $this->customer->id,
        'items' => [
            ['product_id' => $product->id, 'quantity' => 5],
        ],
    ]);

    $response->assertStatus(500);

    expect($product->fresh()->stock_quantity)->toBe(2);
    $this->assertDatabaseCount('orders', 0);
    $this->assertDatabaseCount('order_items', 0);
});

it('падает валидация при отсутствии обязательных полей', function () {
    $response = $this->postJson('/api/v1/orders', []);

    $response->assertStatus(422)
        ->assertJsonPath('status', 'error')
        ->assertJsonStructure(['errors' => ['customer_id', 'items']]);
});
