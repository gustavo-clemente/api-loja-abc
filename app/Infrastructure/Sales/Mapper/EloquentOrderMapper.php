<?php

declare(strict_types= 1);

namespace App\Infrastructure\Sales\Mapper;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\ValueObject\OrderId;
use App\Infrastructure\Sales\Model\OrderModel;
use Illuminate\Database\Eloquent\Collection;

class EloquentOrderMapper
{
    public function __construct(
        private EloquentOrderItemMapper $orderItemMapper
    ) {

    }
    public function mapToDomain(OrderModel $order): Order
    {
        return new Order(
            id: new OrderId($order->id),
            orderItems: $this->orderItemMapper->mapTomDomainCollection($order->items),
            createdAt: new \Datetime($order->created_at->toDateTimeString()),
            updatedAt: new \Datetime($order->updated_at->toDateTimeString()),
        );
    }

    public function mapToDomainCollection(Collection $collection): OrderCollection
    {
        $orders = [];

        foreach ($collection as $order) {
            $orders[] = $this->mapToDomain($order);
        }

        return new OrderCollection($orders);
    }
}
