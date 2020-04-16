<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Division;
  
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
