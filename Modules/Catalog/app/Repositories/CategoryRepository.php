<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Illuminate\Support\Collection;
use Modules\Catalog\Contracts\CategoryRepositoryInterface;
use Modules\Catalog\Dto\Models\CategoryData;
use Modules\Catalog\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function findAll(): Collection
    {
        return Category::orderBy('name')->get()->map(fn (Category $category) => new CategoryData(
            id: $category->id,
            name: $category->name,
        ));
    }
}
