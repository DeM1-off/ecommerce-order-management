<?php

declare(strict_types=1);

namespace Modules\Order\Dto\Models;

use Spatie\LaravelData\Data;

class OrderItemData extends Data
{
    public int $id;
    public int $orderId;
    public int $productId;
    public string $name;
    public string $price;
    public int $quantity;
}
