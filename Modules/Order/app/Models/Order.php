<?php

declare(strict_types=1);

namespace Modules\Order\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Order\Enums\OrderStatus;

/**
 * @property-read int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $customer_name
 * @property string $customer_email
 * @property string $total_amount
 * @property OrderStatus $status
 */
class Order extends Model
{
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'status' => OrderStatus::class,
        ];
    }
}
