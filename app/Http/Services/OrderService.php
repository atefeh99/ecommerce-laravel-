<?php

namespace App\Http\Services;

use App\Models\OrderDetails;
use App\Repositories\OrderDetailsRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderService
{
    public static function store($userId, $data)
    {
        $productRepository = new ProductRepository();
        $orderRepository = new OrderRepository();
        $orderDetailRepository = new OrderDetailsRepository();

        $total = 0;
        foreach ($data as $datum) {
            $product = $productRepository->getProductById($datum['product_id']);
            if ($product->inventory >= $datum['quantity']) {
                $product->inventory -= $datum['quantity'];
                $total += $datum['quantity'] * $product->price;
            } else {
                throw new BadRequestHttpException();
            }
        }
        $orderData = [
            'total' => $total,
            'user_id' => $userId
        ];
        $order = $orderRepository->createOrder($orderData);
        foreach ($data as $datum) {
            $orderDetailData = [
                'order_id' => $order->id,
                'quantity' => $datum['quantity'],
                'product_id' => $datum['product_id'],
            ];
            $orderDetailRepository->createOrderDetail($orderDetailData);

        }
        return [
            'total' => $total
        ];
    }

    public static function updateItem($userId, $orderId, $data)
    {
        $productRepository = new ProductRepository();
        $orderRepository = new OrderRepository();
        $orderDetailRepository = new OrderDetailsRepository();
        $order = $orderRepository->getOrderById($userId, $orderId);

        foreach ($data as $datum) {
            $product = $productRepository->getProductById($datum['product_id']);
            $orderDetail = $orderDetailRepository->getOrderDetailByOrderIdAndProductId($orderId, $datum['product_id']);
            if ($orderDetail) {
                if ($datum['quantity'] == 0) {
                    $order->total -= $orderDetail->quantity * $product->price;
                    $orderDetailRepository->deleteOrderDetail($orderDetail->id);
                    $product->inventory += $orderDetail->quantity;
                } elseif ($datum['quantity'] < $orderDetail->quantity) {

                    $number = $orderDetail->quantity - $datum['quantity'];
                    $order->total -= $datum['quantity'] * $product->price;
                    $orderDetail->quantity = $datum['quantity'];
                    $orderDetail->save();
                    $product->inventory -= $number;
                } elseif ($datum['quantity'] > $orderDetail->quantity) {
                    $number = $datum['quantity'] - $orderDetail->quantity;
                    if ($number > $product->inventory) {
                        throw new BadRequestHttpException();
                    }
                    $order->total += $number * $product->price;
                    $product->inventory -= $number;
                    $orderDetail->quantity += $number;
                    $orderDetail->save();
                }
            } else {
                if ($product->inventory >= $datum['quantity']) {
                    $product->inventory -= $datum['quantity'];
                    $orderDetailRepository->createOrderDetail($data);
                    $order->total += $datum['quantity'] * $product->price;
                } else {
                    throw new BadRequestHttpException();
                }
            }
            $product->save();

        }
        return $order->save();
    }

    public static function deleteItem($orderId)
    {
        $orderDetailRepository = new OrderDetailsRepository();

        $orderDetails = $orderDetailRepository->getAllOrderDetail($orderId);
        foreach ($orderDetails as $orderDetail) {
            $orderDetailRepository->deleteOrderDetail($orderDetail->id);
        }
    }

}
