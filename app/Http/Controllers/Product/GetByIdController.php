<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Products\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;

class GetByIdController extends Controller
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(int $id)
    {
        try {
            return response()->json($this->productRepository->findById($id));
        } catch(Exception $e) {
            return new JsonResponse(
                [
                    'message' => 'Error: '. $e->getMessage()
                ]
            );
        }
    }

}
