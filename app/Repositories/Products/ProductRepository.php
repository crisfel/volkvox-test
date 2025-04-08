<?php

namespace App\Repositories\Products;

use App\Models\Product;
use App\Repositories\Contracts\Products\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id)
    {
        return Product::find($id);
    }

    public function store(string $name, float $price)
    {
        $product = new Product();
        $product->name = $name;
        $product->price = $price;
        $product->save();
    }

    public function update(int $id, ?string $name, ?float $price)
    {
        $product = $this->findById($id);
        $product->name = ! is_null($name) ? $name : $product->name;
        $product->price = ! is_null($price) ? $price : $product->price;
        $product->save();
    }

    public function delete(int $id)
    {
        $product = $this->findById($id);
        $product->delete();
    }

    public function getAll()
    {
        return Product::get();
    }
}
