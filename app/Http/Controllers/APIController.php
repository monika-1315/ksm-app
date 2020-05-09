<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use App\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function getManagement()
    {
        $data = DB::table('users')
                ->join('managements','users.id', '=', 'managements.user_id')
                ->join('functions', 'functions.id', '=','managements.function_id')
                ->select('name', 'surname', 'function_name', 'function_mail', 'term_start', 'term_end', 'user_id')
                ->get();

        return response()->json($data);
    }

   
}
