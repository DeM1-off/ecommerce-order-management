<?php

declare(strict_types=1);

namespace Modules\Order\Dto\Requests;

use App\Data\BaseRequestData;

class OrderItemCreateData extends BaseRequestData
{
    public int $orderId;
    public int $productId;
    public string $name;
    public string $price;
    public int $quantity;
}
