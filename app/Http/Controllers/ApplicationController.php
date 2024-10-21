<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Event_Application;
use App\Models\Event_Application_Field;
use App\Models\Event_Application_Entry;
use App\Models\Event_Application_Parent;
use App\Models\User;

class ApplicationController extends Controller
{
    public function showCreateApplication($id){
        $event = Event::find($id);

        return Inertia::render('Apply/Create', [
            'event' => $event,
        ]);
    }


    public function createOrUpdateApplication(Request $request, $id) {
        $event = Event::find($id);

        // expected_value key:
        // string, longText, enum[], img[]
        // dropdown format: enum[<option>, <option>, ...]
        // img format:      img[<path>]

        $start = new Carbon($request->start);
        $end = new Carbon($request->end);

        $form = Event_Application::Create([
            'tenant_id' => 1,
            'event_id' => $id,
            'name' =>  $request->name,
            'description' =>  $request->desc,
            'open' =>  $start->toDateTimeString(),
            'close' =>  $end->toDateTimeString(),
            'type' =>  $request->type,
            'published' =>  1,
            'state' =>  'open',
        ]);

        foreach($request->entries as $field){
            $mandatory = false;
            $name = 'image';
            $mapped = null;
            $desc = '';

            if (isset($field['entryName'])){
                $name = $field['entryName'];
            }

            if (isset($field['entryMandatory'])){
                $mandatory = $field['entryMandatory'];
            }

            if (isset($field['entryMappedField'])){
                $mapped = $field['entryMappedField'];
            }

            if (isset($field['entryDescription'])){
                $desc = $field['entryDescription'];
            }

            if ($field['entryType'] === 'image') {
                $photo = $field['entryImage']->storePublicly(
                    'form-images', ['disk' => 'public']
                );
                $expected_value = "img[../storage/" . $photo . "]";
            } elseif ($field['entryType'] === 'dropdown') {
                $expected_value = "enum[";
                foreach($field['entryOptions'] as $option) {
                    $expected_value = $expected_value . $option . ", ";
                }
                $expected_value = rtrim($expected_value, ", ") . "]";
            } elseif ($field['entryType'] === 'text') {
                $expected_value = 'string';
            }else {
                $expected_value = 'longText';
            }

            Event_Application_Field::Create([
                'tenant_id' => 1,
                'event_id' => $id,
                'event_application_id' => $form->id,
                'order_id' => $field['id'],
                'name' => $name,
                'description' => $desc,
                'expected_value' => $expected_value,
                'mapped_value' => $mapped,
                'mandatory' => $mandatory,
            ]);
        }
    }

    public function deleteApplication($id) {
        $deleted = Event_Application_Entry::where('event_application_id', $id)->delete();
        $deleted = Event_Application_Field::where('event_application_id', $id)->delete();
        $deleted = Event_Application::find($id);
        $event = $deleted->event_id;
        $deleted->delete();

        return redirect()->route("event", $event)->with('success', 'Deleted');
    }

    public function showApplicationForm($name, $type = 'artist'){
        $application = Event_Application::where('tenant_id', 1)
            ->where('name', $name)
            ->where('type', $type)
            ->first();

        $fields = Event_Application_Field::where('event_application_id', $application->id)
            ->where('tenant_id', 1)
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

    public function showApplications($id, $pageNum = 0, $sortBy = 'name', $filter = 'none') {
        $applications = [];

        $rawApplications = Event_Application_Parent::where('tenant_id', 1)
            ->where('application_id', $id)
            ->skip($pageNum*10)
            ->take(10)
            ->get();

        foreach($rawApplications as $rawApplication) {
            $apps = Event_Application_Entry::where('tenant_id', 1)
            ->where('event_application_parent_id', $rawApplication->id)
            ->get();

            $artist = Artist::where('id', $apps[0]->artist_id)->first();

            $applications[$rawApplication->id]['application_id'] = $rawApplication->id;
            $applications[$rawApplication->id]['rating'] = $artist->rating;
            $applications[$rawApplication->id]['new'] = $rawApplication->new;
            $applications[$rawApplication->id]['shortlisted'] = $rawApplication->shortlisted;
            $applications[$rawApplication->id]['accepted'] = $rawApplication->accepted;
            $applications[$rawApplication->id]['rejected'] = $rawApplication->rejected;
            $applications[$rawApplication->id]['reason'] = $rawApplication->reason;


            foreach($apps as $application) {
                $field = Event_Application_Field::find($application->event_application_field_id);

                $name = $field->name;
                if ($field->mapped_value) {
                    $name = $field->mapped_value;
                }

                $applications[$rawApplication->id][$name] = $application->value;
            }

            Log::debug('Built application entry: {application}', ['application' => $applications[$rawApplication->id]]);
        }

        $count = Event_Application_Parent::where('tenant_id', 1)
            ->where('application_id', $id)
            ->count();

        return Inertia::render('Event/ApplicationList', [
            'applications' => $applications,
            'count' => $count,
        ]);
    }

    public function publishApplication($id) {
        $form = Event_Application::find($id);
        $form->published = 1;
        $form->save();

        return redirect()->route("event", $form->event_id)->with('success', 'Published');
    }

    public function unpublishApplication($id) {
        $form = Event_Application::find($id);
        $form->published = 0;
        $form->save();

        return redirect()->route("event", $form->event_id)->with('success', 'Unpublished');
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

        // Only assume absolute mandatory fields have been set
        $bio = isset($artistKeys['bio'])?               $artistKeys['bio']    : '';
        $genre = isset($artistKeys['genre'])?           $artistKeys['genre']    : '';
        $location = isset($artistKeys['location'])?     $artistKeys['location']    : '';
        $rider = isset($artistKeys['standard_rider'])?  $artistKeys['standard_rider']    : '';
        $tech = isset($artistKeys['tech_specs'])?       $artistKeys['tech_specs']    : '';
        $epk = isset($artistKeys['epk_url'])?           $artistKeys['epk_url']    : '';

        // if we don't have an artist entry in the DB, be sure to create one
        if ($artist === 0) {
            $artist = Artist::Create([
                'tenant_id' => 1,
                'name' => $artistKeys['name'],
                'email' => $artistKeys['email'],
                'bio' => $bio,
                'genre' => $genre,
                'location' => $location,
                'standard_rider' => $rider,
                'tech_specs' => $tech,
                'epk_url' => $epk,
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

        $parent = Event_Application_Parent::create([
            'tenant_id' => 1,
            'application_id' => $application->id,
            'new' => 1,
        ]);

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
                    'event_application_parent_id' => $parent->id,
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

        return Inertia::render('Apply/Success');
    }

    public function shortlist($id) {
        $application = Event_Application_Parent::where('tenant_id', 1)
            ->where('id', $id)
            ->first();

        Log::debug('Toggling Shortlisted status for application: {id}', ['id' => $id]);

        // toggle shortlisted
        if ($application->shortlisted === 0) {
            $application->shortlisted = 1;
        } else {
            $application->shortlisted = 0;
        }
        $application->save();

        return redirect()->route("event.applications", $application->application_id)->with('success', 'Shortlisted');
    }

    public function accept($id) {
        $application = Event_Application_Parent::where('tenant_id', 1)
            ->where('id', $id)
            ->first();

        Log::debug('Toggling Accepted status for application: {id}', ['id' => $id]);

        // toggle accepted
        if ($application->accepted === 0) {
            $application->accepted = 1;
        } else {
            $application->accepted = 0;
        }
        $application->save();

        return redirect()->route("event.applications", $application->application_id)->with('success', 'Accepted');
    }

    public function reject(Request $request, $id) {
        $application = Event_Application_Parent::where('tenant_id', 1)
            ->where('id', $id)
            ->first();

        Log::debug('Toggling Accepted status for application: {id}', ['id' => $id]);

        // toggle accepted
        if ($application->rejected === 0) {
            $application->rejected = 1;
            $application->reason = $request->reason;
        } else {
            $application->rejected = 0;
        }
        $application->save();

        return redirect()->route("event.applications", $application->application_id)->with('success', 'Rejected');
    }

    public function seen($id) {
        $application = Event_Application_Parent::where('tenant_id', 1)
            ->where('id', $id)
            ->first();

        Log::debug('Removing New status for application: {id}', ['id' => $id]);

        $application->new = 0;
        $application->save();

        return redirect()->route("event.applications", $application->application_id)->with('success', '');
    }
}
