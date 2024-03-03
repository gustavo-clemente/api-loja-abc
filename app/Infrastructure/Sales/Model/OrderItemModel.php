<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Model;

use Database\Factories\Sales\OrderItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemModel extends Model
{
    use HasFactory;

    protected $table = "order_items";

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'created_at',
        'updated_at'
    ];

    public $incrementing = true;

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class);
    }

    protected static function newFactory(): Factory
    {
        return OrderItemFactory::new();
    }
    
}
