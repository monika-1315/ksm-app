<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Auth;

class APITest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetDivisions()
    {
        $response = $this->get('/api/getDivisions');
        // $response->dump();
        $response->assertStatus(200);
        $response->assertJsonStructure([
                '*' => [
                'id', 'is_active', 'parish', 'town'
                ]
        ]);        
    }

    public function testGetDivisionsById()
    {
        $response = $this->get('/api/getDivisionById?id=1');
        // $response->dump();
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                'id', 'is_active', 'parish', 'town'
                ]
        ])
            ->assertJsonPath('0.id', '1');        
    }

    public function testGetAllDivisions()
    {
        $response = $this->get('/api/auth/allDivisions');
        $response->assertStatus(405);

        $user = User::first();       
        $token = \JWTAuth::fromUser($user); 
        $response= $this->json('POST', '/api/auth/allDivisions', ['token' => $token]);
        // $response->dump();
        $response->assertStatus(200);
        $response->assertJsonStructure([
                '*' => [
                'id', 'is_active', 'parish', 'town'
                ]
        ]);        
    }

    public function testLogin()
{

    $response = $this->json('POST', '/api/auth/login', [
        'email' => 'moniusiar@gmail.com',
        'password' => 'password'
    ]);

    $response
        // ->dump()
        ->assertStatus(200)
        ->assertJsonStructure([
            'access_token', 'token_type', 'user'
        ]);
}

public function testLogout()
{
    $user = User::first();
    $token = \JWTAuth::fromUser($user);

    $response = $this->json('GET', '/api/auth/logout?token=' . $token, []);

    $response
    // ->dump()
        ->assertStatus(200)
        ->assertExactJson([
            'success' => true
        ]);
}

}
