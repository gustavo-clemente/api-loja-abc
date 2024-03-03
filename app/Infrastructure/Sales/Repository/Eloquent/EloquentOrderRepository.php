<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Repository\Eloquent;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Exception\OrderWithDuplicateProductEntyException;
use App\Domain\Sales\Repository\OrderRepository;
use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\OrderItemId;
use App\Infrastructure\Sales\Mapper\EloquentOrderItemMapper;
use App\Infrastructure\Sales\Mapper\EloquentOrderMapper;
use App\Infrastructure\Sales\Mapper\EloquentProductMapper;
use App\Infrastructure\Sales\Model\OrderItemModel;
use App\Infrastructure\Sales\Model\OrderModel;
use App\Infrastructure\Sales\Model\ProductModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EloquentOrderRepository implements OrderRepository
{
    public function __construct(
        private EloquentProductMapper $productMapper,
        private EloquentOrderMapper $orderMapper,
        private EloquentOrderItemMapper $orderItemMapper
    ) {
    }
    public function createOrder(Order $order): OrderId
    {
        DB::beginTransaction();

        $orderModel = OrderModel::create();

        $orderItemsModels = [];

        foreach ($order->getOrderItems()->getItems() as $orderItem) {
            $orderItemsModels[] = [
                "order_id" => $orderModel->id,
                "product_id" => $orderItem->getProduct()->getId()->getIdentifier(),
                "quantity" => $orderItem->getQuantity(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        OrderItemModel::insert($orderItemsModels);

        Db::commit();

        return new OrderId((string)$orderModel->id);
    }


    public function findAll(): OrderCollection
    {
        $ordersModel = OrderModel::with(['items', 'items.product'])->get();

        return $this->orderMapper->mapToDomainCollection($ordersModel);
    }
    public function findById(OrderId $orderId): ?Order
    {
        $orderModel = OrderModel::with(['items', 'items.product'])->find($orderId->getIdentifier());

        if (is_null($orderModel)) {
            return null;
        }

        return $this->orderMapper->mapToDomain($orderModel);
    }

    public function cancelOrder(OrderId $orderId): ?OrderId
    {
        $order = OrderModel::find($orderId->getIdentifier());

        if (!$order) {
            return null;
        }

        $order->delete();

        return $orderId;
    }

    public function addOrderItems(OrderId $orderId, OrderItemsCollection $orderItems): ?Order
    {
        DB::beginTransaction();

        $orderModel = OrderModel::with(['items'])->find($orderId->getIdentifier());

        if (!$orderModel) {
            return null;
        }

        $existingItems = $orderModel->items->keyBy('product_id');

        $orderItemModels = $this->orderItemMapper->mapToModelCollection($orderModel, $orderItems);

        foreach ($orderItemModels as $orderItemModel) {
            $productId = $orderItemModel->product_id;

            if($existingItems->has($productId)){
                throw new OrderWithDuplicateProductEntyException(
                    "A item with product id '{$productId}' already exists on this order",
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $createdItems = $orderModel->items()->save($orderItemModel);
            $orderModel->items->push($createdItems);
        }

        DB::commit();
        return $this->orderMapper->mapToDomain($orderModel);
    }
}
