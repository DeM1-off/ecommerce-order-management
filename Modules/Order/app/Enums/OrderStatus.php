<?php

declare(strict_types=1);

namespace Modules\Order\Enums;

use App\Dictionaries\EnumMethodHelper;

/**
 * @method bool isPending()
 * @method bool isConfirmed()
 * @method bool isShipped()
 * @method bool isDelivered()
 * @method bool hasStatus(OrderStatus[] $statuses)
 */
enum OrderStatus: string
{
    use EnumMethodHelper;

    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
}
