<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function get_one_order_by_id_test()
    {
        $order = factory(Order::class)->create();
        $response = $this->get("/api/orders/$order->id");
        $response->assertOk();
        $this->assertJson($order);
    }
}
