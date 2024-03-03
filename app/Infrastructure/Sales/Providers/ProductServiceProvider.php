<?php

namespace App\Infrastructure\Sales\Providers;

use App\Domain\Sales\Repository\ProductRepository;
use App\Infrastructure\Sales\Repository\Eloquent\EloquentProductRepository;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public $bindings = [
        ProductRepository::class => EloquentProductRepository::class,
    ];
}
