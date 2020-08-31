<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\DivisionIdRequest;
use App\Http\Requests\IdRequest;
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

    public function getUser(EmailRequest $request)
    {
        $data = User::where('email', $request->only('email'))
            ->get();

        return response()->json($data);
    }

    public function authorizeUser(IdRequest $request)
    {
        $user = User::find($request->get('id'));
        $user->is_authorized = 1;
        $user->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function getUnauthorizedUsers(DivisionIdRequest $request)
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

    public function getAuthorizedUsersDiv(DivisionIdRequest $request)
    {
        $data = User::where('division', $request->only('division'))
            ->where('is_authorized', 1)
            ->get();
        return response()->json($data);
    }

    public function updateUser(UserRequest $request)
    {

        $user = User::find($request->get('id'));
        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->email = $request->get('email');
        if ($request->get('password') === $request->get('confirmPassword') && $request->get('password') !== null)
            $user->password = bcrypt($request->get('password'));
        $user->birthdate = $request->get('birthdate');
        $user->division = $request->get('division');
        $user->want_messages = $request->get('wantMessages');
        $user->is_leadership = $request->get('is_leadership');
        $user->is_management = $request->get('is_management');
        $user->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function changeLeadership(IdRequest $request)
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

    public function changeManagement(IdRequest $request)
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
