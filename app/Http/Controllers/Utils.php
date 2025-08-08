<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;
use App\Models\Artist;
use App\Models\Sponsor;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Models\Venue;
use App\Models\Volunteer;

function recordCheck (String $type, String $name) {
    $types = ['artist', 'sponsor', 'task', 'venue', 'volunteer'];

    if (!Arr::has($types, $type)){
        Log::error("Attempting to check invalid record type: $type");
        abort(500);
    }

    $column = 'name';

    switch ($type) {
	    case 'artist':
	        $check = Artist::Where('tenant_id', 1);
	        break;
	    case 'sponsor':
	        $check = Sponsor::Where('tenant_id', 1);
	        break;
        case 'task':
            Task::Where('tenant_id', 1);
            $column = 'task_list_id';
            break;
	    case 'volunteer':
	        $check = Volunteer::Where('tenant_id', 1);
	        break;
	    case 'venue':
	        $check = Venue::Where('tenant_id', 1);
            $column = 'venue_name';
	    	break;
	    default:
            Log::error("Attempting to check invalid type: $type");
            abort(500);
	}

    $count = $check->where($column, $name)->count();

    Log::debug('{type} records found: {count}', ['type' => $type,'count' => $check]);

    return $count;
}

function readCSV () {
	switch ($type) {
	    case 'artist':
	        $this->getArtistChunkData($chunkdata);
	        break;
	    case 'sponsor':
	        $this->getSponsorChunkData($chunkdata);
	        break;
	    case 'volunteer':
	        $this->getVolunteerChunkData($chunkdata);
	        break;
	    case 'venue':
	        $this->getVenueChunkData($chunkdata);
	    	break;
	    case 'task':
	        $this->getTaskChunkData($chunkdata);
	    	break;
	    default:
            Log::error("CSV of $type type found.");
            abort(500);
	}

    return true;
}


function importCSV(Request $request, $type){
    $request->validate([
        'import_csv' => 'required|mimes:csv',
    ]);
    //read csv file and skip data
    $file = $request->file('import_csv');
    $handle = fopen($file->path(), 'r');
    Log::debug("Importing $type CSV");

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

        $this->readCSV($chunkdata);
    }
    fclose($handle);

    return back()->with('success', 'Data has been added successfully.');
}

function getArtistChunkData($chunkdata){
    foreach($chunkdata as $column){
        Log::debug('Importing artist: {name}', ['name' => $column[0]]);

        $artistCheck = $this->check('artist', $column[0]);

        if ($artistCheck !== 0){
            Log::debug('Artist found - updating');
            $artist = Artist::Where('tenant_id', 1)
            ->where('name', $column[0])
            ->first();
        } else {
            Log::debug('Artist not found - creating');
            $artist = new Artist();
            $artist->tenant_id = 1;
        }

        $booked = 0;
        if($column[9] === 'yes' || $column[9] === 'Yes') {
            $booked = 1;
        }

        $rating = 0;
        if (isset($column[11])) {
            $rating = $column[11];
        }

        $artist->name = $column[0];
        $artist->email = $column[1];
        $artist->genre = $column[2];
        $artist->bio = $column[3];
        $artist->location = $column[4];
        $artist->standard_fee = $column[5];
        $artist->standard_rider = $column[6];
        $artist->tech_specs = $column[7];
        $artist->epk_url = $column[8];
        $artist->booked_previously = $booked;
        $artist->formed = $column[10];
        $artist->rating = $rating;
        $artist->blacklisted = $column[12];
        $artist->notes = $column[13];
        $artist->save();

        $user = User::where('tenant_id', 1)
        ->where('email', $artist->email)
        ->count();

        if ($user === 0) {
            User::create([
                'tenant_id' => 1,
                'name' => $artist->name,
                'email' => $artist->email,
                'password' => Hash::make(Str::random(40)), // new user will need to reset password to be able to log in
            ]);
        }

        return true;
    }
}

function getSponsorChunkData($chunkdata){
    foreach($chunkdata as $column){
        Log::debug('Importing sponsor: {name}', ['name' => $column[0]]);
    }
}

function getVolunteerChunkData($chunkdata){
    foreach($chunkdata as $column){
        Log::debug('Importing volunteer: {name}', ['name' => $column[0]]);
    }
}

function getVenueChunkData($chunkdata){
    foreach($chunkdata as $column){
        Log::debug('Importing venue: {name}', ['name' => $column[0]]);

        $venueCheck = $this->check('venue', $column[0]);

        Log::debug('Venue records found: {count}', ['count' => $venueCheck]);

        if ($venueCheck !== 0){
            Log::debug('Venue found - updating');
            $venue = Venue::Where('tenant_id', 1)
            ->where('venue_name', $column[0])
            ->first();
        } else {
            Log::debug('Venue not found - creating');
            $venue = new Venue();
            $venue->tenant_id = 1;
        }

        $description = null;
        $location = null;
        $capacity = null;
        $standard_fee = null;
        $ticket_cut = null;
        $pic = null;
        $cut_type = null;
        $fee_type = null;
        $additional_fees = null;
        $tech_specs = null;
        $backline = null;

        if($column[2]){$description = $column[2];}
        if($column[3]){$location = $column[3];}
        if($column[4]){$capacity = $column[4];}
        if($column[5]){$standard_fee = $column[5];}
        if($column[7]){$ticket_cut = $column[7];}
        if($column[8]){$cut_type = $column[8];}
        if($column[6]){$fee_type = $column[6];}
        if($column[9]){$additional_fees = $column[9];}
        if($column[10]){$tech_specs = $column[10];}
        if($column[11]){$backline = $column[11];}

        $venue->venue_name = $column[0];
        $venue->email = $column[1];
        $venue->bio = $description;
        $venue->location = $location;
        $venue->capacity = $capacity;
        $venue->standard_fee = $standard_fee;
        $venue->fee_type = $fee_type;
        $venue->ticket_cut = $ticket_cut;
        $venue->cut_type = $cut_type;
        $venue->additional_fees = $additional_fees;
        $venue->tech_specs = $tech_specs;
        $venue->backline = $backline;
        $venue->save();
    }
}

function getTaskChunkData($chunkdata){
    foreach($chunkdata as $column){
        Log::debug('Importing task: {name}', ['name' => $column[0]]);

        $user =  Auth::user();

        $taskList = TaskList::where('tenant_id', 1)
        ->where('owner_id', $user->id)
        ->first();

        $taskCheck = $this->check('task', $taskList->id);

        Log::debug('Task records found: {count}', ['count' => $taskCheck]);

        if ($taskCheck !== 0){
            Log::debug('Task found - updating');
            $task = Task::where('task_list_id', $taskList->id)
                ->where('name', $column[0])
                ->first();
        } else {
            Log::debug('Task not found - creating');
            $task = new Task();
            $task->task_list_id = $taskList->id;
        }

        $task->name = $column[0];
        $task->due = $column[1];
        $task->completed = $column[2];
        $task->order_id = $column[3];
        $task->save();
    }
}
