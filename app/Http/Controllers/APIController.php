<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Division;
use App\User;
use Illuminate\Support\Facades\Auth;

class APIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function getDivisions()
    {
        $data = Division::get();
   
        return response()->json($data);
    }
}
