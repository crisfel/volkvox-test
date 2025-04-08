<?php

namespace App\Repositories\Contracts\Products;

interface ProductRepositoryInterface
{
    public function findById(int $id);
    public function store(string $name, float $price);
    public function update(int $id, string $name, float $price);
    public function delete(int $id);
    public function getAll();
}
