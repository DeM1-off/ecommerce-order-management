<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Catalog\Exceptions\ProductNotFoundException;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Order\Dto\Requests\OrderCreateData;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Services\Order\OrderCrudService;

uses(RefreshDatabase::class);

beforeEach(function () {
    $category = Category::create(['name' => 'Electronics']);

    $this->productA = Product::create([
        'category_id' => $category->id,
        'name' => 'Laptop',
        'price' => '1000.00',
        'stock' => 10,
    ]);

    $this->productB = Product::create([
        'category_id' => $category->id,
        'name' => 'Mouse',
        'price' => '25.00',
        'stock' => 50,
    ]);

    $this->service = app(OrderCrudService::class);
});

it('creates an order with items and calculates total', function () {
    $order = $this->service->create(OrderCreateData::from([
        'customerName' => 'John Doe',
        'customerEmail' => 'john@example.com',
        'items' => [
            ['product_id' => $this->productA->id, 'quantity' => 2],
            ['product_id' => $this->productB->id, 'quantity' => 3],
        ],
    ]));

    expect($order)->toBeInstanceOf(Order::class)
        ->and($order->customer_name)->toBe('John Doe')
        ->and($order->customer_email)->toBe('john@example.com')
        ->and($order->status)->toBe(OrderStatus::PENDING)
        ->and((float) $order->total_amount)->toBe(2075.00) // 2*1000 + 3*25
        ->and($order->items)->toHaveCount(2);

    $this->assertDatabaseHas('orders', ['customer_email' => 'john@example.com']);
    $this->assertDatabaseHas('order_items', ['name' => 'Laptop', 'quantity' => 2]);
});

it('updates order status', function () {
    $order = $this->service->create(OrderCreateData::from([
        'customerName' => 'Jane Doe',
        'customerEmail' => 'jane@example.com',
        'items' => [
            ['product_id' => $this->productA->id, 'quantity' => 1],
        ],
    ]));

    expect($order->status)->toBe(OrderStatus::PENDING);

    $updated = $this->service->setStatus($order, OrderStatus::CONFIRMED);

    expect($updated->status)->toBe(OrderStatus::CONFIRMED);
    $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'confirmed']);
});

it('throws exception when product does not exist', function () {
    $this->service->create(OrderCreateData::from([
        'customerName' => 'John Doe',
        'customerEmail' => 'john@example.com',
        'items' => [
            ['product_id' => 99999, 'quantity' => 1],
        ],
    ]));
})->throws(ProductNotFoundException::class);
