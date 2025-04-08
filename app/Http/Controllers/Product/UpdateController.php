<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\Contracts\Products\ProductRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(UpdateProductRequest $request)
    {
        try {
            $id = intval($request->input('id'));
            $name = $request->input('name');
            $price = $request->input('price');

            $this->productRepository->update($id, $name, $price);

            return new JsonResponse(
                [
                    'message' => 'Product updated successfully'
                ]
            );

        } catch(Exception $e) {

            return new JsonResponse(
                [
                    'message' => 'Error: '. $e->getMessage()
                ]
            );
        }
    }
}
