<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemModel extends Model
{
    protected $table = "order_items";

    protected $fillable = [
        'product_id',
        'quantity',
        'price'
    ];

    public $incrementing = true;

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class);
    }
    
}
