<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;

uses(RefreshDatabase::class);

it('creates a category', function () {
    $category = Category::create(['name' => 'Electronics']);

    expect($category->name)->toBe('Electronics');
    $this->assertDatabaseHas('categories', ['name' => 'Electronics']);
});

it('category has many products', function () {
    $category = Category::create(['name' => 'Electronics']);

    Product::create([
        'category_id' => $category->id,
        'name' => 'Laptop',
        'price' => '1500.00',
        'stock' => 5,
    ]);

    Product::create([
        'category_id' => $category->id,
        'name' => 'Phone',
        'price' => '800.00',
        'stock' => 10,
    ]);

    expect($category->products)->toHaveCount(2)
        ->and($category->products->pluck('name')->toArray())->toContain('Laptop', 'Phone');
});

it('deleting a category cascades to products', function () {
    $category = Category::create(['name' => 'Electronics']);

    Product::create([
        'category_id' => $category->id,
        'name' => 'Laptop',
        'price' => '1500.00',
        'stock' => 5,
    ]);

    $category->delete();

    $this->assertDatabaseMissing('categories', ['name' => 'Electronics']);
    $this->assertDatabaseMissing('products', ['name' => 'Laptop']);
});
