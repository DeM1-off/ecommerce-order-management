<?php

declare(strict_types=1);

namespace Modules\Order\Services\Order;

use Illuminate\Support\Facades\DB;
use Modules\Order\Contracts\OrderRepositoryInterface;
use Modules\Order\Dto\Requests\OrderCreateData;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderItem\OrderItemCrudService;

readonly class OrderCrudService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderItemCrudService $orderItemCrudService,
    ) {}

    public function create(OrderCreateData $data): Order
    {
        return DB::transaction(function () use ($data): Order {
            $order = new Order;
            $order->forceFill($data->getFilledPropertiesAsArray());

            $this->orderRepository->save($order);

            $order->total_amount = $this->orderItemCrudService->attachToOrder($order, $data->items);

            $this->orderRepository->save($order);

            return $order->refresh()->load('items');
        });
    }

    public function setStatus(Order $order, OrderStatus $status): Order
    {
        $order->status = $status;

        return $this->orderRepository->save($order);
    }
}
