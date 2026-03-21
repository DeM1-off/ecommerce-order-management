<?php

declare(strict_types=1);

namespace Modules\Catalog\Dto\Models;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ProductData extends Data
{
    public int $id;
    public int $categoryId;
    public string $name;
    public string|Optional|null $description;
    public string $price;
    public int $stock;
}
