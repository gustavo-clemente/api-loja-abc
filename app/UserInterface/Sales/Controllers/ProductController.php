<?php

declare(strict_types= 1);

namespace App\UserInterface\Sales\Controllers;

use App\Application\Sales\ProductApplication;
use App\Infrastructure\Laravel\Controller;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private ProductApplication $productApplication
    ) {

    }

    public function index(): JsonResponse
    {
        $output = $this->productApplication->getAll();

        return new JsonResponse($output);
    }
}
