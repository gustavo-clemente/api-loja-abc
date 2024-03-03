<?php

namespace App\Infrastructure\Sales\Providers;

use App\Domain\Sales\Repository\OrderRepository;
use App\Infrastructure\Sales\Repository\Eloquent\EloquentOrderRepository;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public $bindings = [
        OrderRepository::class => EloquentOrderRepository::class,
    ];
}
