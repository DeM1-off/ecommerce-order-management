<?php

declare(strict_types=1);

namespace Modules\Catalog\Dto\Requests;

use App\Data\BaseRequestData;
use Spatie\LaravelData\Optional;

class ProductCreateData extends BaseRequestData
{
    public int $categoryId;
    public string $name;
    public string|Optional|null $description;
    public string $price;
    public int $stock;
}
