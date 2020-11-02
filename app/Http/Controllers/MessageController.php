<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMessagesRequest;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\IdRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Division;
use App\Message;

use Tymon\JWTAuth\Facades\JWTAuth;

class MessageController extends Controller
{

    public function getMessages(GetMessagesRequest $request)
    {
        $user = JWTAuth::toUser($request->get('token'));
        define('PAGE_SIZE', 4);
        $div = $user->division;
        switch ($request->get('card')) {
            case ('A'):

                if ($user->is_leadership) {
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
                if ($user->is_leadership) {
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

    public function newMessage(MessageRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($logged_user->is_authorized === 0 || ($logged_user->is_leadership === 0 && $logged_user->is_management === 0))
            return response()->json([
                'success' => false
            ]);

        $message = new Message();
        $message->receiver_group = $request->get('receiver_group');
        if ($request->get('receiver_group') === 1)
            $message->division = $request->get('division');
        $message->title = $request->get('title');
        $message->body = $request->get('body');
        $message->published_at = date("Y-m-d H:i:s");
        $message->author = $logged_user->id;
        $message->save();

        $recipients = UsersController::getRecipients($request->get('division'), $request->get('receiver_group'));
        foreach ($recipients as $p) {
            Mail::send(
                $p->email,
                'Nowy komunikat w aplikacji KSM DL',
                'Witaj!<br>W aplikacji KSM DL właśnie pojawiło się dla Ciebie nowe ogłoszenie:<br><br><b>'
                    . $request->get('title')
                    . '</b><br><p style="white-space: pre-line"><i>' . $request->get('body') . '</i></p>'
            );
        }

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

    public function getMessageByAuthor(Request $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        $data = Message::where('author', '=', $logged_user->id)
            ->orderby('published_at', 'desc')
            ->get();

        return response()->json($data);
    }

    public function editMessage(MessageRequest $request)
    {
        $message = Message::find($request->get('id'));
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($message->author !== $logged_user->id)
            return response()->json(['success' => false]);
        $message->receiver_group = $request->get('receiver_group');
        if ($request->get('division') !== -1)
            $message->division = $request->get('division');
        $message->title = $request->get('title');
        $message->body = $request->get('body');
        $message->modified = 1;
        $message->save();

        if ($request->get('email') == 1) {
            $recipients = UsersController::getRecipients($request->get('division'), $request->get('receiver_group'));
            foreach ($recipients as $p) {
                Mail::send(
                    $p->email,
                    'Edytowano komunikat w aplikacji KSM DL',
                    'Witaj!<br>W aplikacji KSM DL właśnie zostało zmodyfikowane ogłoszenie:<br><br><b>'
                        . $request->get('title')
                        . '</b><br><i>' . $request->get('body') . '</i><br><br>Autor ogłoszenia uznał modyfikację za istotną.'
                );
            }
        }
        return response()->json([

            'success' => true
        ]);
    }

    public function deleteMessage(IdRequest $request)
    {
        $data = Message::find($request->get('id'));
        $logged_user = JWTAuth::toUser($request->get('token'));
        if ($data->author !== $logged_user->id)
            return response()->json(['success' => false]);

        $data->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
