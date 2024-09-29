<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Artist;
use App\Models\Event_Application_Entry;
use App\Models\Event_Application_Parent;

class ArtistController extends Controller
{

    public function getArtistList($pagenum = 0, $sortby = 'name'){
        $count = Artist::count();

        // don't skip ahead a page
        if ($pagenum !== 0) {
            $pagenum--;
        }

        Log::debug('Generating artist list sorted by: {sort}', ['sort' => $sortby]);

        ;

        if ($sortby === 'rating') {
            $artists = Artist::where('state', '!=', 'deleted')
                ->orderBy($sortby, 'desc')
                ->skip($pagenum * 10)
                ->take(10)
                ->get();
        } else {
            $artists = Artist::where('state', '!=', 'deleted')
                ->orderBy($sortby, 'asc')
                ->skip($pagenum * 10)
                ->take(10)
                ->get();
        }

        return Inertia::render('Artist/List', [
            'artists' => $artists,
            'count' => $count,
        ]);
    }

    public function getArtist($id){
        $artist = Artist::find($id);

        return Inertia::render('Artist/Show', [
            'artist' => $artist,
        ]);
    }

    public function createArtist(Request $request){
        // due to multitenant architecture we need to check here if the email already exists
        // and reject form validation if it does

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return redirect('Artist/Create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $artist = Artist::Create([
            'tenant_id' => 0,
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'genre' => $request->genre,
            'pic_url' => $request->pic_url,
            'location' => $request->location,
            'standard_fee' => $request->standard_fee,
            'standard_rider' => $request->standard_rider,
            'tech_specs' => $request->tech_specs,
            'epk_url' => $request->epk_url,
            'booked_previously' => $request->booked_previously,
            'formed' => $request->formed,
            'state' => 'active'
        ]);

    }

    public function updateArtist(Request $request, $id){
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $artist = Artist::find($id);
        $artist->name = $request->name;
        $artist->email = $request->email;
        $artist->bio = $request->bio;
        $artist->genre = $request->genre;
        $artist->pic_url = $request->pic_url;
        $artist->location = $request->location;
        $artist->standard_fee = $request->standard_fee;
        $artist->standard_rider = $request->standard_rider;
        $artist->tech_specs = $request->tech_specs;
        $artist->epk_url = $request->epk_url;
        $artist->booked_previously = $request->booked_previously;
        $artist->rating = $request->rating;
        $artist->blacklisted = $request->blacklisted;
        $artist->formed = $request->formed;
        $artist->notes = $request->notes;
        $artist->state = $request->status;
        $artist->save();
        return back()->with('status', 'artist-updated');
    }

    public function rate(Request $request, $id, $via = 'artist'){
        if ($via === 'application') {
            $parent = Event_Application_Parent::where('tenant_id', 1)
                ->where('id', $id)
                ->first();
            $artist = Event_Application_Entry::where('tenant_id', 1)
                ->where('event_application_parent_id', $rawApplication->id)
                ->first();
            $id = $artist->artist_id;
        }
    }



    public function destroyArtist($id){
        $artist = Artist::find($id);
        $artist->state = $request->status;
        $artist->save();
    }

    public function exportCSV() {
        $filename = 'artists.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "inline; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'X-Accel-Buffering' => 'no'
        ];
        Log::info('Exporting artist CSV');

        $columns = [
            'Name *',
            'Email *',
            'Genre',
            'Bio',
            'Location',
            'Standard Fee',
            'Rider',
            'Tech Rider',
            'EPK',
            'Booked Previously',
            'Year Formed',
            'Rating',
            'Blacklisted',
            'Notes',
        ];

        $callback = function() use ($filename, $columns) {
            $handle = fopen('php://output', 'w');
            Log::debug('Exporting artist CSV: {name}', ['name' => $filename]);

            fputcsv($handle, $columns);

             // Fetch and process data in chunks
            Artist::chunk(25, function ($artists) use ($handle) {
                foreach ($artists as $artist) {
                    Log::debug('Exporting artist: {name}', ['name' => $artist->name]);
                    // Extract data from each artist.
                    $booked = "No";
                    if ($artist->booked_previously === 1) {
                        $booked = "Yes";
                    }

                    $data = [
                        isset($artist->name)?               $artist->name                       : '',
                        isset($artist->email)?              $artist->email                      : '',
                        isset($artist->genre)?              $artist->genre                      : '',
                        isset($artist->bio)?                trim($artist->bio,'"')              : '',
                        isset($artist->location)?           $artist->location                   : '',
                        isset($artist->standard_fee)?       $artist->standard_fee               : '',
                        isset($artist->standard_rider)?     trim($artist->standard_rider,'"')   : '',
                        isset($artist->tech_specs)?         trim($artist->tech_specs,'"')       : '',
                        isset($artist->epk_url)?            $artist->epk_url                    : '',
                        $booked,
                        isset($artist->formed)?             $artist->formed                     : '',
                        isset($artist->rating)?             $artist->rating                     : '',
                        isset($artist->blacklisted)?        $artist->blacklisted                : '',
                        isset($artist->notes)?              trim($artist->notes,'"')            : '',
                    ];

                    // Write data to a CSV file.
                    echo fputcsv($handle, $data);
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
        Log::debug('Importing artist CSV');

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

        return redirect()->route('artists')->with('success', 'Data has been added successfully.');
    }

    public function getchunkdata($chunkdata)
    {
        foreach($chunkdata as $column){
            Log::debug('Importing artist: {name}', ['name' => $column[0]]);

            $artistCheck = Artist::Where('tenant_id', 1)
                ->where('name', $column[0])
                ->count();

            Log::debug('Artist records found: {count}', ['count' => $artistCheck]);

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
        }
    }
}
