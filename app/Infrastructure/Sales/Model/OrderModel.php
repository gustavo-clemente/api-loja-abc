<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderModel extends Model
{
    protected $table = "orders";

    public $incrementing = true;

    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }
}
