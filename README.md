# E-commerce Order Management System

> A modular Laravel application for managing products and orders, built with clean architecture principles.

## Tech Stack

- **Laravel 13** — PHP framework
- **nwidart/laravel-modules** — modular architecture
- **Filament** — admin interfaces
- **Livewire** — interactive frontend components
- **PostgreSQL** — primary database
- **Pest** — testing framework

## Local Install

1. Clone the repository:
    ```sh
    git clone https://github.com/DeM1-off/ecommerce-order-management.git ecommerce-order-management && cd ecommerce-order-management
    ```

2. Create required files:
    ```sh
    touch .docker/php-fpm/.bash_history && touch .docker/php-fpm/php.ini
    ```

3. Create `.env` file:
    ```sh
    cp .env.example .env
    ```

4. Configure `.env` — set free docker ports if needed.

5. Start docker containers:
    ```sh
    docker compose up -d
    ```

6. Enter the php container:
    ```sh
    docker compose exec php-fpm bash
    ```

7. Install dependencies and set up the application:
    ```sh
    composer install
    php artisan key:generate --ansi
    php artisan migrate
    php artisan db:seed
    npm install && npm run build
    ```

## Accessing the Application

| Service | URL |
|---------|-----|
| Application | http://localhost:8095 |
| Admin Panel | http://localhost:8095/admin |

Default admin credentials (created via seeder):

| Field | Value |
|-------|-------|
| Email | admin@admin.com |
| Password | password |

## Running Tests

1. Create `.env.testing` file (on your host machine):
    ```sh
    cp .env.testing.example .env.testing
    ```

2. Enter the php container and run the following commands:
    ```sh
    docker compose exec php-fpm bash
    ```

    ```sh
    php artisan key:generate --env=testing
    php artisan migrate --env=testing
    ```

    Run all tests:
    ```sh
    php artisan test
    ```

    Run module-specific tests:
    ```sh
    php artisan test --filter=Catalog
    php artisan test --filter=Order
    ```

## Code Quality

```sh
# Code style check
composer lint

# Auto-fix code style
composer fix

# Static analysis (PHPStan level 5)
composer analyse
```

## Architecture Overview

The application is split into two independent modules:

```
Modules/
├── Catalog/                   — product catalog management
│   ├── app/
│   │   ├── Contracts/         — CategoryRepositoryInterface, ProductRepositoryInterface
│   │   ├── Dto/               — CategoryData, ProductData
│   │   ├── Exceptions/        — ProductNotFoundException
│   │   ├── Filament/          — CategoryResource, ProductResource (admin panel)
│   │   ├── Models/            — Category, Product
│   │   └── Repositories/      — CategoryRepository, ProductRepository
│   └── database/
│       ├── migrations/
│       └── seeders/
└── Order/                     — order processing
    ├── app/
    │   ├── Contracts/         — OrderRepositoryInterface
    │   ├── Dto/               — OrderCreateData, OrderItemCreateData
    │   ├── Enums/             — OrderStatus
    │   ├── Filament/          — OrderResource (admin panel)
    │   ├── Livewire/          — CreateOrder (public order form)
    │   ├── Models/            — Order, OrderItem
    │   ├── Repositories/      — OrderRepository, OrderItemRepository
    │   └── Services/
    │       ├── Order/         — OrderCrudService
    │       └── OrderItem/     — OrderItemCrudService
    └── database/
        ├── migrations/
        └── seeders/
```

### Layers

| Layer | Responsibility |
|-------|---------------|
| **Model** | Eloquent ORM, casts, relationships |
| **Repository** | Database queries, returns DTOs |
| **DTO** | Typed data transfer via `spatie/laravel-data` |
| **Service** | Business logic, orchestrates repositories |
| **Filament Resource** | Admin panel CRUD |
| **Livewire Component** | Interactive public UI |

### Cross-module Communication

Modules communicate exclusively through contracts (interfaces), never by direct class coupling:

```
Order module
    ├── CreateOrder (Livewire)
    │       └── CategoryRepositoryInterface  ← contract (defined in Catalog)
    │                   ↓
    │           CategoryRepository  ← implementation (resolved via IoC container)
    └── OrderItemCrudService
            └── ProductRepositoryInterface  ← contract (defined in Catalog)
                        ↓
                ProductRepository  ← implementation (resolved via IoC container)
```

The `Order` module depends on `Catalog` only through contracts. This means the `Catalog` implementation can be swapped without touching `Order`.

### Order Flow

```
POST /orders/create (Livewire)
    └── OrderCrudService::create(OrderCreateData)
            ├── Order::forceFill() + save()         — creates order record
            └── OrderItemCrudService::attachToOrder()
                    ├── ProductRepository::findByIds() — resolves products via contract
                    ├── OrderItem::create() per item   — saves line items
                    └── calculateTotal()               — returns total amount
```