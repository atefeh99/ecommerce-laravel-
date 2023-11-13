<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{


    public function testRegisterSuccessfully()
    {
        $user = [
            'name' => 'UserTest',
            'email' => 'user@test.com',
            'password' => 'testpass',
            'password_confirmation' => 'testpass'
        ];

        $this->json('POST', 'api/register', $user)
            ->assertStatus(201);
        $this->assertDatabaseHas('users', $user);
    }
}
