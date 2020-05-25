<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class APITest extends TestCase
{
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
        $token = JWTAuth::fromUser($user);
        $response = $this->json('POST', '/api/auth/allDivisions', ['token' =>$token]);
        // $response->dump();
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id', 'is_active', 'parish', 'town'
            ]
        ]);
    }

    public function testGetDivisionsStats()
    {
        $response = $this->get('/api/getDivisionsStats');
        // $response->dump();
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'town', 'parish', 'cnt_all', 'cnt_aut'
            ]
        ]);
    }

    public function testGetUser()
    {
        $response = $this->get('/api/auth/getUser');
        $response->assertStatus(405);

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        $response = $this->json('POST', '/api/auth/getUser', ['token' =>$token, 'email' => 'moniusiar@gmail.com']);
        // $response->dump();
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id', 'email', 'name', 'surname', 'birthdate', 'division', 'is_authorized', 'is_leadership', 'is_management'
            ]
        ]);
    }
    public function testAuthorizedUsers()
    {
        $response = $this->get('/api/auth/getAuthorizedUsers');
        $response->assertStatus(405);

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        $response = $this->json('POST', '/api/auth/getAuthorizedUsers', ['token' =>$token]);
        // $response->dump();
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id', 'email', 'name', 'surname', 'birthdate', 'division', 'is_authorized', 'is_leadership', 'is_management'
            ]
        ])
        ->assertJsonPath('0.is_authorized', '1');
    }

    public function testAuthorizedUsersDiv()
    {
        $response = $this->get('/api/auth/getAuthorizedUsersDiv');
        $response->assertStatus(405);

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        $response = $this->json('POST', '/api/auth/getAuthorizedUsersDiv', ['token' =>$token, 'division' =>1]);
        // $response->dump();
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id', 'email', 'name', 'surname', 'birthdate', 'division', 'is_authorized', 'is_leadership', 'is_management'
            ]
        ])
        ->assertJsonPath('0.is_authorized', '1')
        ->assertJsonPath('0.division', '1');
    }

    public function testChangeManagement()
    {

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        $response = $this->json('POST', '/api/auth/changeManagement', ['token' =>$token, 'id' => 1]);
        // $response->dump();
        $response->assertStatus(200)
            ->assertExactJson([
            'success' => true
        ]);
        $response = $this->json('POST', '/api/auth/changeManagement', ['token' =>$token, 'id' => 1]);
        // $response->dump();
        $response->assertStatus(200)
            ->assertExactJson([
            'success' => true
        ]);
    }

    public function testChangeLeadership()
    {

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        $response = $this->json('POST', '/api/auth/changeLeadership', ['token' =>$token, 'id' => 1]);
        // $response->dump();
        $response->assertStatus(200)
            ->assertExactJson([
            'success' => true
        ]);
        $response = $this->json('POST', '/api/auth/changeLeadership', ['token' =>$token, 'id' => 1]);
        // $response->dump();
        $response->assertStatus(200)
            ->assertExactJson([
            'success' => true
        ]);
    }

    public function testgetMessages()
    {
        $response = $this->get('/api/auth/getMessages');
        $response->assertStatus(405);

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        $response = $this->json('POST', '/api/auth/getMessages', ['token' =>$token, 'division' =>1, 'card'=>'A']);
        // $response->dump();
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'current_page',
            'data' =>[
            '*' => [
                'id', 'receiver_group', 'division', 'title', 'body', 'division', 'published_at', 'author', 'modified'
            ]]
        ]);
    }


}
