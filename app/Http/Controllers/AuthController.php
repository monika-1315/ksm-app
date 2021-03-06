<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function postRegister(RegisterRequest $request)
    {
        $user = new User();

        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->birthdate = $request->get('birthdate');
        $user->division = $request->get('division');
        $user->want_messages = $request->get('wantMessages');
        $leader = $request->get('is_leadership');
        $user->is_leadership = $leader;
        $user->is_authorized = $leader;
        $user->save();

        return response()->json([

            'success' => true
        ]);
    }

    public function postLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = null;

        try {
            if (!$token = \JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'message' => [
                            'Błędny email lub hasło'
                        ]
                    ],
                ]);
            }
        } catch (\JWTAuthException $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'message' => [
                        'Either Email or Password Invalid'
                    ]
                ],
            ]);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'user' => $user,
            'token_type' => 'bearer',
        ]);
    }

    public function logout()
    {
        \JWTAuth::invalidate(\JWTAuth::getToken());
        return response()->json([
            'success' => true
        ]);
    }

    
}
