<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Event_Application;
use App\Models\Event_Application_Field;
use App\Models\Event_Application_Entry;
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

        return Inertia::render('Apply/Success');
    }
}
