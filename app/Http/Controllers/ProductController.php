<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class ProductController extends Controller
{
    public function __construct(private ProductRepositoryInterface $productRepository) {
        $this->middleware('auth:api');

    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->productRepository->getAllProducts()
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        return response()->json(
            [
                'data' => $this->productRepository->createProduct($request->validated())
            ],
            Response::HTTP_CREATED
        );
    }

    public function show(Product $product) : JsonResponse
    {
        return response()->json([
            'data' => $this->productRepository->getProductById($product->id)
        ]);
    }

    public function updateItem(UpdateProductRequest $request, int $id)
    {
        return response()->json([
            'data' => $this->productRepository->updateProduct($id, $request->validated())
        ]);
    }

    public function destroy(Product $product) : JsonResponse
    {
        $this->productRepository->deleteProduct($product->id);

        return response()->json($product->id, Response::HTTP_NO_CONTENT);
    }
}
