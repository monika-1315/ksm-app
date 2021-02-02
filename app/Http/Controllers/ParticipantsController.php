<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipantRequest;
use App\Http\Requests\IdRequest;
use Illuminate\Http\Request;
use App\Event;
use App\Participant;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class ParticipantsController extends Controller
{
    
    public function getParticipants(IdRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        $event = Event::find($request->get('id'));
        $is_admin = (($event->division == null && $logged_user->is_management) ||
            ($event->division == $logged_user->division &&
                $logged_user->is_leadership) ||
            $event->author == $logged_user->id);

        if ($is_admin) {
            $select = DB::table('participants')
                ->where('event_id', '=', $request->get('id'));

            $data = DB::table('users')
                ->joinSub($select, 'sel', function ($join) {
                    $join->on('users.id', '=', 'sel.user_id');
                })->join('divisions', 'users.division', '=', 'divisions.id')
                ->select('sel.is_sure', 'users.id', 'users.name', 'users.surname', 'users.birthdate', 'divisions.town', 'divisions.parish')
                ->get();
        } else {
            $select = DB::table('participants')
                ->where('event_id', '=', $request->get('id'))
                ->where('visible', '=', 1);
            $data = DB::table('users')
                ->joinSub($select, 'sel', function ($join) {
                    $join->on('users.id', '=', 'sel.user_id');
                })->join('divisions', 'users.division', '=', 'divisions.id')
                ->select('sel.is_sure', 'users.id', 'users.name', 'users.surname', 'divisions.town', 'divisions.parish')
                ->get();
        }


        return response()->json($data);
    }

    public static function getParticipantsForMails($event_id)
    {
        $participants = Participant::where('event_id', '=', $event_id)
            ->where('want_messages', '=', 1)
            ->select('user_id')
            ->get();
        return $participants;
    }


    public function checkParticipant(IdRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        $data = Participant::where('event_id', '=', $request->get('id'))
            ->where('user_id', '=', $logged_user->id)
            ->select('visible', 'is_sure', 'want_messages')
            ->get();

        return response()->json($data);
    }

    public function newParticipant(ParticipantRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        $participant = new Participant();
        $participant->event_id = $request->get('event_id');
        $participant->user_id =  $logged_user->id;
        $participant->visible = $request->get('visible');
        $participant->is_sure = $request->get('is_sure');
        $participant->want_messages = $request->get('want_messages');
        $participant->save();

        return response()->json([

            'success' => true
        ]);
    }

    public function editParticipant(ParticipantRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        $data = [
            'event_id' => $request->get('event_id'),
            'user_id' =>$logged_user->id,
            'visible' => $request->get('visible'),
            'is_sure' => $request->get('is_sure'),
            'want_messages' => $request->get('want_messages')
        ];

        Participant::where([
            'event_id' => $request->get('event_id'),
            'user_id' => $logged_user->id,
        ])->update($data);

        return response()->json([
            'success' => true
        ]);
    }

    public function deleteParticipant(ParticipantRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->get('token'));
        Participant::where([
            'event_id' => $request->get('event_id'),
            'user_id' => $logged_user->id,
        ])->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
