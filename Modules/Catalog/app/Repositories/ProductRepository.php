<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Illuminate\Support\Collection;
use Modules\Catalog\Contracts\ProductRepositoryInterface;
use Modules\Catalog\Dto\Models\ProductData;
use Modules\Catalog\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function save(Product $product): Product
    {
        $product->save();

        return $product;
    }

    /** @param  int[]  $ids */
    public function findByIds(array $ids): Collection
    {
        return Product::whereIn('id', $ids)->get()->map(fn (Product $product) => ProductData::from($product));
    }

    public function findAll(): Collection
    {
        return Product::all()->map(fn (Product $product) => ProductData::from($product));
    }
}
