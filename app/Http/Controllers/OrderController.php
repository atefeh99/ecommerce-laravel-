<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Services\OrderService;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Tymon\JWTAuth\JWTAuth;


class OrderController extends Controller
{
    public $user_id;

    public function __construct(
        private OrderRepositoryInterface $OrderRepository
    )
    {
        $this->middleware('auth:api');
        $this->user_id = auth()->user()->getAuthIdentifier();


    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->OrderRepository->getAllOrders($this->user_id)
        ]);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $data = OrderService::store($this->user_id, $request->validated());
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
            'data' => $this->OrderRepository->getOrderById($this->user_id,$order->id)
        ]);
    }

    public function updateItem(UpdateOrderRequest $request, $id)
    {
        $data = OrderService::updateItem($this->user_id,$id, $request->validated());


        return response()->json([
            'data' => $data
        ]);
    }

    public function destroy(Order $order)
    {
        OrderService::deleteItem( $order->id);
        $this->OrderRepository->deleteOrder($this->user_id, $order->id);

        return response()->json($order->id, Response::HTTP_NO_CONTENT);
    }
}
