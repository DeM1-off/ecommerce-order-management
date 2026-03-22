<?php

declare(strict_types=1);

namespace Modules\Order\Repositories;

use Modules\Order\Contracts\OrderRepositoryInterface;
use Modules\Order\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function save(Order $order): Order
    {
        $order->save();

        return $order;
    }
}
