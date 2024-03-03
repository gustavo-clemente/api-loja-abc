<?php

declare(strict_types=1);

namespace App\Application\Sales\Input;

use App\Domain\Sales\Entity\OrderItem;
use App\Domain\Sales\Entity\OrderItemsCollection;
use App\Domain\Sales\Entity\Product;
use App\Domain\Sales\ValueObject\OrderId;
use App\Domain\Sales\ValueObject\ProductId;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AddOrderItemsInput
{
    public function __construct(
        private int $id,
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

    public function getOrderId(): OrderId
    {
        return new OrderId($this->id);
    }

    public function getOrderItemsCollection(): OrderItemsCollection
    {
        $orderItems = [];

        foreach ($this->data['items'] as $item) {
            $orderItems[] = new OrderItem(
                orderId: $this->getOrderId(),
                product: new Product(
                    id: new ProductId((string)$item['id']),
                ),
                quantity: (int)$item['quantity'],
            );
        }

        return new OrderItemsCollection($orderItems);
    }
}
