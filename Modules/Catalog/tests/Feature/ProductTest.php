<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Catalog\Dto\Requests\ProductCreateData;
use Modules\Catalog\Dto\Requests\ProductUpdateData;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Services\ProductCrudService;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->category = Category::create(['name' => 'Electronics']);
    $this->service = app(ProductCrudService::class);
});

it('creates a product via service', function () {
    $product = $this->service->create(ProductCreateData::from([
        'categoryId' => $this->category->id,
        'name' => 'iPhone 15',
        'price' => '999.99',
        'stock' => 10,
    ]));

    expect($product)->toBeInstanceOf(Product::class)
        ->and($product->name)->toBe('iPhone 15')
        ->and($product->stock)->toBe(10)
        ->and($product->category_id)->toBe($this->category->id);

    $this->assertDatabaseHas('products', ['name' => 'iPhone 15', 'stock' => 10]);
});

it('updates a product name via service', function () {
    $product = $this->service->create(ProductCreateData::from([
        'categoryId' => $this->category->id,
        'name' => 'Old Name',
        'price' => '100.00',
        'stock' => 5,
    ]));

    $updated = $this->service->update($product, ProductUpdateData::from(['name' => 'New Name']));

    expect($updated->name)->toBe('New Name')
        ->and($updated->price)->toBe('100.00');
});

it('sets product stock', function () {
    $product = $this->service->create(ProductCreateData::from([
        'categoryId' => $this->category->id,
        'name' => 'Test Product',
        'price' => '50.00',
        'stock' => 10,
    ]));

    $updated = $this->service->setStock($product, 25);

    expect($updated->stock)->toBe(25);
    $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 25]);
});

it('sets product price', function () {
    $product = $this->service->create(ProductCreateData::from([
        'categoryId' => $this->category->id,
        'name' => 'Test Product',
        'price' => '50.00',
        'stock' => 10,
    ]));

    $updated = $this->service->setPrice($product, '75.99');

    expect($updated->price)->toBe('75.99');
    $this->assertDatabaseHas('products', ['id' => $product->id, 'price' => '75.99']);
});
