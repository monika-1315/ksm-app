<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Requests\IdRequest;
use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class EventsController extends Controller
{

    public function getUpcomingEvents()
    {
        $data = Event::where('end', '>=', date('Y-m-d'))
            ->orderby('start', 'desc')
            ->get();
        return response()->json($data);
    }

    public function getOldEvents()
    {
        $data = Event::where('end', '<', date('Y-m-d'))
            ->orderby('start', 'asc')
            ->get();
        return response()->json($data);
    }

    public function getUserEvents(Request $request)
    {
        $user = JWTAuth::toUser($request->only('token'));
        $data = Event::where('author', '=', $user->id)
            ->orderby('start', 'desc')
            ->get();
        return response()->json($data);
    }

    public function getDivisionEvents(IdRequest $request)
    {
        if ($request->get('id') == 0) {
            $data = Event::whereNull('division')
                ->where('end', '>=', date('Y-m-d'))
                ->select('id', 'division', 'title', 'about', 'start', 'end', 'location', 'author');
        } else {
            $data = Event::where('division', '=', $request->get('id'))
                ->where('end', '>=', date('Y-m-d'))
                ->select('id', 'division', 'title', 'about', 'start', 'end', 'location', 'author');
        }

        $select1 = DB::table('participants')
            ->where('user_id', '=', $request->get('user_id'));
        $select = $data
            ->leftJoinSub($select1, 'participants', function ($join) {
                $join->on('events.id', '=', 'participants.event_id');
            })
            ->selectRaw('events.id, events.division, events.title, events.about, events.start, events.end, events.location, participants.is_sure, events.author')
            ->orderby('start', 'asc')
            ->get();

        return response()->json($select);
    }


    public function getUserUpcomingEvents(Request $request)
    {
        $user = JWTAuth::toUser($request->only('token'));
        $select = DB::table('participants')
            ->where('user_id', '=', $user->id);


        $data = DB::table('events')
            ->rightJoinSub($select, 'sel', function ($join) {
                $join->on('events.id', '=', 'sel.event_id');
            })->selectRaw('events.id, events.division, events.title, events.about, events.start, events.end, events.location, sel.is_sure, events.author')
            ->where('end', '>=', date('Y-m-d'))
            ->orderBy('start', 'asc')
            ->get();

        return response()->json($data);
    }

    public function getUserOldEvents(Request $request)
    {
        $user = JWTAuth::toUser($request->only('token'));
        $select = DB::table('participants')
            ->where('user_id', '=', $user->id);


        $data = DB::table('events')
            ->joinSub($select, 'sel', function ($join) {
                $join->on('events.id', '=', 'sel.event_id');
            })->selectRaw('events.id, events.division, events.title, events.about, events.start, events.end, events.location, events.author')
            ->where('end', '<', date('Y-m-d'))
            ->orderBy('start', 'desc')
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
            })->selectRaw('events.id eventsid,  events.division aim_division, events.title, events.about, events.start, events.end, events.location, events.price, events.timetable, events.details, events.author, events.created_at, events.modified_at, count(sel.user_id) participants')
            ->orderByRaw('events.start')
            ->groupByRaw('events.id,  events.division, events.title, events.about, events.start, events.end, events.location, events.price, events.timetable, events.details, events.author, events.created_at, events.modified_at');

        $author = DB::table('users')
            ->JoinSub($data1, 'sel', function ($join) {
                $join->on('users.id', '=', 'sel.author');
            })->selectRaw('sel.eventsid event_id2,users.id, users.name, users.surname ');
        $data = DB::table('divisions')
            ->rightJoinSub($data1, 'events', function ($join) {
                $join->on('events.aim_division', '=', 'divisions.id');
            })
            ->JoinSub($author, 'author', function ($join) {
                $join->on('author.event_id2', '=', 'events.eventsid');
            })
            ->selectRaw('events.eventsid id,  events.aim_division division, events.title, author.id author_id, author.name,author.surname, events.about, events.start, events.end, events.location, events.price, events.timetable, events.details, events.created_at, events.modified_at, events.participants, divisions.email')
            ->get();

        return response()->json($data);
    }



    public function newEvent(EventRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->only('token'));
        if ($logged_user->is_authorized === 0 || ($logged_user->is_leadership === 0 && $logged_user->is_management === 0))
            return response()->json([
                'success' => false
            ]);

        $event = new Event();
        if ($request->get('division') != 0)
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

        $recipients = UsersController::getRecipients($request->get('division'), null);
        foreach ($recipients as $p) {
            Mail::send(
                $p->email,
                'Nowe wydarzenie w kalendarium KSM DL',
                'Witaj!<br>W aplikacji KSM DL właśnie pojawiło się nowe wydarzenie, które może Cię zainteresować: <b>'
                    . $request->get('title')
                    . '</b>.<br>' . $request->get('about') . '<br>'
                    . $request->get('start') . ' - ' . $request->get('end')
                    . '<br> Zaloguj się do aplikacji, aby sprawdzić szczegóły i dołączyć do wydarzenia już dziś!'
            );
        }

        return response()->json([

            'success' => true
        ]);
    }

    public function editEvent(EventRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->only('token'));
        if ($logged_user->is_authorized === 0)
            return response()->json(['success' => false]);

        $event = Event::find($request->get('id'));
        if ($event->author !== $logged_user->id) {
            if ($event->division == null) {
                if ($logged_user->is_management === 0)
                    return response()->json(['success' => false, 'info' => 1]);
            } else if ($logged_user->is_leadership === 0 || $logged_user->division !== $event->division)
                return response()->json(['success' => false, 'info' => 12]);
        }

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
        $event->modified_at = date("Y-m-d H:i:s");
        $event->save();

        if ($request->emails == true) {
            $participants = ParticipantsController::getParticipantsForMails($request->get('id'));
            foreach ($participants as $p) {
                Mail::sendById(
                    $p->user_id,
                    'Wydarzenie zostało zmodyfikowane',
                    'Witaj!<br>Organizator wydarzenia <b>' . $request->get('title') . '</b> wprowadził w nim zmiany, które uznał za istotne. Zaloguj się do aplikacji, aby to sprawdzić!'
                );
            }
        }

        return response()->json([
            'success' => true
        ]);
    }


    public function deleteEvent(IdRequest $request)
    {
        $logged_user = JWTAuth::toUser($request->only('token'));
        if ($logged_user->is_authorized === 0)
            return response('', 401);

        $event = Event::find($request->get('id'));
        if ($event->author !== $logged_user->id) {
            if ($event->division == null) {
                if ($logged_user->is_management === 0)
                return response('', 401);
            } 
            else if ($logged_user->is_leadership === 0 || $logged_user->division !== $event->division)
                return response('', 401);
        }
        $event->delete();

        return response()->json([
            'success' => true
        ]);
    }
    public function getCollidingEvents(Request $request)
    {

        $events = Event::where('end', '>', $request->get('start'))
            ->where('end', '<', $request->get('end'))
            ->orWhere('start', '<', $request->get('end'))
            ->where('start', '>', $request->get('start'))
            ->orWhere('end', '>', $request->get('start'))
            ->where('start', '<', $request->get('start'))
            ->orWhere('end', '>', $request->get('end'))
            ->where('start', '<', $request->get('end'))
            ->orderBy('start', 'asc')
            ->select('id', 'division', 'title', 'start', 'end');

        $data = DB::table('divisions')
            ->rightJoinSub($events, 'events', function ($join) {
                $join->on('events.division', '=', 'divisions.id');
            })->selectRaw('events.id, divisions.town, divisions.parish, title, start, end')
            ->get();

        return response()->json($data);
    }
}
