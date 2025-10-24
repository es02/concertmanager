<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Http\Controllers\EmailController;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Event_Application;
use App\Models\Event_Application_Field;
use App\Models\Event_Application_Entry;
use App\Models\Event_Application_Parent;
use App\Models\User;

class ApplicationController extends Controller
{
    // Also called for updating a form
    public function showCreateApplication($id, $form = 0){
        $event = Event::find($id);
        $count = 0;
        $formData = null;

        Log::debug('Showing application form creation page for event {id}, form {form}', ['id' => $id, 'form' => $form]);

        if($form > 0) {
            Log::debug('Updating existing form');
            $formData = Event_Application::where('tenant_id', 1)
            ->where('id', $form)
            ->first();

            $fields = Event_Application_Field::where('event_application_id', $formData->id)
            ->where('tenant_id', 1)
            ->orderBy('order_id', 'asc');

            $count = $fields->count();
            $fields = $fields->get();
            $entries = [];

            foreach ($fields as &$field) {
                $entry = [];
                $tmp = str_replace(" ", "_", $field->name);
                $tmp = str_replace("(s)", "s", $tmp);
                $field->vmodel = $tmp;
                $type = 'longText';
                $entry['entryOptions'] = [];
                $entry['entryImage'] = '';

                if (substr($field->expected_value, 0, 3) === 'img') {
                    $type = 'image';
                    $entry['entryImage'] = substr($field->expected_value, 4, -1);
                } elseif (substr($field->expected_value, 0, 4) === 'enum') {
                    $type = 'dropdown';
                    $entry['entryOptions'] = explode(',', substr($field->expected_value, 5, -1));
                } elseif ($field->expected_value === 'string') {
                    $type = 'text';
                }

                Log::debug('Adding form element {name}', ['name' => $field->name]);

                $entry['entryType'] = $type;
                $entry['entryName'] = $field->name;
                $entry['entryDescription'] = $field->description;
                $entry['entryMappedField'] = $field->mapped_value;
                $entry['entryMandatory'] = $field->mandatory;
                $entry['hasFocus'] = false;
                $entries[] = $entry;
            }
            $formData['entries'] = $entries;
        }

        return Inertia::render('Apply/Create', [
            'event' => $event,
            'fieldCount' => $count,
            'appForm' => $formData,
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

        $formCheck = Event_Application::where('tenant_id', 1)
            ->where('event_id', $id)
            ->where('name', $request->name)
            ->count();

        if ($formCheck > 0) {
            // There should never be 2 forms for an event with the same name
            // so we can safely just pull the first record
            $form = Event_Application::where('tenant_id', 1)
            ->where('event_id', $id)
            ->where('name', $request->name)
            ->first();

            // Update form values
            $form->name = $request->name;
            $form->description =  $request->desc;
            $form->open =  $start->toDateTimeString();
            $form->close =  $end->toDateTimeString();
            $form->type =  $request->type;
            $form->published =  1;
            $form->state =  'open';
            $form->save();
        } else {
            $form = Event_Application::Create([
                'tenant_id' => 1,
                'event_id' => $id,
                'name' => $request->name,
                'description' =>  $request->desc,
                'open' =>  $start->toDateTimeString(),
                'close' =>  $end->toDateTimeString(),
                'type' =>  $request->type,
                'published' =>  1,
                'state' =>  'open',
            ]);
        }

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

            if ($field['entryType'] === 'image' && !is_array($field['entryImage'])) {
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

            $entryCheck = Event_Application_Field::where('tenant_id', 1)
                ->where('event_id', $id)
                ->where('event_application_id', $form->id)
                ->where('name', $name)
                ->count();

            if($entryCheck > 0) {
                $field = Event_Application_Field::where('tenant_id', 1)
                    ->where('event_id', $id)
                    ->where('event_application_id', $form->id)
                    ->where('name', $name)
                    ->first();
                    if ($field['entryType'] === 'image' && is_array($field['entryImage'])) {
                        $expected_value = $field->expected_value;
                    }

                    $field->order_id = $field['id'];
                    $field->description = $desc;
                    $field->expected_value = $expected_value;
                    $field->mapped_value = $mapped;
                    $field->mandatory = $mandatory;
                    $field->save();
            } else {
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
    }

    public function deleteApplication($id) {
        $deleted = Event_Application_Entry::where('event_application_id', $id)->delete();
        $deleted = Event_Application_Parent::where('application_id', $id)->delete();
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

        $now = new Carbon();
        $close = new Carbon($application->close);

        if($close <= $now) {
            $application->state = 'closed';
            $application->save();
        }

        $god = false;
        $user =  Auth::user();
        if ($user && $user->hasRole('god')) {$god = true;}

        // Allow admin users to view unpublished forms
        if ($application->published == 0 & $god == false) {
            abort(404);
        }

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

    public function showApplications($id, $a = 'none', $b = 'id', $c = 0,) {
        $applications = [];
        $sortby = 'id';
        $filter = 'none';
        $pagenum = 0;

        if (is_numeric($a)) {
            // $pagenum = $a;
        } elseif (is_numeric($b)) {
            // $pagenum = $b;

            if ($a === 'name' || $a === 'location' || $a === 'genre' || $a === 'rating' || $a === 'status') {
                $sortby = $a;
            } else {
                if(isset($a) && $a !== 'undefined'){$filter = $a;}
            }
        } else {
            // $pagenum = $c;
            if(isset($b) && $b !== 'undefined'){$sortby = $b;}
            if(isset($a) && $a !== 'undefined'){$filter = $a;}
        }

        Log::debug('Generating event application list for Application Form: {id}, sorted by: {sort}, filtered by: {filter}, page: {page}', ['id' => $id, 'sort' => $sortby, 'filter' => $filter, 'page' => $pagenum]);

        // don't skip ahead a page
        // if ($pagenum !== 0) {
        //     $pagenum--;
        // }

        if($filter !== 'none') {
            $rawApplications = Event_Application_Parent::where('tenant_id', 1)
                ->where('application_id', $id)
                ->where($filter, 1)
                // ->skip($pagenum * 10)
                // ->take(10)
                ->get();

            $count = Event_Application_Parent::where('tenant_id', 1)
                ->where('application_id', $id)
                ->where($filter, 1)
                ->count();
        } else {
            $rawApplications = Event_Application_Parent::where('tenant_id', 1)
                ->where('application_id', $id)
            //     ->skip($pagenum * 10)
            //     ->take(10)
                ->get();

            $count = Event_Application_Parent::where('tenant_id', 1)
                ->where('application_id', $id)
                ->count();
        }

        foreach($rawApplications as $rawApplication) {
            Log::debug('Getting details for Application {id}', ['id' => $rawApplication->id]);

            $check = Event_Application_Entry::where('tenant_id', 1)
                ->where('event_application_parent_id', $rawApplication->id)
                ->count();

            if($check === 0) {
                continue;
            }

            $apps = Event_Application_Entry::where('tenant_id', 1)
            ->where('event_application_parent_id', $rawApplication->id)
            ->get();

            $rating = 0;

            $artist = Artist::where('id', $apps[0]->artist_id)->first();
            if(isset($artist->rating)){
                $rating = $artist->rating;
            }

            $applications[$rawApplication->id]['application_id'] = $rawApplication->id;
            $applications[$rawApplication->id]['rating'] = $rating;
            $applications[$rawApplication->id]['new'] = $rawApplication->new;
            $applications[$rawApplication->id]['shortlisted'] = $rawApplication->shortlisted;
            $applications[$rawApplication->id]['accepted'] = $rawApplication->accepted;
            $applications[$rawApplication->id]['rejected'] = $rawApplication->rejected;
            $applications[$rawApplication->id]['reason'] = $rawApplication->reason;
            $applications[$rawApplication->id]['artist'] = $artist->id;
            $applications[$rawApplication->id]['blacklisted'] = $artist->blacklisted;


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

        if ($sortby !== 'id') {
            $sort = SORT_ASC;
            if ($sortby === 'rating' || $sortby === 'status') {
                $sort = SORT_DESC;
            }

            $name = array_column($applications, 'name');
            $location = array_column($applications, 'location');
            $genre = array_column($applications, 'genre');
            $rating = array_column($applications, 'rating');
            $new = array_column($applications, 'new');
            $accepted = array_column($applications, 'accepted');
            $shortlisted = array_column($applications, 'shortlisted');
            $rejected = array_column($applications, 'rejected');

            if ($sortby === 'status') {
                array_multisort($new, $sort, $accepted, $sort, $shortlisted, $sort, $rejected, $sort, $applications);
            } else {
                array_multisort($$sortby, $sort, $applications);
            }
        }

        Log::debug('Built sorted application list: {application}', ['application' => $applications]);

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

        $artistKeys = [];

        foreach ($fields as $field) {
            $name = $field->name;

            $name = str_replace("(s)", "s", $name);
            $name = str_replace(" ", "_", $name);

            $value = $request->$name;

            // Someone managed to get an empty form response in.
            // This bricks the application list so make sure mandatory fields are validated server-side as well as client-side.
            if ($field->mandatory == 1 && $value == '') {
                return back()->with('error', 'missing field');
            }

            Log::debug('Processing field {name} with value {value}', ['name' => $name, 'value' => $value]);

            if (substr($field->expected_value, 0, 3) === 'img') {
                continue;
            }

            if ($field->mapped_value !== '') {
                $artistKeys[$field->mapped_value] = $value;
            }
            if ($field->name === 'Location_other' && $field.value !== '') {
                $artistKeys['location'] = $value;
            }

            $items[$field->name] = $value;
        }

        // Only assume absolute mandatory fields have been set
        $bio = isset($artistKeys['bio'])?               $artistKeys['bio']              : '';
        $genre = isset($artistKeys['genre'])?           $artistKeys['genre']            : '';
        $location = isset($artistKeys['location'])?     $artistKeys['location']         : '';
        $rider = isset($artistKeys['standard_rider'])?  $artistKeys['standard_rider']   : '';
        $tech = isset($artistKeys['tech_specs'])?       $artistKeys['tech_specs']       : '';
        $epk = isset($artistKeys['epk_url'])?           $artistKeys['epk_url']          : '';
        $fee = isset($artistKeys['standard_fee'])?      $artistKeys['standard_fee']     : '';

        // Use name rather than email as booking agents/managers use the same email for multiple acts
        $artist = Artist::where('tenant_id', 1)
            ->where('name', $artistKeys['name'])
            ->count();

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
                'standard_fee' => $fee,
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
            ->where('name', $artistKeys['name'])
            ->first();
        }

        $entryCount = Event_Application_Entry::where('tenant_id', 1)
            ->where('artist_id', $artist->id)
            ->where('event_application_id', $application->id) // Ensure we're only checking for applications to this form
            ->count();

        $email = new EmailController;
        $event = Event::find($application->event_id);

        $to = $artist->email;
        $template = 'event_success';         // TODO: Set correct template name from DB
        $data = [
            'name' => $artist->name,
            'event' => $event->name,
            'applicationType' => $type,
            'items' => $items,
        ];

        if ($entryCount === 0) {

            // Only create a new parent entry if there is not an existing entry
            $parent = Event_Application_Parent::create([
                'tenant_id' => 1,
                'application_id' => $application->id,
                'new' => 1,
            ]);

            foreach ($fields as $field) {
                $name = $field->name;

                $name = str_replace("(s)", "s", $name);
                $name = str_replace(" ", "_", $name);

                $value = $request->$name;

                Log::debug('Processing field {name} with value {value}', ['name' => $name, 'value' => $value]);

                if (substr($field->expected_value, 0, 3) === 'img') {
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
                if ($value === null) {
                    $value = "";
                }

                Log::debug('Processing field {name} with value {value} id {id}', ['name' => $name, 'value' => $value, 'id' => $field->id]);

                if (substr($field->expected_value, 0, 3) === 'img') {
                    continue;
                }

                $applied = Event_Application_Entry::where('tenant_id', 1)
                    ->where('artist_id', $artist->id)
                    ->where('event_id', $application->event_id)
                    ->where('event_application_id', $application->id)
                    ->where('event_application_field_id', $field->id)    // FIXME: There's a null value here for no apparent reason.
                    ->first();

                $applied->value = $value;
                $applied->save();

                $parent = $applied->event_application_parent_id;
            }

            // Notify admin that an entry has been updated
            $parent = Event_Application_Parent::find($parent);
            $parent->new = 1;
        }

        // Send confirmation email only if processing successful
        $email->sendEmail($to, $template, $data);

        return redirect()->route("success", $event)->with('success', 'Applied');
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

    public function delete($id) {
        Log::debug('Removing application: {id}', ['id' => $id]);

        $appId = Event_Application_Parent::find($id);
        $appId = $appId->application_id;

        $deleted = Event_Application_Entry::where('event_application_parent_id', $id)->delete();
        $deleted = Event_Application_Parent::find($id)->delete();

        return redirect()->route("event.applications", $appId)->with('success', 'Deleted');
    }

    // resend the application received email
    public function resendConfirmation($id) {
        Log::debug('Re-sending application received email for application: {id}', ['id' => $id]);

        $rawApplication = Event_Application_Parent::find($id);
        $app = Event_Application::find($rawApplication->application_id);
        $type = $app->type;

        $event = Event::find($app->event_id);

        //Log::debug('Getting details for Application {id}', ['id' => $rawApplication->id]);

        $apps = Event_Application_Entry::where('tenant_id', 1)
        ->where('event_application_parent_id', $rawApplication->id)
        ->get();

        $rating = 0;

        $artist = Artist::where('id', $apps[0]->artist_id)->first();
        if(isset($artist->rating)){
            $rating = $artist->rating;
        }

        foreach($apps as $application) {
            $field = Event_Application_Field::find($application->event_application_field_id);

            $name = $field->name;
            if (substr($field->expected_value, 0, 3) === 'img') {
                continue;
            }

            $items[$name] = $application->value;
        }

        //Log::debug('Built application entry: {application}', ['application' => $items]);

        $email = new EmailController;

        $to = $artist->email;
        $template = 'event_success';
        $data = [
            'name' => $artist->name,
            'event' => $event->name,
            'applicationType' => $type,
            'items' => $items,
        ];

        $email->sendEmail($to, $template, $data);

        //return redirect()->route("event.applications", $appId)->with('success', 'Re-sent');
    }

    public function exportCSV($id) {
        $filename = 'applications.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "inline; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'X-Accel-Buffering' => 'no'
        ];
        Log::info('Exporting Applications CSV');

        $columns = [];

        $fields = Event_Application_Field::where('tenant_id', 1)
            ->where('event_application_id', $id)
            ->get();

        foreach($fields as $field) {
            $columns[] = $field->name;
        }
        $columns[] = 'Accepted';
        $columns[] = 'Shortlisted';
        $columns[] = 'Rejected';

        $callback = function() use ($filename, $columns, $id) {
            $handle = fopen('php://output', 'w');
            Log::debug('Exporting Applications CSV: {name}', ['name' => $filename]);

            fputcsv($handle, $columns);

            $rawApplications = Event_Application_Parent::where('tenant_id', 1)
                ->where('application_id', $id)
                ->chunk(25, function ($applications) use ($handle) {
                    foreach ($applications as $application) {
                        $apps = Event_Application_Entry::where('tenant_id', 1)
                            ->where('event_application_parent_id', $application->id)
                            ->get();

                        $data = [];
                        foreach ($apps as $app) {
                            $data[] = $app->value;
                        }
                        $data[] = $application->accepted;
                        $data[] = $application->shortlisted;
                        $data[] = $application->rejected;

                        fputcsv($handle, $data);
                    }
                });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers)->send();
    }
}
