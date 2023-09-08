<?php

namespace App\Repositories;

interface OrderRepositoryInterface
{
    public function getAllOrders($userId);

    public function getOrderById($userId, $orderId);

    public function deleteOrder($userId, $orderId);

    public function createOrder(array $orderData);

    public function updateOrder($userId, $orderId, array $orderData);

}
