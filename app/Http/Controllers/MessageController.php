<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMessagesRequest;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\IdRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Division;
use App\Message;

class MessageController extends Controller
{

    public function getMessages(GetMessagesRequest $request)
    {
       define('PAGE_SIZE',3);
        $div = $request->get('division');
        switch ($request->get('card')) {
            case ('A'):

                if ($request->get('is_leadership')) {
                    $data = DB::table('messages')
                        ->join('users', 'messages.author', '=', 'users.id')
                        ->where('receiver_group', '=', 1)
                        ->where('messages.division', '=', $div)
                        ->orWhere('receiver_group', '!=', 1)
                        ->select('messages.*', 'users.name', 'users.surname')
                        ->orderby('published_at', 'desc')
                        ->paginate(constant('PAGE_SIZE'));
                } else {
                    $data = DB::table('messages')
                        ->join('users', 'messages.author', '=', 'users.id')
                        ->where('receiver_group', '=', 1)
                        ->where('messages.division', '=', $div)
                        ->orWhere('receiver_group', '=', 0)
                        ->select('messages.*', 'users.name', 'users.surname')
                        ->orderby('published_at', 'desc')
                        ->paginate(constant('PAGE_SIZE'));
                }
                break;
            case ('B'):
                $data = DB::table('messages')
                    ->join('users', 'messages.author', '=', 'users.id')
                    ->where('receiver_group', '=', 1)
                    ->where('messages.division', '=', $div)
                    ->select('messages.*', 'users.name', 'users.surname')
                    ->orderby('published_at', 'desc')
                    ->paginate(constant('PAGE_SIZE'));
                break;
            case ('C'):
                $data = DB::table('messages')
                    ->join('users', 'messages.author', '=', 'users.id')
                    ->where('receiver_group', '=', 0)
                    ->select('messages.*', 'users.name', 'users.surname')
                    ->orderby('published_at', 'desc')
                    ->paginate(constant('PAGE_SIZE'));
                break;
            case ('D'):
                if ($request->get('is_leadership')) {
                    $data = DB::table('messages')
                        ->join('users', 'messages.author', '=', 'users.id')
                        ->where('receiver_group', '=', 2)
                        ->select('messages.*', 'users.name', 'users.surname')
                        ->orderby('published_at', 'desc')
                        ->paginate(constant('PAGE_SIZE'));
                } else
                    $data = [];
                break;
        }

        return response()->json($data);
    }

    public function newMessage( MessageRequest $request)
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

    public function getMessageById(IdRequest $request)
    {
        $data = Message::where('id', '=', $request->get('id'))
            ->get();

        return response()->json($data);
    }

    public function getMessageByAuthor(IdRequest $request)
    {
        $data = Message::where('author', '=', $request->get('id'))
            ->orderby('published_at', 'desc')
            ->get();

        return response()->json($data);
    }

    public function editMessage( MessageRequest $request)
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

    public function deleteMessage(IdRequest $request)
    {
        $data = Message::find($request->get('id'));
        $data->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
