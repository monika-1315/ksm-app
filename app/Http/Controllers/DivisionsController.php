<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

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
            ->where('is_authorized', '=', 1)
            ->rightJoin('divisions', 'users.division', '=', 'divisions.id')
            ->where('is_active', '=', 1)
            ->selectRaw('divisions.town, count(users.id) cnt1 ')
            ->groupByRaw('divisions.town');

        $data = DB::table('users')
            ->where('is_authorized', '=', 0)
            ->rightJoin('divisions', 'users.division', '=', 'divisions.id')
            ->where('is_active', '=', 1)
            ->selectRaw('divisions.town, count(users.id) cnt0, sel.cnt1')
            ->groupByRaw('divisions.town, sel.cnt1')
            ->joinSub($select, 'sel', function ($join) {
                $join->on('divisions.town', '=', 'sel.town');
            })->get();
        
        return response()->json($data);
    }
    
    public function getDivisions()
    {
        $data = Division::where('is_active', '=', 1)
            ->get();

        return response()->json($data);
    }

    public function getDivisionById(Request $request)
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

    public function deleteDivision(Request $request)
    {
        $data = Division::find($request->get('id'));
        $data->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function updateDivision(Request $request)
    {
        $division = Division::find($request->get('id'));
        $division->town = $request->get('town');
        $division->parish = $request->get('parish');
        $division->is_active = $request->get('is_active');
        $division->save();

        return response()->json([

            'success' => true
        ]);
    }


    public function newDivision(Request $request)
    {
        $division = new Division();
        $division->town = $request->get('town');
        $division->parish = $request->get('parish');
        $division->save();

        return response()->json([

            'success' => true
        ]);
    }
}
