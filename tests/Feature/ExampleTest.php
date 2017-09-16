<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testRegister()
    {
        $response = $this->json('POST', 'v2/auth/register',
            [
                'name' => 'chango',
                'email' => 'gudu.chango@gmail.com',
                'password' => 'secret',
            ]
        );

        $response
            ->assertStatus(200);
    }

    public function testLogin()
    {
        $response = $this->json('POST', 'v2/auth/login',
            [
                'name' => 'chango',
                'password' => 'secret',
            ]
        );

        $response
            ->assertStatus(200);
    }
}
