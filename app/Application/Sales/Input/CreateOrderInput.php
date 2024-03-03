<?php

declare(strict_types=1);

namespace App\Application\Sales\Input;

use App\Domain\Sales\Entity\Order;
use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\ValueObject\ProductId;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateOrderInput
{
    public function __construct(
        private array $data
    ) {
        $this->validateInput();
    }

    private function validateInput(): void
    {
        $validator = Validator::make($this->data, [
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function getOrder(): Order
    {
        $orderItemCollection = $this->getOrderItemsCollection();

        return new Order(
            orderItems: $orderItemCollection
        );
    }

    private function getOrderItemsCollection(): OrderItemsCollection
    {
        $orderItems = [];

        foreach ($this->data['items'] as $item) {
            $orderItems[] = new OrderItem(
                product: new Product(
                    id: new ProductId((string)$item['id']),
                ),
                quantity: (int)$item['quantity'],
            );
        }

        return new OrderItemsCollection($orderItems);
    }
}
