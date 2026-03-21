<?php

declare(strict_types=1);

namespace Modules\Order\Contracts;

use Illuminate\Support\Collection;
use Modules\Order\Dto\Models\OrderData;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?OrderData;

    public function findAll(): Collection;
}
