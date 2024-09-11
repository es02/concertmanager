<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Event_Stage;
use App\Models\Event_Set;
use App\Models\Venue;
use App\Models\Event_Application;
use App\Models\Event_Application_Field;
use App\Models\Event_Application_Entry;
use App\Models\User;

class EventController extends Controller
{
    public function getEventList($pagenum = 0){
        $count = Event::count();
        $events = Event::where('state', '!=', 'deleted')
            ->orderBy('name')
            ->skip($pagenum*10)
            ->take(10)
            ->get();

        return Inertia::render('Event/List', [
            'events' => $events,
            'count' => $count,
        ]);
    }

    public function getEvent($id){
        $event = DB::table('event')
            ->join('venue', 'event.venue_id', '=', 'venue.id')
            ->select('event.*', 'venue.*')
            ->where('event.tenant_id', 1)
            ->where('event.id', $id)
            ->first();

        $sets = DB::table('event_set')
            ->join('event_stage', 'event_set.event_id', '=', 'event_stage.event_id')
            ->select('event_stage.*', 'event_set.*')
            ->where('event_stage.tenant_id', 1)
            ->where('event_set.id', $id)
            ->get();

        $stages = DB::table('event_stage')
            ->join('venue', 'event_stage.venue_id', '=', 'venue.id')
            ->select('event_stage.*', 'venue.*')
            ->where('event_stage.tenant_id', 1)
            ->where('event_stage.event_id', $id)
            ->get();

        $forms = Event_Application::where('tenant_id', 1)
            ->where('event_id', $id)
            ->get();

        foreach($forms as &$form) {
            $fields =  Event_Application_Field::where('tenant_id', 1)
                ->where('event_application_id', $form->id)
                ->count();
            $applications = Event_Application_Entry::where('tenant_id', 1)
                ->where('event_application_id', $form->id)
                ->count();

            Log::debug('Form: {id} :: {form}, Form Fields: {fields}, Application Records: {records}', ['id' => $form->id, 'form' => $form->name, 'fields' => $fields, 'records' => $applications]);

            // Divide total applications by number of entry fields
            $count = 0;
            if ($fields > 0){
                $count = $applications / $fields;
            }
            $form['application_count'] = $count;
        }

        return Inertia::render('Event/Show', [
            'event' => $event,
            'stages' => $stages,
            'sets' => $sets,
            'forms' => $forms,
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
            'event_name' => $request->name,
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
            'stage_name' => 'Main Stage',
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
            'artist_name' => $request->name,
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
            $stages = Event_Stage::where('event_id', $id)->get();

            // TODO: Allow stages to run between multiple venues
            foreach($stages as $stage){
                $stage->venue_id = $request->venue_id;
                $stage->save();
            }
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

        $event->save();
    }

    public function destroyEvent($id){
        $event = Event::find($id);
        $event->state = $request->status;
        $event->save();
    }
}
