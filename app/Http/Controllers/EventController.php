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

        $event = Event::create([
            'tenant_id' => 0,
            'name' => $request->name,
            'venue_id' => $request->venue_id,
            'start' => $request->start,
            'end' => $request->end,
            'description' => $request->description,
            'ticketing_provider' => $request->ticketing_provider,
            'free' => $request->free,
            'all_ages' => $request->all_ages,
            'pic_url' => $request->pic_url,
            'ticket_url' => $request->ticket_url,
            'location' => $request->location,
            'state' => 'active'
        ]);

        $event_stage = Event_Stage::create([
            'tenant_id' => 0,
            'event_id' => $event->id,
            'venue_id' => $request->venue_id,
            'name' => 'Main Stage',
            'state' => 'active',
        ]);
    }

    public function addSet(Request $request, $event, $stage){
        $set = Event_Set::create([
            'tenant_id' => 0,
            'event_id' => $event->id,
            'venue_id' => $request->venue_id,
            'event_stage_id' => $request->stage_id,
            'artist_id' => $request->artist_id,
            'name' => $request->name,
            'time' => $request->time,
            'duration' => $request->duration,
        ]);
    }

    public function removeSet($id){
        $set = Event_Set::find($id);
        $set->delete();
    }

    public function updateEvent(Request $request, $id){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'venue_id' => ['required', 'numeric']
        ]);

        $event = Event::find($id);
        if ($event->venue_id !== $request->venue_id) {
            $stage = Event_Stage::where('event_id', $id)->first(); //TODO: Multi-stage needs to go through each stage and reset the venue id
            $stage->venue_id = $request->venue_id;
            $stage->save();
        }

        $event->name = $request->name;
        $event->venue_id = $request->venue_id;
        $event->start = $request->start;
        $event->end = $request->end;
        $event->description = $request->description;
        $event->ticketing_provider = $request->ticketing_provider;
        $event->free = $request->free;
        $event->all_ages = $request->all_ages;
        $event->pic_url = $request->pic_url;
        $event->ticket_url = $request->ticket_url;
        $event->location = $request->location;
        $event->state = $request->status;
    }

    public function destroyEvent($id){
        $event = Event::find($id);
        $event->state = $request->status;
        $event->save();
    }
}