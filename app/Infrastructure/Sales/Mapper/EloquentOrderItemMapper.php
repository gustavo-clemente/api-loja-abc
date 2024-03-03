<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Mapper;

use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\OrderItemId;
use App\Infrastructure\Sales\Model\OrderItemModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class EloquentOrderItemMapper
{
    public function __construct(
        private EloquentProductMapper $productMapper
    ) {
    }
    public function mapToDomain(OrderItemModel $orderItem): OrderItem
    {
        return new OrderItem(
            id: new OrderItemId($orderItem->id),
            orderId: new OrderId($orderItem->order_id),
            product: $this->productMapper->mapToDomain($orderItem->product),
            quantity: $orderItem->quantity,
            createdAt: new \Datetime($orderItem->created_at->toDateTimeString()),
            updatedAt: new \Datetime($orderItem->updated_at->toDateTimeString())
        );
    }

    public function mapTomDomainCollection(Collection $collection): OrderItemsCollection
    {
        $orderItems = [];

        foreach ($collection as $orderItem) {
            $orderItems[] = $this->mapToDomain($orderItem);
        }

        return new OrderItemsCollection($orderItems);
    }
}
