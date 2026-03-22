<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Order\Livewire\CreateOrder;
use Modules\Order\Models\Order;

uses(RefreshDatabase::class);

beforeEach(function () {
    $category = Category::create(['name' => 'Electronics']);

    $this->product = Product::create([
        'category_id' => $category->id,
        'name' => 'Laptop',
        'price' => '1000.00',
        'stock' => 5,
    ]);
});

it('renders the create order page', function () {
    $this->get(route('orders.create'))->assertOk();
});

it('can add a product to cart', function () {
    Livewire::test(CreateOrder::class)
        ->call('addToCart', $this->product->id)
        ->assertSet('cart', [(string) $this->product->id => 1]);
});

it('increments quantity when product added twice', function () {
    Livewire::test(CreateOrder::class)
        ->call('addToCart', $this->product->id)
        ->call('addToCart', $this->product->id)
        ->assertSet('cart', [(string) $this->product->id => 2]);
});

it('can remove a product from cart', function () {
    Livewire::test(CreateOrder::class)
        ->call('addToCart', $this->product->id)
        ->call('removeFromCart', $this->product->id)
        ->assertSet('cart', []);
});

it('validates required fields on submit', function () {
    Livewire::test(CreateOrder::class)
        ->call('submit')
        ->assertHasErrors(['customerName', 'customerEmail']);
});

it('creates order on valid submit', function () {
    Livewire::test(CreateOrder::class)
        ->set('customerName', 'John Doe')
        ->set('customerEmail', 'john@example.com')
        ->call('addToCart', $this->product->id)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('orders.create'));

    expect(Order::count())->toBe(1)
        ->and(Order::first()->customer_name)->toBe('John Doe');
});
