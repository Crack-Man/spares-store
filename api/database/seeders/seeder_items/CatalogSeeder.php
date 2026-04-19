<?php

namespace Database\Seeders\seeder_items;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'categories' => [
                [
                    'name' => 'Детали двигателя',
                    'products' => [
                        [
                            'name' => 'Вентилятор',
                            'sku' => fake()->uuid(),
                            'price' => 15560,
                            'stock_quantity' => 3,
                        ],
                        [
                            'name' => 'Привод вентилятора',
                            'sku' => fake()->uuid(),
                            'price' => 6600,
                            'stock_quantity' => 1,
                        ],
                        [
                            'name' => 'Крыльчатка вентилятора',
                            'sku' => fake()->uuid(),
                            'price' => 8815,
                            'stock_quantity' => 4,
                        ],
                        [
                            'name' => 'Маслоохладитель',
                            'sku' => fake()->uuid(),
                            'price' => 13650,
                            'stock_quantity' => 2,
                        ],
                        [
                            'name' => 'Термостат',
                            'sku' => fake()->uuid(),
                            'price' => 1350,
                            'stock_quantity' => 5,
                        ],
                    ],
                ],
            ]
        ];
        
        foreach ($data['categories'] as $category) {
            $categoryModel = Category::updateOrCreate(
                ['name' => $category['name']],
                collect($category)->except('products')->toArray()
            );

            foreach ($category['products'] as $product) {
                $categoryModel->products()->updateOrCreate(
                    ['name' => $product['name']],
                    $product
                );
            }
        }
    }
}
