<?php

declare(strict_types=1);

namespace App\Infrastructure\Sales\Repository\Eloquent;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderCollection;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Repository\OrderRepository;
use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\OrderItemId;
use App\Infrastructure\Sales\Mapper\EloquentOrderMapper;
use App\Infrastructure\Sales\Mapper\EloquentProductMapper;
use App\Infrastructure\Sales\Model\OrderItemModel;
use App\Infrastructure\Sales\Model\OrderModel;
use App\Infrastructure\Sales\Model\ProductModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EloquentOrderRepository implements OrderRepository
{
    public function __construct(
        private EloquentProductMapper $productMapper,
        private EloquentOrderMapper $orderMapper
    ) {

    }
    public function createOrder(Order $order): OrderId
    {
        DB::beginTransaction();

        $orderModel = OrderModel::create();

        $orderItemsModels = [];

        foreach($order->getOrderItems()->getItems() as $orderItem){
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

        if(is_null($orderModel)){
            return null;
        }

        return $this->orderMapper->mapToDomain($orderModel);
    }
    
    public function cancelOrder(OrderId $orderId): ?OrderId
    {
        $order = OrderModel::find($orderId->getIdentifier());

        if(!$order){
            return null;
        }

        $order->delete();

        return $orderId;
    }

    public function addOrderItems(OrderItemsCollection $orderItems): Order
    {
        throw new \Exception("Not Implemented");
    }

  

}
