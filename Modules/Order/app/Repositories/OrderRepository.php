<?php

declare(strict_types=1);

namespace Modules\Order\Repositories;

use Illuminate\Support\Collection;
use Modules\Order\Contracts\OrderRepositoryInterface;
use Modules\Order\Dto\Models\OrderData;
use Modules\Order\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function save(Order $order): Order
    {
        $order->save();

        return $order;
    }

    public function findById(int $id): ?OrderData
    {
        $order = Order::with('items')->find($id);

        return $order ? OrderData::from($order) : null;
    }

    public function findAll(): Collection
    {
        return Order::with('items')->get()->map(fn (Order $order) => OrderData::from($order));
    }
}
