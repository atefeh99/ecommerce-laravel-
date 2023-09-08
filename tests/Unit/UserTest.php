<?php

namespace Tests\Unit;

use App\Models\User;
use Database\Factories\UserFactory;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function testRegisterSuccessfully()
    {
        $register = [
            'name' => 'UserTest',
            'email' => 'user@test.com',
            'password' => 'testpass',
            'password_confirmation' => 'testpass'
        ];

        $this->json('POST', 'api/register', $register)
            ->assertStatus(201)
            ;
    }



}
