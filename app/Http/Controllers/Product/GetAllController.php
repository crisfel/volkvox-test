<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Products\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;

class GetAllController extends Controller
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke()
    {
        try {
            return response()->json($this->productRepository->getAll());
        } catch(Exception $e) {
            return new JsonResponse(
                [
                    'message' => 'Error: '. $e->getMessage()
                ]
            );
        }
    }
}
