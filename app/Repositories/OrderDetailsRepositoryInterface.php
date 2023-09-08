<?php

namespace App\Repositories;

interface OrderDetailsRepositoryInterface
{
    public function getAllOrderDetail($orderId);

    public function getOrderDetailById($orderDetailId);

    public function deleteOrderDetail($orderDetailId);

    public function createOrderDetail(array $orderDetailData);

    public function updateOrderDetail(array $orderDetailData);

    public function getOrderDetailByOrderIdAndProductId($orderId, $productId);

}
