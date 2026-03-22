<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;

class CatalogDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Clothing',
            'Books',
            'Home & Garden',
            'Sports',
        ];

        $created = collect($categories)->mapWithKeys(
            fn (string $name) => [$name => Category::create(['name' => $name])],
        );

        $products = [
            [
                'category' => 'Electronics', 'name' => 'iPhone 15 Pro', 'price' => '1199.99', 'stock' => 25,
                'description' => 'Apple iPhone 15 Pro 256GB',
            ],
            [
                'category' => 'Electronics', 'name' => 'Samsung Galaxy S24', 'price' => '999.99', 'stock' => 30,
                'description' => 'Samsung Galaxy S24 128GB',
            ],
            [
                'category' => 'Electronics', 'name' => 'MacBook Air M3', 'price' => '1299.99', 'stock' => 15,
                'description' => 'Apple MacBook Air 13" M3',
            ],
            [
                'category' => 'Electronics', 'name' => 'Sony WH-1000XM5', 'price' => '349.99', 'stock' => 40,
                'description' => 'Wireless noise-cancelling headphones',
            ],
            [
                'category' => 'Clothing', 'name' => 'Classic White T-Shirt', 'price' => '29.99', 'stock' => 100,
                'description' => 'Essential cotton t-shirt',
            ],
            [
                'category' => 'Clothing', 'name' => 'Slim Fit Jeans', 'price' => '79.99', 'stock' => 60,
                'description' => 'Classic blue denim jeans',
            ],
            [
                'category' => 'Clothing', 'name' => 'Winter Jacket', 'price' => '149.99', 'stock' => 35,
                'description' => 'Warm insulated winter jacket',
            ],
            [
                'category' => 'Books', 'name' => 'Clean Code', 'price' => '39.99', 'stock' => 50,
                'description' => 'A Handbook of Agile Software Craftsmanship',
            ],
            [
                'category' => 'Books', 'name' => 'The Pragmatic Programmer', 'price' => '44.99', 'stock' => 45,
                'description' => 'Your journey to mastery',
            ],
            [
                'category' => 'Books', 'name' => 'Design Patterns', 'price' => '49.99', 'stock' => 30,
                'description' => 'Elements of Reusable Object-Oriented Software',
            ],
            [
                'category' => 'Home & Garden', 'name' => 'Coffee Maker', 'price' => '89.99', 'stock' => 20,
                'description' => 'Programmable 12-cup coffee maker',
            ],
            [
                'category' => 'Home & Garden', 'name' => 'Indoor Plant Set', 'price' => '34.99', 'stock' => 55,
                'description' => 'Set of 3 low-maintenance indoor plants',
            ],
            [
                'category' => 'Sports', 'name' => 'Yoga Mat', 'price' => '49.99', 'stock' => 70,
                'description' => 'Non-slip premium yoga mat',
            ],
            [
                'category' => 'Sports', 'name' => 'Resistance Bands Set', 'price' => '24.99', 'stock' => 90,
                'description' => 'Set of 5 resistance bands',
            ],
            [
                'category' => 'Sports', 'name' => 'Running Shoes', 'price' => '119.99', 'stock' => 40,
                'description' => 'Lightweight breathable running shoes',
            ],
        ];

        foreach ($products as $data) {
            Product::create([
                'category_id' => $created[$data['category']]->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'stock' => $data['stock'],
            ]);
        }
    }
}
