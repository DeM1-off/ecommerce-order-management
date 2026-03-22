<?php

declare(strict_types=1);

namespace Modules\Order\Contracts;

use Modules\Order\Models\Order;

interface OrderRepositoryInterface
{
    public function save(Order $order): Order;
}
