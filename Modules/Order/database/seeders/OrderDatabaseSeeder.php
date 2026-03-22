<?php

declare(strict_types=1);

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\Product;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;

class OrderDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all()->keyBy('id');

        if ($products->isEmpty()) {
            return;
        }

        $productList = $products->values();

        $orders = [
            [
                'customer_name' => 'Alice Johnson',
                'customer_email' => 'alice@example.com',
                'status' => OrderStatus::DELIVERED,
                'items' => [
                    ['product' => $productList->get(0), 'quantity' => 1],
                    ['product' => $productList->get(7), 'quantity' => 2],
                ],
            ],
            [
                'customer_name' => 'Bob Smith',
                'customer_email' => 'bob@example.com',
                'status' => OrderStatus::SHIPPED,
                'items' => [
                    ['product' => $productList->get(2), 'quantity' => 1],
                    ['product' => $productList->get(3), 'quantity' => 1],
                ],
            ],
            [
                'customer_name' => 'Carol White',
                'customer_email' => 'carol@example.com',
                'status' => OrderStatus::CONFIRMED,
                'items' => [
                    ['product' => $productList->get(4), 'quantity' => 3],
                    ['product' => $productList->get(5), 'quantity' => 2],
                    ['product' => $productList->get(6), 'quantity' => 1],
                ],
            ],
            [
                'customer_name' => 'David Brown',
                'customer_email' => 'david@example.com',
                'status' => OrderStatus::PENDING,
                'items' => [
                    ['product' => $productList->get(12), 'quantity' => 1],
                    ['product' => $productList->get(13), 'quantity' => 2],
                ],
            ],
            [
                'customer_name' => 'Eva Martinez',
                'customer_email' => 'eva@example.com',
                'status' => OrderStatus::PENDING,
                'items' => [
                    ['product' => $productList->get(1), 'quantity' => 1],
                    ['product' => $productList->get(9), 'quantity' => 1],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $total = collect($orderData['items'])->sum(
                fn (array $item) => (float) $item['product']->price * $item['quantity'],
            );

            $order = Order::create([
                'customer_name' => $orderData['customer_name'],
                'customer_email' => $orderData['customer_email'],
                'status' => $orderData['status'],
                'total_amount' => number_format($total, 2, '.', ''),
            ]);

            foreach ($orderData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'name' => $item['product']->name,
                    'price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                ]);
            }
        }
    }
}
