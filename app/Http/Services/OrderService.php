<?php

namespace App\Http\Services;

use App\Repositories\OrderRepositoryInterface;

class OrderService
{

    public function __construct(
        private OrderRepositoryInterface $repository,
        protected OrderDetailService     $orderDetailService
    )
    {
    }

    public function store(string $userId, array $data): array
    {
        $order = $this->repository->createOrder([
            'user_id' => $userId
        ]);
        $this->orderDetailService->createOrderDetails($order->id, $data);
        return $order;
    }

    public function updateItem(string $userId, int $orderId, array $data): array
    {
        $this->orderDetailService->updateOrderDetails($orderId, $data);
        $this->repository->updateOrder($userId, $orderId, $data);
        return $this->repository->getOrderById($userId, $orderId);
    }

    public function deleteItem(string $userId, int $orderId): array
    {
        $this->orderDetailService->deleteOrderDetailsByOrderId($orderId);
        return $this->repository->deleteOrder($userId, $orderId);

    }


}
