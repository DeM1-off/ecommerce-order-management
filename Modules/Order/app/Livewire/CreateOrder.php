<?php

declare(strict_types=1);

namespace Modules\Order\Livewire;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Modules\Catalog\Contracts\ProductRepositoryInterface;
use Modules\Catalog\Dto\Models\ProductData;
use Modules\Catalog\Models\Category;
use Modules\Order\Dto\Requests\OrderCreateData;
use Modules\Order\Services\OrderCrudService;

#[Layout('order::components.layouts.master')]
class CreateOrder extends Component
{
    #[Validate('required|string|max:255')]
    public string $customerName = '';
    #[Validate('required|email|max:255')]
    public string $customerEmail = '';
    public ?int $selectedCategory = null;
    /** @var array<string, int> product_id => quantity */
    public array $cart = [];

    public function addToCart(int $productId): void
    {
        $key = (string) $productId;
        $this->cart[$key] = ($this->cart[$key] ?? 0) + 1;
    }

    public function removeFromCart(int $productId): void
    {
        unset($this->cart[(string) $productId]);
    }

    public function decrementCart(int $productId): void
    {
        $key = (string) $productId;

        if (($this->cart[$key] ?? 0) <= 1) {
            $this->removeFromCart($productId);

            return;
        }

        $this->cart[$key]--;
    }

    #[Computed]
    public function categories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    #[Computed]
    public function products(): Collection
    {
        return app(ProductRepositoryInterface::class)->findAll()
            ->when(
                $this->selectedCategory,
                fn (Collection $products) => $products->where('categoryId', $this->selectedCategory),
            );
    }

    #[Computed]
    public function cartItems(): Collection
    {
        if (empty($this->cart)) {
            return collect();
        }

        $ids = array_map('intval', array_keys($this->cart));

        return app(ProductRepositoryInterface::class)->findByIds($ids)
            ->map(fn (ProductData $product) => [
                'product' => $product,
                'quantity' => $this->cart[(string) $product->id],
                'subtotal' => (float) $product->price * $this->cart[(string) $product->id],
            ]);
    }

    public function submit(OrderCrudService $orderCrudService): void
    {
        $this->validate();

        if (empty($this->cart)) {
            $this->addError('cart', 'Please add at least one product to your order.');

            return;
        }

        $items = collect($this->cart)
            ->map(fn (int $quantity, string $productId) => [
                'product_id' => (int) $productId,
                'quantity' => $quantity,
            ])
            ->values()
            ->toArray();

        $orderCrudService->create(OrderCreateData::from([
            'customerName' => $this->customerName,
            'customerEmail' => $this->customerEmail,
            'items' => $items,
        ]));

        session()->flash('success', 'Your order has been placed successfully!');

        $this->redirect(route('orders.create'), navigate: true);
    }

    public function render(): View
    {
        return view('order::livewire.create-order');
    }
}
