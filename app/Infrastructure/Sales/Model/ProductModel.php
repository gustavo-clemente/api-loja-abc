<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Model;

use Database\Factories\Sales\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductModel extends Model
{
    use HasFactory;
    
    protected $table = "products";

    public $incrementing = true;

    protected static function newFactory(): Factory
{
    return ProductFactory::new();
}
}
