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
        $response->assertJsonStructure([
            '*' => [
                'id', 'division', 'title', 'about', 'price', 'start', 'end', 'location', 'timetable', 'details', 'author', 'created_at', 'modified_at'
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
        $user = User::first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token)->getPayload();
        $response = $this->json('POST', '/api/auth/allDivisions', ['token' => $token]);
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
        JWTAuth::setToken($token)->getPayload();
        $response = $this->json('POST', '/api/auth/getUser', ['token' => $token]);
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
        JWTAuth::setToken($token)->getPayload();

        $response = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])->postJson('/api/auth/getAuthorizedUsers', ['token' => $token],  [
                'HTTP_Authorization' => "Bearer $token",
            ]);

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
        JWTAuth::setToken($token)->getPayload();
        $response = $this->json('POST', '/api/auth/getAuthorizedUsersDiv', ['token' => $token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id', 'email', 'name', 'surname', 'birthdate', 'division', 'is_authorized', 'is_leadership', 'is_management'
            ]
        ])
            ->assertJsonPath('0.division', '1')
            ->assertJsonMissing(['is_authorized' => '0']);
    }

    public function testChangeManagement()
    {

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token)->getPayload();
        $response = $this->json('POST', '/api/auth/changeManagement', ['token' => $token, 'id' => 2]);
        $response->assertStatus(200)
            ->assertExactJson([
                'success' => true
            ]);
        $response = $this->json('POST', '/api/auth/changeManagement', ['token' => $token, 'id' => 2]);
        $response->assertStatus(200)
            ->assertExactJson([
                'success' => true
            ]);
    }

    public function testChangeLeadership()
    {

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token)->getPayload();
        $response = $this->json('POST', '/api/auth/changeLeadership', ['token' => $token, 'id' => 2]);
        $response->assertStatus(200);
        $response->assertStatus(200)
            ->assertExactJson([
                'success' => true
            ]);
        $response = $this->json('POST', '/api/auth/changeLeadership', ['token' => $token, 'id' => 2]);
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

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token)->getPayload();
        $response = $this->json('POST', '/api/auth/getMessages', ['token' => $token, 'card' => 'A']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id', 'receiver_group', 'division', 'title', 'body', 'division', 'published_at', 'author', 'modified'
                ]
            ]
        ]);
    }

    public function testGetParticipants()
    {
        $user = User::first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token)->getPayload();
        $response = $this->json('POST', '/api/auth/getParticipants', ['token' => $token,  'id' => 17]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'is_sure', 'id', 'name', 'surname', 'town', 'parish',
            ]
        ]);
    }

    public function testNewPassword()
    {
        $response = $this->json('POST', '/api/forgotPassword', ['email' => '']);
        $response->assertStatus(422);
        $response->assertJson(['message' => 'The given data was invalid.', 'errors' =>
        ['email' => ['Pole email jest wymagane.']]]);

        $response = $this->json('POST', '/api/forgotPassword', ['email' => 1]);
        $response->assertStatus(422);
        $response->assertJson(['message' => 'The given data was invalid.', 'errors' =>
        ['email' => ['The email must be a valid email address.']]]);

        $response = $this->json('POST', '/api/forgotPassword', ['email' => 'moniusiar@gmail.com']);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'moniusiar@gmail.com',
            'password' => 'password'
        ]);
        $response->assertJsonFragment([
            'success' => false
        ]);
    }

    public function testUpdateUser()
    {
        $token = JWTAuth::fromUser(User::first());
        JWTAuth::setToken($token)->getPayload();
        $response = $this->json('POST', '/api/auth/updateUser', [
            'token' => $token, 'email' => 'moniusiar',
            'name' => 'Monika', 'surname' => 'K', 'password' => 'password', 'confirmPassword' => 'password',
            'birthdate' => "1998-03-15", 'division' => 1, 'id' => 1, 'is_leadership' => 1, 'is_management' => 1, 'wantMessages' => 1
        ]);
        $response->assertStatus(422);
        $response->assertJson(['message' => 'The given data was invalid.', 'errors' =>
        ['email' => ['The email must be a valid email address.']]]);

        $response = $this->json('POST', '/api/auth/updateUser', [
            'token' => $token, 'email' => 'moniusiar@gmail.com',
            'name' => 'Monika', 'surname' => 'K', 'password' => 'password', 'confirmPassword' => 'password',
            'birthdate' => "199803-15", 'division' => 1, 'id' => 1, 'is_leadership' => 1, 'is_management' => 1, 'wantMessages' => 1
        ]);
        $response->assertStatus(422);
        $response->assertJson(['message' => 'The given data was invalid.', 'errors' =>
        ['birthdate' => ['The birthdate is not a valid date.']]]);

        $response = $this->json('POST', '/api/auth/updateUser', [
            'token' => $token, 'email' => 'moniusiar@gmail.com',
            'name' => 'Monika', 'surname' => 'K', 'password' => 'password', 'confirmPassword' => 'password',
            'birthdate' => "1998-03-15", 'division' => 1, 'id' => 1, 'is_leadership' => 's', 'is_management' => 1, 'wantMessages' => 1
        ]);
        $response->assertStatus(422);
        $response->assertJson(['message' => 'The given data was invalid.', 'errors' =>
        ['is_leadership' => ['The is leadership field must be true or false.']]]);

        $response = $this->json('POST', '/api/auth/updateUser', [
            'token' => $token, 'email' => 'moniusiar@gmail.com',
            'name' => 'Monika', 'surname' => 'K', 'password' => 'password', 'confirmPassword' => 'password',
            'birthdate' => "1998-03-15", 'division' => 1, 'id' => 1, 'is_leadership' => 1, 'is_management' => 1, 'wantMessages' => 1
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
