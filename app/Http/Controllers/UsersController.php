<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use App\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function getUser(Request $request)
    {
        $data = User::where('email', $request->only('email'))
            ->get();

        return response()->json($data);
    }

    public function authorizeUser(Request $request)
    {
        $user = User::find($request->get('id'));
        $user->is_authorized = 1;
        $user->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function getUnauthorizedUsers(Request $request)
    {
        $data = User::where('division', $request->only('division'))
            ->where('is_authorized', 0)
            ->orderby('id', 'desc')
            ->get();
        return response()->json($data);
    }

    public function getAuthorizedUsers()
    {
        $data = User::where('is_authorized', 1)
            ->get();
        return response()->json($data);
    }

    public function getAuthorizedUsersDiv(Request $request)
    {
        $data = User::where('division', $request->only('division'))
            ->where('is_authorized', 1)
            ->get();
        return response()->json($data);
    }

    public function updateUser(Request $request)
    {


        $user = User::find($request->get('id'));
        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->email = $request->get('email');
        if ($request->get('password') === $request->get('confirmPassword') && $request->get('password') !== '')
            $user->password = bcrypt($request->get('password'));
        $user->birthdate = $request->get('birthdate');
        $user->division = $request->get('division');
        $user->is_leadership = $request->get('is_leadership');
        $user->is_management = $request->get('is_management');
        $user->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function changeLeadership(Request $request)
    {
        $user = User::find($request->get('id'));

        if ($user->is_leadership === 1)
            $user->is_leadership = 0;
        else
            $user->is_leadership = 1;
        $user->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function changeManagement(Request $request)
    {
        $user = User::find($request->get('id'));

        if ($user->is_management === 1)
            $user->is_management = 0;
        else
            $user->is_management = 1;
        $user->save();
        return response()->json([
            'success' => true
        ]);
    }
}
