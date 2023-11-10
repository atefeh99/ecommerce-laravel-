<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class OrderController extends Controller
{
    public $user_id;

    public function __construct(
        private OrderService $orderService
    )
    {
        $this->middleware('auth:api');
        $this->user_id = auth()->user()->getAuthIdentifier();


    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->orderService->index($this->user_id)
        ]);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $data = $this->orderService->store($this->user_id, $request->validated());
        return response()->json(
            [
                'data' => $data
            ],
            Response::HTTP_CREATED
        );
    }

    public function show(Order $order)
    {
        return response()->json([
            'data' => $this->orderService->show($this->user_id,$order->id)
        ]);
    }

    public function updateItem(UpdateOrderRequest $request, $id)
    {
        $data = $this->orderService->updateItem($this->user_id,$id, $request->validated());


        return response()->json([
            'data' => $data
        ]);
    }

    public function destroy(Order $order)
    {
        $this->orderService->deleteItem($this->user_id, $order->id);

        return response()->json($order->id, Response::HTTP_NO_CONTENT);
    }
}
