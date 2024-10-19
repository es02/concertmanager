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

    public function showCreateEvent(){
        $venues = Venue::where('tenant_id', 1)
            ->get();

        return Inertia::render('Event/Create', [
            'venues' => $venues,
        ]);
    }

    public function createEvent(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'venue_id' => ['required', 'numeric']
        ]);

        $description = '';
        $provider = '';
        $free = false;
        $allAges = false;
        $pic = '';
        $ticketUrl = '';
        $location = '';

        if($request->description){$description = $request->description;}
        if($request->ticketing_provider){$provider = $request->ticketing_provider;}
        if($request->free){$free = $request->free;}
        if($request->all_ages){$allAges = $request->all_ages;}
        if($request->ticket_url){$ticketUrl = $request->ticket_url;}
        if($request->location){$location = $request->location;}
        if($request->pic_url){
            $pic = $request->pic_url->storePublicly(
                'event-images', ['disk' => 'public']
            );
            $pic = "../storage/" . $pic;
        }

        $event = Event::create([
            'tenant_id' => 1,
            'name' => $request->name,
            'venue_id' => $request->venue_id,
            'start' => $request->start,
            'end' => $request->end,
            'description' => $description,
            'ticketing_provider' => $provider,
            'free' => $free,
            'all_ages' => $allAges,
            'event_pic_url' => $pic,
            'ticket_url' => $ticketUrl,
            'location' => $location,
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

    public function exportCSV() {
        $filename = 'events.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "inline; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'X-Accel-Buffering' => 'no'
        ];
        Log::info('Exporting event CSV');

        $columns = [
            'Name *',
            'Venue ID *',
            'Start *',
            'End *',
            'Description',
            'Ticket Provider',
            'Is Free?',
            'Is All Ages?',
            'Ticket Link',
            'Location',
        ];

        $callback = function() use ($filename, $columns) {
            $handle = fopen('php://output', 'w');
            Log::debug('Exporting event CSV: {name}', ['name' => $filename]);

            fputcsv($handle, $columns);

             // Fetch and process data in chunks
            Event::chunk(25, function ($events) use ($handle) {
                foreach ($events as $event) {
                    Log::debug('Exporting event: {name}', ['name' => $event->name]);
                    // Extract data from each event.
                    $free = "No";
                    $aa = "No";
                    if ($event->free === 1) {
                        $free = "Yes";
                    }
                    if ($event->all_ages === 1) {
                        $aa = "Yes";
                    }

                    $data = [
                        isset($event->name)?                $event->name                    : '',
                        isset($event->venue_id)?            $event->venue_id                : '',
                        isset($event->start)?               $event->start                   : '',
                        isset($event->end)?                 $event->end                     : '',
                        isset($event->description)?         trim($event->description,'"')   : '',
                        isset($event->ticketing_provider)?  $event->ticketing_provider      : '',
                        $free,
                        $aa,
                        isset($event->ticket_url)?          $event->ticket_url              : '',
                        isset($event->location)?            $event->location                : '',
                    ];

                    Log::debug('Exporting data: {data}', ['data' => $data]);
                    Log::debug('Exporting handle: {handle}', ['handle' => $handle]);

                    // Write data to a CSV file.
                    fputcsv($handle, $data);
                }
            });
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers)->send();
    }

    public function importCSV(Request $request)
    {
        $request->validate([
            'import_csv' => 'required|mimes:csv',
        ]);
        //read csv file and skip data
        $file = $request->file('import_csv');
        $handle = fopen($file->path(), 'r');
        Log::debug('Importing event CSV');

        //skip the header row
        fgetcsv($handle);

        $chunksize = 25;
        while(!feof($handle))
        {
            $chunkdata = [];

            for($i = 0; $i<$chunksize; $i++)
            {
                $data = fgetcsv($handle);
                if($data === false)
                {
                    break;
                }
                $chunkdata[] = $data;
            }

            $this->getchunkdata($chunkdata);
        }
        fclose($handle);

        return redirect()->route('events')->with('success', 'Data has been added successfully.');
    }

    public function getchunkdata($chunkdata)
    {
        foreach($chunkdata as $column){
            Log::debug('Importing event: {name}', ['name' => $column[0]]);

            $eventCheck = Event::Where('tenant_id', 1)
                ->where('name', $column[0])
                ->count();

            Log::debug('Event records found: {count}', ['count' => $eventCheck]);

            if ($eventCheck !== 0){
                Log::debug('Event found - updating');
                $event = Event::Where('tenant_id', 1)
                ->where('name', $column[0])
                ->first();
            } else {
                Log::debug('Event not found - creating');
                $event = new Event();
                $event->tenant_id = 1;
            }

            $free = 0;
            if($column[6] === 'yes' || $column[6] === 'Yes') {
                $booked = 1;
            }
            $aa = 0;
            if($column[7] === 'yes' || $column[7] === 'Yes') {
                $booked = 1;
            }

            $event->name = $column[0];
            $event->venue_id = $column[1];
            $event->start = $column[2];
            $event->end = $column[3];
            $event->description = $column[4];
            $event->ticketing_provider = $column[5];
            $event->free = $free;
            $event->all_ages = $aa;
            $event->ticket_url = $column[8];
            $event->location = $column[9];
            $event->save();
        }
    }
}
