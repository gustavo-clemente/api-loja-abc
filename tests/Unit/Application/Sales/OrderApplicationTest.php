<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Sales;

use App\Application\Sales\Input\CreateOrderInput;
use App\Application\Sales\OrderApplication;
use App\Application\Sales\Output\CreateOrderOutput;
use App\Domain\Sales\Service\OrderService;
use App\Domain\Sales\ValueObject\OrderId;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrderApplicationTest extends TestCase
{
    public function testCreateOrderReturnsCorrectOutput(): void
    {
        $inputCreateOrder = new CreateOrderInput([
            "items" => [
                [
                    "id" => 1,
                    "quantity" => 1
                ]
            ]
        ]);

        $orderId = new OrderId('1');

        $this->mock(OrderService::class, function (MockInterface $mock) use($orderId){
            $mock
              ->shouldReceive("createOrder")
              ->once()
              ->andReturn($orderId);
        });

        $output = app(OrderApplication::class)->createOrder($inputCreateOrder);

        $this->assertInstanceOf(CreateOrderOutput::class, $output);
        $this->assertIsArray($output->jsonSerialize());

        $outputSerialized = $output->jsonSerialize();

        $this->assertArrayHasKey('status', $outputSerialized);
        $this->assertArrayHasKey('id', $outputSerialized);
        $this->assertEquals(Response::HTTP_CREATED, $outputSerialized['status']);
        $this->assertEquals($orderId->getIdentifier(), $outputSerialized['id']);
    }
}
