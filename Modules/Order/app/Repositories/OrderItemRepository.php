<?php

declare(strict_types=1);

namespace Modules\Order\Repositories;

use Modules\Order\Models\OrderItem;

class OrderItemRepository
{
    public function save(OrderItem $orderItem): OrderItem
    {
        $orderItem->save();

        return $orderItem;
    }
}
