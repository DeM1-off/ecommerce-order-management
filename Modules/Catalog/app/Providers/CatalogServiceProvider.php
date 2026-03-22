<?php

namespace Modules\Catalog\Providers;

use Modules\Catalog\Contracts\CategoryRepositoryInterface;
use Modules\Catalog\Contracts\ProductRepositoryInterface;
use Modules\Catalog\Repositories\CategoryRepository;
use Modules\Catalog\Repositories\ProductRepository;
use Nwidart\Modules\Support\ModuleServiceProvider;

class CatalogServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Catalog';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'catalog';

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

        $this->registerProducts();
        $this->registerCategories();
    }

    protected function registerProducts(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }

    protected function registerCategories(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    /**
     * Define module schedules.
     *
     * @param  $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
