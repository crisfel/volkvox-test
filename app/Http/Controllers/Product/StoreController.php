<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Repositories\Contracts\Products\ProductRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(StoreProductRequest $request)
    {
        try {
            $name = strval($request->input('name'));
            $price = floatval($request->input('price'));

            $this->productRepository->store($name, $price);

            return new JsonResponse(
                [
                    'message' => 'Product stored successfully'
                ]
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'message' => 'Error: '. $e->getMessage()
                ]
            );
        }
    }
}
