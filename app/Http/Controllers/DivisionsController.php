<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdRequest;
use App\Http\Requests\DivisionRequest;
use Illuminate\Http\Request;
use App\Division;
use Illuminate\Support\Facades\DB;

use Tymon\JWTAuth\Facades\JWTAuth;

class DivisionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function getDivisionsStats()
    {
        $select = DB::table('users')
            ->where('is_authorized', '=', 1);
           
        
        $select2 = DB::table('divisions')
            ->leftJoinSub($select, 'sel', function ($join) {
                $join->on('divisions.id', '=', 'sel.division');
            }) ->selectRaw('divisions.town,divisions.parish, divisions.id, count(sel.id) cnt_aut ')
            ->where('is_active', '=', 1)
            ->groupByRaw('divisions.town,divisions.parish, divisions.id');
            

        $data = DB::table('users')
            ->selectRaw('sel.town, sel.parish, count(users.id) cnt_all, sel.cnt_aut')
            ->groupByRaw('sel.town, sel.parish, sel.cnt_aut')
            ->rightJoinSub($select2, 'sel', function ($join) {
                $join->on('users.division', '=', 'sel.id');
            })->get();
        
        return response()->json($data);
    }
    
    public function getDivisions()
    {
        $data = Division::where('is_active', '=', 1)
            ->get();

        return response()->json($data);
    }

    public function getDivisionById(IdRequest $request)
    {
        $data = Division::where('id', '=', $request->get('id'))
            ->get();

        return response()->json($data);
    }

    public function getAllDivisions()
    {
        $data = Division::get();

        return response()->json($data);
    }

    public function deleteDivision(IdRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_management == 0 || $logged_user->is_authorized == 0)
            return response()->json([
                'success' => false
            ]);

        $data = Division::find($request->get('id'));
        $data->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function updateDivision(DivisionRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_management == 0 || $logged_user->is_authorized == 0)
            return response()->json([
                'success' => false
            ]);

        $division = Division::find($request->get('id'));
        $division->town = $request->get('town');
        $division->parish = $request->get('parish');
        $division->is_active = $request->get('is_active');
        $division->email = $request->get('email');
        $division->save();

        return response()->json([

            'success' => true
        ]);
    }


    public function newDivision(DivisionRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_management == 0 || $logged_user->is_authorized == 0)
            return response()->json([
                'success' => false
            ]);
            
        $division = new Division();
        $division->town = $request->get('town');
        $division->parish = $request->get('parish');
        $division->save();

        return response()->json([

            'success' => true
        ]);
    }
}
