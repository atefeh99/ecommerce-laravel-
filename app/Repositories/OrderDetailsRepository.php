<?php

namespace App\Repositories;

use App\Models\OrderDetails;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderDetailsRepository implements OrderDetailsRepositoryInterface
{

    public function getAllOrderDetail($orderId)
    {
        $items = OrderDetails::where('order_id', $orderId)->get();
        if ($items->count > 0) {
            return $items;
        } else {
            throw new ModelNotFoundException();
        }
    }

    public function getOrderDetailById($orderDetailId)
    {
        // TODO: Implement getOrderDetailById() method.
    }

    public function deleteOrderDetail($orderDetailId)
    {
        OrderDetails::destroy($orderDetailId);

    }

    public function createOrderDetail(array $orderDetailData)
    {
        OrderDetails::create($orderDetailData);
    }

    public function updateOrderDetail(array $orderDetailData)
    {
        // TODO: Implement updateOrderDetail() method.
    }

    public function getOrderDetailByOrderIdAndProductId($orderId, $productId)
    {
        $item = OrderDetails::where($orderId)->where($productId)->get();
        if ($item->count > 0) {
            return $item;
        } else {
            return null;
        }
    }
}
