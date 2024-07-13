<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Event_Stage;
use App\Models\Event_Set;
use App\Models\Venue;

class EventController extends Controller
{
    public function getEventList($pagenum = 0){
        $count = Event::count();
        $events = Event::where('state', '!=', 'deleted')
            ->orderBy('name')
            ->skip($pagenum*10)
            ->take(10)
            ->get();

            //TODO: # of pages

        return Inertia::render('Event/List', [
            'events' => $events,
        ]);
    }

    public function getEvent($id){
        $event = DB::table('event')
            ->join('event_stage', 'event.id', '=', 'event_stage.event_id')
            ->join('event_set', 'event.id', '=', 'event_set.event_id')
            ->select('event.*', 'event_stage.*', '.*')
            ->where('event.id', $id)
            ->get();

        return Inertia::render('Event/Show', [
            'event' => $event,
        ]);
    }

    public function createEvent(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'venue_id' => ['required', 'numeric']
        ]);
    }

    public function updateEvent(Request $request){
        //
    }

    public function destroyEvent($id){
        $event = Event::find($id);
        $event->state = $request->status;
        $event->save();
    }
}
