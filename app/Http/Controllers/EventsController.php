<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\EventRequest;
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

    public function getDivisionEvents(IdRequest $request)
    {
        if ($request->get('id') == 0) {
            $data = Event::whereNull('division')
                ->orderby('start', 'desc')
                ->select('id', 'division', 'title', 'about', 'start', 'end', 'location');
                // ->get();
        } else {
            $data = Event::where('division', '=', $request->get('id'))
                ->orderby('start', 'desc')
                ->select('id', 'division', 'title', 'about', 'start', 'end', 'location');
                // ->get();
        }

        $select1 = DB::table('participants')
            ->where('user_id', '=', $request->get('user_id'));
        $select = $data
            ->leftJoinSub($select1, 'participants', function ($join) {
                $join->on('events.id', '=', 'participants.event_id');
            })
            ->selectRaw('events.id, events.division, events.title, events.about, events.start, events.end, events.location, participants.is_sure')
            ->get();

        return response()->json($select);
    }


    public function getUserUpcomingEvents(IdRequest $request)
    {

        $select = DB::table('participants')
            ->where('user_id', '=', $request->get('id'));


        $data = DB::table('events')
            ->joinSub($select, 'sel', function ($join) {
                $join->on('events.id', '=', 'sel.event_id');
            })->selectRaw('events.id, events.division, events.title, events.about, events.start, events.end, events.location')
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
            ->joinSub($select, 'sel', function ($join) {
                $join->on('events.id', '=', 'sel.event_id');
            })->selectRaw('events.id, events.division, events.title, events.about, events.start, events.end, events.location')
            ->where('end', '<', date('Y-m-d'))
            ->orderByRaw('events.start')
            ->get();

        return response()->json($data);
    }

    public function getEventInfo(IdRequest $request)
    {

        $select = DB::table('participants')
            ->where('event_id', '=', $request->get('id'));


        $data1 = DB::table('events')
            ->where('id', '=', $request->get('id'))
            ->leftJoinSub($select, 'sel', function ($join) {
                $join->on('events.id', '=', 'sel.event_id');
            })->selectRaw('events.id,  events.division, events.title, events.about, events.start, events.end, events.location, events.price, events.timetable, events.details, events.author, events.created_at, events.modified_at, count(sel.user_id) participants')
            ->orderByRaw('events.start')
            ->groupByRaw('events.id,  events.division, events.title, events.about, events.start, events.end, events.location, events.price, events.timetable, events.details, events.author, events.created_at, events.modified_at');

        $data = DB::table('divisions')
            ->rightJoinSub($data1, 'events', function ($join) {
                $join->on('events.division', '=', 'divisions.id');
            })->selectRaw('events.id,  events.division, events.title, events.about, events.start, events.end, events.location, events.price, events.timetable, events.details, events.author, events.created_at, events.modified_at, events.participants, divisions.email')
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
                ->where('visible', '=', 1);
        }

        $data = DB::table('users')
            ->joinSub($select, 'sel', function ($join) {
                $join->on('users.id', '=', 'sel.user_id');
            })->join('divisions', 'users.division', '=', 'divisions.id')
            ->select('sel.is_sure', 'users.name', 'users.surname', 'divisions.town', 'divisions.parish')
            ->get();

        return response()->json($data);
    }

    public function newEvent(EventRequest $request)
    {
        $event = new Event();
        if ($request->get('division') !== 0)
            $event->division = $request->get('division');
        $event->title = $request->get('title');
        $event->about = $request->get('about');
        $event->start = $request->get('start');
        $event->end = $request->get('end');
        $event->details = $request->get('details');
        $event->location = $request->get('location');
        $event->price = $request->get('price');
        $event->timetable = $request->get('timetable');
        $event->created_at = date("Y-m-d H:i:s");
        $event->author = $request->get('author');
        $event->save();

        return response()->json([

            'success' => true
        ]);
    }

    public function editEvent(EventRequest $request)
    {
        $event = Event::find($request->get('id'));
        if ($request->get('division') != 0)
            $event->division = $request->get('division');
        else
            $event->division = null;
        $event->title = $request->get('title');
        $event->about = $request->get('about');
        $event->start = $request->get('start');
        $event->end = $request->get('end');
        $event->details = $request->get('details');
        $event->location = $request->get('location');
        $event->price = $request->get('price');
        $event->timetable = $request->get('timetable');
        $event->modified_at = date("Y-m-d H:i:s");;
        $event->save();

        return response()->json([

            'success' => true
        ]);
    }

    public function deleteEvent(IdRequest $request)
    {
        $data = Event::find($request->get('id'));
        $data->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
