<?php

namespace App\Http\Services;

use App\Models\OrderDetails;
use App\Models\Product;
use App\Repositories\OrderDetailsRepository;
use App\Repositories\OrderDetailsRepositoryInterface;

class OrderDetailService
{
    public function __construct(
        private OrderDetailsRepositoryInterface $repository,
        protected ProductService                $productService
    )
    {
    }

    public function createOrderDetails(int $orderId, array $orderDetails)
    {
        foreach ($orderDetails as $orderDetail) {
            $orderDetailData = [
                'order_id' => $orderId,
                'quantity' => $orderDetail['quantity'],
                'product_id' => $orderDetail['product_id'],
            ];
            $this->repository->createOrderDetail($orderDetailData);

        }
    }

    public function updateOrderDetails(int $orderId, array $updataData)
    {
        foreach ($updataData as $updateDatum) {
            $orderDetailItem = $this->repository->getOrderDetailByOrderIdAndProductId($orderId, $updateDatum['product_id']);
            if ($orderDetailItem) {
                if ($updateDatum['quantity'] == 0) {
                    $this->updateOrderDetailOnZero($orderDetailItem);
                } elseif ($updateDatum['quantity'] < $orderDetailItem->quantity) {
                    $this->updateOrderDetailOnDecrease($orderDetailItem, $updateDatum);
                } elseif ($updateDatum['quantity'] > $orderDetailItem->quantity) {
                    $this->updateOrderDetailOnIncrease($orderDetailItem, $updateDatum);
                }
            } else {
                $this->repository->createOrderDetail($updateDatum);
            }
        }
    }

    public function deleteOrderDetailsByOrderId(int $orderId)
    {
        $orderDetails = $this->repository->getAllOrderDetail($orderId);
        foreach ($orderDetails as $orderDetail) {
            $this->repository->deleteOrderDetail($orderDetail->id);
        }
    }

    public function updateOrderDetailOnZero(OrderDetails $orderDetailItem)
    {
        $this->repository->deleteOrderDetail($orderDetailItem->id);
        $product = $this->productService->show($orderDetailItem->product_id);
        $inventory = $product->inventory + $orderDetailItem->quantity;
        $this->productService->update($orderDetailItem->product_id, ['inventory' => $inventory]);
    }

    public function updateOrderDetailOnIncrease(OrderDetails $orderDetailItem, array $updateDatum)
    {
        $number = $updateDatum['quantity'] - $orderDetailItem->quantity;
        $product = $this->productService->show($orderDetailItem->product_id);
        if ($number > $product->inventory) {
            throw new BadRequestHttpException();
        }
        $inventory = $product->inventory - $number;
        $this->productService->update($product->id, ['inventory' => $inventory]);
        $quantity = $orderDetailItem->quantity + $number;
        $this->repository->updateOrderDetail($orderDetailItem->id, ['quantity' => $quantity]);
    }

    public function updateOrderDetailOnDecrease(OrderDetails $orderDetailItem, array $updateDatum)
    {
        $this->repository->updateOrderDetail($orderDetailItem->id, $updateDatum['quantity']);
        $differ = $orderDetailItem->quantity - $updateDatum['quantity'];
        $product = $this->productService->show($orderDetailItem->product_id);
        $inventory = $product->inventory + $differ;
        $this->productService->update($product->id, ['inventory' => $inventory]);
    }
}
