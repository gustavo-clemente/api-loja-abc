<?php

declare(strict_types= 1);

namespace App\UserInterface\Sales\Controllers;

use App\Application\Sales\Input\CancelOrderInput;
use App\Application\Sales\Input\CreateOrderInput;
use App\Application\Sales\Input\GetOrderByIdInput;
use App\Application\Sales\OrderApplication;
use App\Infrastructure\Laravel\Controller;
use App\UserInterface\Sales\Request\CreateOrderRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function __construct(
        private OrderApplication $orderApplication
    ) {

    }

    public function index(): JsonResponse
    {
        $output = $this->orderApplication->getAll();
        return new JsonResponse($output->jsonSerialize());
    }
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $inputCreateOrder = new CreateOrderInput($request->toArray());

        $output = $this->orderApplication->createOrder($inputCreateOrder);

        return new JsonResponse($output->jsonSerialize(), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $inputGetById = new GetOrderByIdInput($id);

        $output = $this->orderApplication->getById($inputGetById);

        return new JsonResponse($output->jsonSerialize(), Response::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        $inputCancelOrder = new CancelOrderInput($id);

        $output = $this->orderApplication->cancelOrder($inputCancelOrder);

        return new JsonResponse($output->jsonSerialize(), Response::HTTP_OK);
    }
}
