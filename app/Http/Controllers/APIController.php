<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Division;
use App\Message;
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

    public function getMessages(Request $request)
    {
        $div=$request->get('division');
        $data = Message::where('receiver_group', '=', 1)
                        ->where('division', '=', $div)
                  ->orWhere('receiver_group', '!=', 1)
            ->orderby('published_at', 'desc')
            ->get();
   
        return response()->json($data);
    }

    public function newMessage(Request $request)
    {
        $message = new Message();
        $message->division = $request->get('division');
        $message->receiver_group = $request->get('receiver_group');
        $message->title = $request->get('title');
        $message->body = $request->get('body');
        $message->published_at = date("Y-m-d H:i:s");
        $message->author = $request->get('author');
        $message->save();
   
        return response()->json([

            'success' => true
        ]);
    }
}
