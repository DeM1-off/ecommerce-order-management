<?php

declare(strict_types=1);

namespace App\Dictionaries;

enum NavigationGroup: string
{
    case CATALOG = 'Catalog';
    case ORDERS = 'Orders';
}
