<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Model;

use Database\Factories\Sales\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderModel extends Model
{
    use HasFactory;

    protected $table = "orders";

    public $incrementing = true;

    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }

    protected static function newFactory(): Factory
    {
        return OrderFactory::new();
    }
}
