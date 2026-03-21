<?php

declare(strict_types=1);

namespace Modules\Order\Services;

use Illuminate\Support\Collection;
use Modules\Catalog\Contracts\ProductRepositoryInterface;
use Modules\Catalog\Exceptions\ProductNotFoundException;
use Modules\Order\Dto\Requests\OrderItemCreateData;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Order\Repositories\OrderItemRepository;

readonly class OrderItemCrudService
{
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
        protected ProductRepositoryInterface $productRepository,
    ) {}

    public function create(OrderItemCreateData $data): OrderItem
    {
        $orderItem = new OrderItem;
        $orderItem->forceFill($data->getFilledPropertiesAsArray());

        return $this->orderItemRepository->save($orderItem);
    }

    public function attachToOrder(Order $order, array $items): string
    {
        $resolved = $this->resolveItems($items);

        $this->createOrderItems($order, $resolved);

        return $this->calculateTotal($resolved);
    }

    private function resolveItems(array $items): Collection
    {
        $products = $this->productRepository
            ->findByIds(array_column($items, 'product_id'))
            ->keyBy('id');

        return collect($items)->map(fn (array $item) => [
            'product' => $products->get($item['product_id'])
                ?? throw ProductNotFoundException::forId($item['product_id']),
            'quantity' => $item['quantity'],
        ]);
    }

    private function createOrderItems(Order $order, Collection $resolved): void
    {
        $resolved->each(fn (array $item) => $this->create(
            OrderItemCreateData::from([
                'orderId' => $order->id,
                'productId' => $item['product']->id,
                'name' => $item['product']->name,
                'price' => $item['product']->price,
                'quantity' => $item['quantity'],
            ]),
        ));
    }

    private function calculateTotal(Collection $resolved): string
    {
        return number_format(
            $resolved->sum(fn (array $item) => (float) $item['product']->price * $item['quantity']),
            2, '.', '',
        );
    }
}
