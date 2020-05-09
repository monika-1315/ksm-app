<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Division;
use App\Message;

class MessageController extends Controller
{


    public function getMessages(Request $request)
    {
        $div = $request->get('division');
        if ($request->get('is_leadership')) {
            $data = Message::where('receiver_group', '=', 1)
                ->where('division', '=', $div)
                ->orWhere('receiver_group', '!=', 1)
                ->orderby('published_at', 'desc')
                ->paginate(2);
                // ->get();
        } else {
            $data = Message::where('receiver_group', '=', 1)
                ->where('division', '=', $div)
                ->orWhere('receiver_group', '=', 0)
                ->orderby('published_at', 'desc')
                ->paginate(3);
                // ->get();
        }
        
        return response()->json($data);
    }

    public function newMessage(Request $request)
    {
        $message = new Message();
        $message->receiver_group = $request->get('receiver_group');
        if ($request->get('receiver_group') === 1)
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
        $data =DB::table('messages')
                ->join('users', 'messages.author', '=', 'users.id')
                ->where('author', '=', $request->get('author'))
                ->select('messages.*', 'users.name','users.surname')
                ->orderby('published_at', 'desc')
                ->get();

        return response()->json($data);
    }

    public function editMessage(Request $request)
    {
        $message = Message::find($request->get('id'));
        $message->receiver_group = $request->get('receiver_group');
        if ($request->get('division') !== -1)
            $message->division = $request->get('division');
        $message->title = $request->get('title');
        $message->body = $request->get('body');
        $message->modified = 1;
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
