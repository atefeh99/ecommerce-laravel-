<?php

namespace Tests\Unit;

use App\Http\Services\OrderDetailService;
use App\Http\Services\OrderService;
use App\Repositories\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function deletes_order_and_order_details()
    {
        // Arrange
        $userId = 'ae003aec-2b2e-4ebc-be43-c0809429c5b2';
        $orderId = 1;
        //act
        $orderDetailServiceMock = Mockery::mock(OrderDetailService::class);
        $orderDetailServiceMock->shouldReceive('deleteOrderDetailsByOrderId')->with($orderId)->once();

        $orderRepositoryMock = Mockery::mock(OrderRepository::class);
        $orderRepositoryMock->shouldReceive('deleteOrder')->with($userId, $orderId)->once();

        $orderService = new OrderService($orderDetailServiceMock, $orderRepositoryMock);

        $result = $orderService->deleteItem($userId, $orderId);
        //assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }
}
