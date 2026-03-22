<?php

declare(strict_types=1);

namespace Modules\Catalog\Contracts;

use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    /** @return Collection<int, \Modules\Catalog\Dto\Models\CategoryData> */
    public function findAll(): Collection;
}
