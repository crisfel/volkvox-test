<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Products\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class DeleteController extends Controller
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function __invoke(int $id)
    {
        try {
            $this->productRepository->delete($id);

            return new JsonResponse(
                [
                    'message' => 'Product deleted successfully'
                ]
            );

        } catch(\Exception $e) {

            return new JsonResponse(
                [
                    'message' => 'Error: '. $e->getMessage()
                ]
            );
        }
    }
}
