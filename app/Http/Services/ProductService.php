<?php


namespace App\Http\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductService
{
    public function __construct(
        private ProductRepository $repository
    )
    {
    }

    public function show(int $id): Product
    {
        return $this->repository->getProductById($id);
    }

    public function update(int $id, array $data): product
    {
        return $this->repository->updateProduct($id, $data);
    }

}
