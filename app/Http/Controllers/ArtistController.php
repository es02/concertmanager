<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Artist;
use App\Models\Event_Application_Entry;
use App\Models\Event_Application_Parent;

class ArtistController extends Controller
{

    public function getArtistList($pagenum = 0){
        $count = Artist::count();

        // don't skip ahead a page
        if ($pagenum !== 0) {
            $pagenum--;
        }

        $artists = Artist::where('state', '!=', 'deleted')
            ->orderBy('name')
            ->skip($pagenum * 10)
            ->take(10)
            ->get();

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
        $artists->formed = $request->formed;
        $artist->state = $request->status;
        $artist->save();
        return back()->with('status', 'artist-updated');
    }

    public function rate(Request $request, $id, $via = 'artist'){
        if ($via == 'application') {
            $parent = Event_Application_Parent::where('tenant_id', 1)
                ->where('id', $id)
                ->first();
            $artist = Event_Application_Entry::where('tenant_id', 1)
                ->where('event_application_parent_id', $rawApplication->id)
                ->first();
            $id = $artist->artist_id;
        }

        $artist = Artist::find($id);
        $artist->rating = $request->rating;
        $artist->save();
    }

    public function destroyArtist($id){
        $artist = Artist::find($id);
        $artist->state = $request->status;
        $artist->save();
    }
}
