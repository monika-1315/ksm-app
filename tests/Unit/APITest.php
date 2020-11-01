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
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id', 'is_active', 'parish', 'town'
            ]
        ]);
    }

    public function testGetOldEvents()
    {
        $response = $this->get('/api/getOldEvents');
        $response->assertStatus(200);
        // $response->dump();
        $response->assertJsonStructure([
            '*' => [
                'id', 'division', 'title', 'about', 'price', 'start', 'end', 'location', 'timetable', 'details', 'author', 'created_at','modified_at'
            ]
        ]);
    }

    public function testGetDivisionsById()
    {

        $response = $this->get('/api/getDivisionById?id=1');
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
        // $response->assertStatus(405);

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
        $response->assertSee('html');

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
        $response->assertSee('html');

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
        $response->assertSee('html');

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        $response = $this->json('POST', '/api/auth/getAuthorizedUsersDiv', ['token' =>$token, 'division' =>1]);
        
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
        $response->assertStatus(200);
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
        $response->assertSee('html');
        $response->assertSee('noscript');        
        $response->assertSessionDoesntHaveErrors();
        // $response->dump();
        $user = User::first();
        $token = JWTAuth::fromUser($user);
        $response = $this->json('POST', '/api/auth/getMessages', ['token' =>$token, 'division' =>1, 'card'=>'A', 'is_leadership'=>'1']);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'current_page',
            'data' =>[
            '*' => [
                'id', 'receiver_group', 'division', 'title', 'body', 'division', 'published_at', 'author', 'modified'
            ]]
        ]);
    }

    public function testNewPassword()
    {
        $response = $this->json('POST', '/api/forgotPassword', ['email' => '']);
        $response->assertStatus(422);
        $response->assertJson(['message'=>'The given data was invalid.','errors'=>
                ['email'=>['Pole email jest wymagane.']]]);

        $response = $this->json('POST', '/api/forgotPassword', ['email' => 1]);
        $response->assertStatus(422);
        $response->assertJson(['message'=>'The given data was invalid.','errors'=>
                ['email'=>['The email must be a valid email address.']]]);

        // $response = $this->json('POST', '/api/forgotPassword', ['email' => 'moniusiar@gmail.com']);
        // $response->assertStatus(200);
        // $response->assertJson(['success'=>true]);

    }


}
