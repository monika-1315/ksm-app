<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ParticipantsRequest;
use App\Http\Requests\IdRequest;
use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function getUpcomingEvents(Request $request)
    {
        $data = Event::where('end', '>=', date('Y-m-d'))
            ->orderby('start', 'desc')
            ->get();
        return response()->json($data);
    }

    public function getOldEvents(Request $request)
    {
        $data = Event::where('end', '<', date('Y-m-d'))
            ->orderby('start', 'asc')
            ->get();
        return response()->json($data);
    }

    public function getUserEvents(IdRequest $request)
    {
        $data = Event::where('author', '=', $request->get('id'))
            ->orderby('start', 'desc')
            ->get();
        return response()->json($data);
    }

    public function getUserUpcomingEvents(IdRequest $request)
    {

        $select = DB::table('participants')
            ->where('user_id', '=', $request->get('id'));


        $data = DB::table('events')
            ->leftJoinSub($select, 'sel', function ($join) {
                $join->on('events.id', '=', 'sel.event_id');
            })->selectRaw('events.id, events.receiver_group, events.division, events.title, events.about, events.start, events.end, events.location')
            ->where('end', '>=', date('Y-m-d'))
            ->orderByRaw('events.start')
            ->get();

        return response()->json($data);
    }

    public function getUserOldEvents(IdRequest $request)
    {

        $select = DB::table('participants')
            ->where('user_id', '=', $request->get('id'));


        $data = DB::table('events')
            ->leftJoinSub($select, 'sel', function ($join) {
                $join->on('events.id', '=', 'sel.event_id');
            })->selectRaw('events.id, events.receiver_group, events.division, events.title, events.about, events.start, events.end, events.location')
            ->where('end', '<', date('Y-m-d'))
            ->orderByRaw('events.start')
            ->get();

        return response()->json($data);
    }

    public function getEventInfo(IdRequest $request)
    {

        $select = DB::table('participants')
            ->where('event_id', '=', $request->get('id'));


        $data = DB::table('events')
            ->leftJoinSub($select, 'sel', function ($join) {
                $join->on('events.id', '=', 'sel.event_id');
            })->selectRaw('events.id, events.receiver_group, events.division, events.title, events.about, events.start, events.end, events.location, events.price, events.timetable, events.details, events.author, events.created_at, events.modified_at, count(sel.user_id) participants')
            ->orderByRaw('events.start')
            ->groupByRaw('events.id, events.receiver_group, events.division, events.title, events.about, events.start, events.end, events.location, events.price, events.timetable, events.details, events.author, events.created_at, events.modified_at')
            ->get();

        return response()->json($data);
    }

    public function getParticipants(ParticipantsRequest $request)
    {
        if ($request->get('is_admin') == 1) {
            $select = DB::table('participants')
                ->where('event_id', '=', $request->get('id'));
        } else {
            $select = DB::table('participants')
                ->where('event_id', '=', $request->get('id'))
                ->where('visible', '=',1);
        }

        $data = DB::table('users')
            ->joinSub($select, 'sel', function ($join) {
                $join->on('users.id', '=', 'sel.user_id');
            }) ->join('divisions', 'users.division', '=', 'divisions.id')
            ->select('sel.is_sure', 'users.name', 'users.surname', 'divisions.town', 'divisions.parish')
            ->get();

        return response()->json($data);
    }
}
