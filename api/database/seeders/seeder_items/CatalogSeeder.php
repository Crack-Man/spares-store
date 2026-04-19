<?php

namespace Database\Seeders\seeder_items;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Детали двигателя',
                'products' => [
                    ['name' => 'Вентилятор', 'price' => 15560, 'stock_quantity' => 3],
                    ['name' => 'Привод вентилятора', 'price' => 6600, 'stock_quantity' => 1],
                    ['name' => 'Крыльчатка вентилятора', 'price' => 8815, 'stock_quantity' => 4],
                    ['name' => 'Маслоохладитель', 'price' => 13650, 'stock_quantity' => 2],
                    ['name' => 'Термостат', 'price' => 1350, 'stock_quantity' => 5],
                ],
            ],
            [
                'name' => 'Ремни',
                'products' => [
                    ['name' => 'Ремень вентилятора/генератора (20x2155)', 'price' => 15560, 'stock_quantity' => 2],
                    ['name' => 'Ремень привода двигателя', 'price' => 6600, 'stock_quantity' => 1],
                ],
            ],
        ];

        foreach ($data as $categoryData) {
            $category = Category::factory()->create([
                'name' => $categoryData['name'],
            ]);

            foreach ($categoryData['products'] as $productData) {
                Product::factory()->create([
                    'category_id' => $category->id,
                    ...$productData,
                ]);
            }
        }
    }
}
