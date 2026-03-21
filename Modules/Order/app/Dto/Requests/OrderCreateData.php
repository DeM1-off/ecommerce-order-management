<?php

declare(strict_types=1);

namespace Modules\Order\Dto\Requests;

use App\Data\BaseRequestData;

class OrderCreateData extends BaseRequestData
{
    public string $customerName;
    public string $customerEmail;

    /** @var array<int, array{product_id: int, quantity: int}> */
    public array $items;

    protected array $excludeFromFill = ['items'];
}
