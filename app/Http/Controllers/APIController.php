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
        if ($request->get('is_leadership')){
        $data = Message::where('receiver_group', '=', 1)
                        ->where('division', '=', $div)
                  ->orWhere('receiver_group', '!=', 1)
            ->orderby('published_at', 'desc')
            ->get();
        }else{
            $data = Message::where('receiver_group', '=', 1)
                      ->where('division', '=', $div)
                    ->orWhere('receiver_group', '=', 0)
            ->orderby('published_at', 'desc')
            ->get();
        }
   
        return response()->json($data);
    }

    public function newMessage(Request $request)
    {
        $message = new Message();
        $message->receiver_group = $request->get('receiver_group');
        if ($request->get('receiver_group')===1)
            $message->division = $request->get('division');
        $message->title = $request->get('title');
        $message->body = $request->get('body');
        $message->published_at = date("Y-m-d H:i:s");
        $message->author = $request->get('author');
        $message->save();
   
        return response()->json([

            'success' => true
        ]);
    }

    public function getMessageById(Request $request)
    {
        $data = Message::where('id', '=', $request->get('id'))
                ->get();
   
        return response()->json($data);
    }

    public function getMessageByAuthor(Request $request)
    {
        $data = Message::where('author', '=', $request->get('author'))
                ->get();
   
        return response()->json($data);
    }

    public function editMessage(Request $request)
    {
        $message = Message::find($request->get('id'));
        $message->receiver_group = $request->get('receiver_group');
        if ($request->get('division')!==-1)
            $message->division = $request->get('division');
        $message->title = $request->get('title');
        $message->body = $request->get('body');
        $message->modified = 1;
        $message->author = $request->get('author');
        $message->save();
   
        return response()->json([

            'success' => true
        ]);
    }

    public function deleteMessage(Request $request)
    {
        $data = Message::find($request->get('id'));
        $data->delete();
   
        return response()->json([
            'success' => true
        ]);
    }
}
