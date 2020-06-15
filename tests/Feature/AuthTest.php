<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    public function testLogin()
    {

        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'moniusiar@gmail.com',
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'user'
            ]);
    }

    public function testLogout()
    {
        $user = User::first();
        $token = JWTAuth::fromUser($user);

        $response = $this->json('GET', '/api/auth/logout?token=' . $token, []);

        $response
            // ->dump()
            ->assertStatus(200)
            ->assertExactJson([
                'success' => true
            ]);
    }

    public function testRegister()
    {
        
        $response = $this->json('POST', '/api/auth/register', [
            'name'=> 'new neme',
            'division'=> 1
        ]);

        $response
            // ->dump()
            ->assertStatus(422)
            ->assertExactJson([
                'errors'=>[
                    'birthdate'=>['Pole birthdate jest wymagane.'],
                    'confirmPassword'=>['Pole confirm password jest wymagane.'],
                    'email'=>['Pole email jest wymagane.'],
                    'password'=>['Pole password jest wymagane.'],
                    'surname'=>['Pole surname jest wymagane.']
                ],
                'message'=>"The given data was invalid."
                    
            ]);
    }

   
}
