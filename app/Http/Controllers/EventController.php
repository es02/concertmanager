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
            ->where('event.id', $id)
            ->first();

        $sets = DB::table('event_set')
            ->join('event_stage', 'event_set.event_id', '=', 'event_stage.event_id')
            ->select('event_stage.*', 'event_set.*')
            ->where('event_set.id', $id)
            ->get();

        return Inertia::render('Event/Show', [
            'event' => $event,
            'sets' => $sets,
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

    public function showApplication($name, $type = 'artist'){
        $application = Event_Application::where('tenant_id', 1)
            ->where('name', $name)
            ->where('type', $type)
            ->first();

        $fields = Event_Application_Field::where('event_application_id', $application->id)
            ->orderBy('order_id', 'asc')
            ->get();

        foreach ($fields as &$field) {
            $tmp = str_replace(" ", "_", $field->name);
            $tmp = str_replace("(s)", "s", $tmp);
            $field->vmodel = $tmp;
        }

        $event = Event::find($application->event_id);

        return Inertia::render('Apply/Show', [
            'application' => $application,
            'fields' => $fields,
            'event' => $event,
        ]);
    }

    public function applyForEvent(Request $request, $name, $type = 'artist'){
        $application = Event_Application::where('tenant_id', 1)
            ->where('name', $name)
            ->where('type', $type)
            ->first();

        $fields = Event_Application_Field::where('event_application_id', $application->id)
            ->orderBy('order_id', 'asc')
            ->get();

        $artist = Artist::where('tenant_id', 1)
            ->where('email', $application->email)
            ->count();

        $artistKeys = [];

        foreach ($fields as $field) {
            $name = $field->name;

            $name = str_replace("(s)", "s", $name);
            $name = str_replace(" ", "_", $name);

            $value = $request->$name;

            Log::debug('Processing field {name} with value {value}', ['name' => $name, 'value' => $value]);

            if ($name === 'Header_Image') {
                continue;
            }

            if ($field->mapped_value !== '') {
                $artistKeys[$field->mapped_value] = $value;
            }
            if ($field->name === 'Location_other' && $field.value !== '') {
                $artistKeys['location'] = $value;
            }
        }

        // if we don't have an artist entry in the DB, be sure to create one
        if ($artist === 0) {
            $artist = Artist::Create([
                'tenant_id' => 1,
                'name' => $artistKeys['name'],
                'email' => $artistKeys['email'],
                'bio' => $artistKeys['bio'],
                'genre' => $artistKeys['genre'],
                'location' => $artistKeys['location'],
                'standard_rider' => $artistKeys['standard_rider'],
                'tech_specs' => $artistKeys['tech_specs'],
                'epk_url' => $artistKeys['epk_url'],
                'booked_previously' => false,
                'state' => 'active'
            ]);

            $user = User::where('tenant_id', 1)
            ->where('email', $artist->email)
            ->count();

            if ($user === 0) {
                User::create([
                    'tenant_id' => 1,
                    'name' => $artistKeys['name'],
                    'email' => $artistKeys['email'],
                    'password' => Hash::make(Str::random(40)), // new user will need to reset password to be able to log in
                ]);
            }

        }else{
            $artist = Artist::where('tenant_id', 1)
            ->where('email', $application->email)
            ->first();
        }

        $entryCount = Event_Application_Entry::where('tenant_id', 1)
            ->where('artist_id', $artist->id)
            ->count();

        if ($entryCount === 0) {
            foreach ($fields as $field) {
                $name = $field->name;

                $name = str_replace("(s)", "s", $name);
                $name = str_replace(" ", "_", $name);

                $value = $request->$name;

                Log::debug('Processing field {name} with value {value}', ['name' => $name, 'value' => $value]);

                if ($name === 'Header_Image') {
                    continue;
                }

                $applied = Event_Application_Entry::create([
                    'tenant_id' => 1,
                    'event_id' => $application->event_id,
                    'event_application_id' => $application->id,
                    'event_application_field_id' => $field->id,
                    'artist_id' => $artist->id,
                    'value' => $request->$name,
                ]);
            }
        } else {
            foreach ($fields as $field) {
                $name = $field->name;

                $name = str_replace("(s)", "s", $name);
                $name = str_replace(" ", "_", $name);

                $value = $request->$name;

                Log::debug('Processing field {name} with value {value}', ['name' => $name, 'value' => $value]);

                if ($name === 'Header_Image') {
                    continue;
                }

                $applied = Event_Application_Entry::where('tenant_id', 1)
                    ->where('artist_id', $artist->id)
                    ->where('event_id', $application->event_id)
                    ->where('event_application_id', $application->id)
                    ->where('event_application_field_id', $field->id)
                    ->first();

                $applied->value = $value;
                $applied->save();
            }
        }
    }
}
