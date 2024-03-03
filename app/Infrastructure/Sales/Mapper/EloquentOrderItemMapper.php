<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Mapper;

use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\OrderItemId;
use App\Infrastructure\Sales\Model\OrderItemModel;
use App\Infrastructure\Sales\Model\OrderModel;
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

    public function mapToModelCollection(OrderModel $orderModel, OrderItemsCollection $orderItems): Collection
    {
        $orderItemsModels = [];

        foreach($orderItems->getItems() as $orderItem){
            $orderItemsModels[] = new OrderItemModel([
                "order_id" => $orderModel->id,
                "product_id" => $orderItem->getProduct()->getId()->getIdentifier(),
                "quantity" => $orderItem->getQuantity(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return new Collection($orderItemsModels);
    }
}
