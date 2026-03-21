<?php

declare(strict_types=1);

namespace Modules\Catalog\Dto\Requests;

use App\Data\BaseRequestData;
use Spatie\LaravelData\Optional;

class ProductUpdateData extends BaseRequestData
{
    public int|Optional $categoryId;
    public string|Optional $name;
    public string|Optional|null $description;
    public string|Optional $price;
    public int|Optional $stock;
}
