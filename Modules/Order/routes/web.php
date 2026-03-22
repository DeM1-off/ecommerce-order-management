<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Order\Livewire\CreateOrder;

Route::get('orders/create', CreateOrder::class)->name('orders.create');
