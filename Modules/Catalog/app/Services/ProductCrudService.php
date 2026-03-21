<?php

declare(strict_types=1);

namespace Modules\Catalog\Services;

use Modules\Catalog\Dto\Requests\ProductCreateData;
use Modules\Catalog\Dto\Requests\ProductUpdateData;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Repositories\ProductRepository;

readonly class ProductCrudService
{
    public function __construct(
        protected ProductRepository $productRepository,
    ) {}

    public function create(ProductCreateData $data): Product
    {
        $product = new Product;
        $product->forceFill($data->getFilledPropertiesAsArray());

        return $this->productRepository->save($product);
    }

    public function update(Product $product, ProductUpdateData $data): Product
    {
        $product->forceFill($data->getFilledPropertiesAsArray());

        return $this->productRepository->save($product);
    }

    public function setStock(Product $product, int $stock): Product
    {
        $product->stock = $stock;

        return $this->productRepository->save($product);
    }

    public function setPrice(Product $product, string $price): Product
    {
        $product->price = $price;

        return $this->productRepository->save($product);
    }
}
