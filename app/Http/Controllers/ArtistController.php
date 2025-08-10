<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Artist;
use App\Models\Event_Application_Entry;
use App\Models\Event_Application_Parent;
use App\Models\User;
use App\Http\Controllers\Utils;

class ArtistController extends Controller
{

    public function getArtistList($pagenum = 0, $sortby = 'name'){
        $count = Artist::count();

        // don't skip ahead a page
        if ($pagenum !== 0) {
            $pagenum--;
        }

        Log::debug('Generating artist list sorted by: {sort}', ['sort' => $sortby]);

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

    public function search(Request $request): Response {
        $search = $request->search;

        $artists = Artist::query()
            // when we have a search value, see if it matches anything in an artist model:
            ->when($search,
                fn ($query) => $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%')
                    ->orWhere('location', 'LIKE', '%'.$search.'%')
                    ->orWhere('genre', 'LIKE', '%'.$search.'%')
            )
            ->paginate();

        return Inertia::render('Artist/List', [
            'artists' => $artists,
            'count' => $artists->total(),
            // and return the search value as a page prop to inertia/vue.
            // This is the value we watch in the data() property of the SearchInput.vue component.
            'search' => $search,
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

        $validator = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $photo = '';

        if(is_uploaded_file($request->pic_url)) {
            $photo = $request->pic_url->storePublicly(
                'artist-images', ['disk' => 'public']
            );

            $photo = "../storage/" . $photo;
        }

        $artist = Artist::Create([
            'tenant_id' => 1,
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'genre' => $request->genre,
            'pic_url' => $photo,
            'location' => $request->location,
            'standard_fee' => $request->standard_fee,
            'standard_rider' => $request->standard_rider,
            'tech_specs' => $request->tech_specs,
            'epk_url' => $request->epk_url,
            'rating' => $request->rating,
            'booked_previously' => $request->booked_previously,
            'blacklisted' => $request->blacklisted,
            'formed' => $request->formed,
            'notes' => $request->notes,
            'state' => 'active'
        ]);

        $user = User::where('tenant_id', 1)
            ->where('email', $artist->email)
            ->count();

        if ($user === 0) {
            User::create([
                'tenant_id' => 1,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(40)), // new user will need to reset password to be able to log in
            ]);
        }

        return redirect()->route("artist", $artist->id)->with('status', 'Created');
    }

    public function updateArtist(Request $request){
        $validator = $request->validate([
            'id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $id = $request->id;
        $artist = Artist::find($id);

        $photo = $artist->pic_url;

        if(is_uploaded_file($request->pic_url)) {
            $photo = $request->pic_url->storePublicly(
                'artist-images', ['disk' => 'public']
            );

            $photo = "../storage/" . $photo;
        }

        $artist->name = $request->name;
        $artist->email = $request->email;
        $artist->bio = $request->bio;
        $artist->genre = $request->genre;
        $artist->pic_url = $photo;
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
        // $artist->state = $request->status;
        $artist->save();
        return back()->with('status', 'artist-updated');
    }

    public function rate(Request $request, $id, $via = 'artist'){
        if ($via === 'application') {
            $parent = Event_Application_Parent::where('tenant_id', 1)
                ->where('id', $id)
                ->first();
            $artist = Event_Application_Entry::where('tenant_id', 1)
                ->where('event_application_parent_id', $parent->id)
                ->first();
            $id = $artist->artist_id;
        }

        $artist = Artist::find($id);
        $artist->rating = $request->rating;
        $artist->save();

        return back()->with('status', 'artist-rated');
    }



    public function destroyArtist($id){
        $artist = Artist::find($id)->delete();

        return redirect()->route("artists", 1)->with('success', 'Deleted');
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
        Utils::importCSV($request, 'artist');

        return redirect()->route('artists')->with('success', 'Data has been added successfully.');
    }
}
