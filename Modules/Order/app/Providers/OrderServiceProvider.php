<?php

declare(strict_types=1);

namespace Modules\Order\Providers;

use Modules\Order\Contracts\OrderRepositoryInterface;
use Modules\Order\Repositories\OrderRepository;
use Nwidart\Modules\Support\ModuleServiceProvider;

class OrderServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Order';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'order';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->registerOrders();
    }

    protected function registerOrders(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }
}
