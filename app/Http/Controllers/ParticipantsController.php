<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipantsRequest;
use App\Http\Requests\ParticipantRequest;
use App\Http\Requests\IdRequest;
use Illuminate\Http\Request;
use App\Event;
use App\Participant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ParticipantsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function getParticipants(ParticipantsRequest $request)
    {
        if ($request->get('is_admin') == 1) {
            $select = DB::table('participants')
                ->where('event_id', '=', $request->get('id'));
        } else {
            $select = DB::table('participants')
                ->where('event_id', '=', $request->get('id'))
                ->where('visible', '=', 1);
        }

        $data = DB::table('users')
            ->joinSub($select, 'sel', function ($join) {
                $join->on('users.id', '=', 'sel.user_id');
            })->join('divisions', 'users.division', '=', 'divisions.id')
            ->select('sel.is_sure', 'users.id', 'users.name', 'users.surname', 'divisions.town', 'divisions.parish')
            ->get();

        return response()->json($data);
    }

    public function checkParticipant(ParticipantRequest $request){
        $data = Participant::where('event_id', '=',$request->get('event_id') )
        ->where('user_id', '=',$request->get('user_id') )
        ->select('visible', 'is_sure', 'want_messages')
        ->get();

        return response()->json($data);
    }

    public function newParticipant(ParticipantRequest $request)
    {
        $participant = new Participant();
        $participant->event_id = $request->get('event_id');
        $participant->user_id = $request->get('user_id');
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
        $data = [
            'event_id' => $request->get('event_id'),
            'user_id' => $request->get('user_id'),
            'visible' => $request->get('visible'),
            'is_sure' => $request->get('is_sure'),
            'want_messages' => $request->get('want_messages')
        ];

        Participant::where([
            'event_id' => $request->get('event_id'),
            'user_id' => $request->get('user_id'),
        ])->update($data);

        return response()->json([
            'success' => true
        ]);
    }

    public function deleteParticipant(ParticipantRequest $request)
    {
        Participant::where([
            'event_id' => $request->get('event_id'),
            'user_id' => $request->get('user_id'),
        ])->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
