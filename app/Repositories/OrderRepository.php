<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(protected Order $order)
    {
    }

    public function getAllOrders($userId)
    {
        return $this->order->with('order_details')->where('user_id', $userId)->get();
    }

    public function getOrderById($userId, $orderId)
    {
        $items = $this->order->with('order_details')
            ->where('user_id', $userId)
            ->where('id', $orderId)
            ->get();
        if ($items->count > 0) {
            return $items;
        } else {
            throw new ModelNotFoundException();
        }
    }

    public function deleteOrder($userId, $orderId)
    {
        $this->order->where('user_id', $userId)->destroy($orderId);
    }

    public function createOrder(array $orderData)
    {
        return $this->order->create($orderData);
    }

    public function updateOrder($userId, $orderId, array $orderData)
    {
        $item = $this->order->where('user_id', $userId)->where('order_id', $orderId)->get();
        if (!$item || $item->count < 0) {
            throw new ModelNotFoundException();
        }
        $item->update($orderData);
        $item->save();
        return $item;
    }


}
