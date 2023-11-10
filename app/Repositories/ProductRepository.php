<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{

    public function getAllProducts()
    {
        return Product::all();
    }

    public function getProductById($productId)
    {
        return Product::findOrFail($productId)->toArray();
    }

    public function deleteProduct($productId)
    {
        Product::destroy($productId);
    }

    public function createProduct(array $productData)
    {
        return Product::create($productData);
    }

    public function updateProduct($productId, array $productData)
    {
        $item = Product::findOrFail($productId);
        $item->update($productData);
        $item->save();
        return $item;
//        return Product::whereId($productId)->update($productData);
    }

}
