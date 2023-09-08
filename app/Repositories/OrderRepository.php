<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderRepository implements OrderRepositoryInterface
{

    public function getAllOrders($userId)
    {
        return Order::with('order_details')->where('user_id', $userId)->get();
    }

    public function getOrderById($userId, $orderId)
    {
        $items = Order::with('order_details')
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
        Order::where('user_id', $userId)->destroy($orderId);
    }

    public function createOrder(array $orderData)
    {
        return Order::create($orderData);
    }

    public function updateOrder($userId, $orderId, array $orderData)
    {
        $item = Order::where('user_id',$userId)->where('order_id',$orderId)->get();
        if(!$item || $item->count < 0){
            throw new ModelNotFoundException();
        }
        $item->update($orderData);
        $item->save();
        return $item;
//        return Order::whereId($OrderId)->update($OrderData);
    }


}
