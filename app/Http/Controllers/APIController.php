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

    public function getUser(Request $request)
    {
        $data = User::where('email', $request->only('email'))
                ->get();
   
        return response()->json($data);
    }

    public function updateUser(Request $request){

        try{
        $user= User::find($request->get('id'));
        $user->name=$request->get('name');
        $user->surname=$request->get('surname');
        $user->email=$request->get('email');
        $user->password=bcrypt($request->get('password'));
        $user-> birthdate=$request->get('birthdate');
        $user->division=$request->get('division');
        $user->save();
        return response()->json([
            'success' => true
        ]);
        }
        catch (Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'message' => [
                        'Either Email or Password Invalid'
                    ]
                ],
            ]);
        }
    }
}
