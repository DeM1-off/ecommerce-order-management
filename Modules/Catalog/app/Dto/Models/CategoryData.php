<?php

declare(strict_types=1);

namespace Modules\Catalog\Dto\Models;

use Spatie\LaravelData\Data;

class CategoryData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}
}
