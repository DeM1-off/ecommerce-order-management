<?php

declare(strict_types=1);

namespace Modules\Catalog\Contracts;

use Illuminate\Support\Collection;
use Modules\Catalog\Dto\Models\ProductData;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?ProductData;

    /** @param  int[]  $ids */
    public function findByIds(array $ids): Collection;

    public function findAll(): Collection;
}
