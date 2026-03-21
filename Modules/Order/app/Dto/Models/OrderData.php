<?php

declare(strict_types=1);

namespace Modules\Order\Dto\Models;

use Illuminate\Support\Collection;
use Modules\Order\Enums\OrderStatus;
use Spatie\LaravelData\Data;

class OrderData extends Data
{
    public int $id;
    public string $customerName;
    public string $customerEmail;
    public string $totalAmount;
    public OrderStatus $status;

    /** @var Collection<int, OrderItemData> */
    public Collection $items;
}
