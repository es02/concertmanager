<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Artist;
use App\Actions\Fortify\PasswordValidationRules;

class ArtistController extends Controller
{
    use PasswordValidationRules;

    public function getArtistList($pagenum = 0){
        $count = Artist::count();
        $artists = Artist::where('state', '!=', 'deleted')
            ->orderBy('name')
            ->skip($pagenum*10)
            ->take(10)
            ->get();

        return Inertia::render('Artist/List', [
            'artists' => $artists,
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

        $email_check = Artist::where('tenant_id', 0)
            ->where('email', '=', $request->email)
            ->count();

        $data = $request->all();
        $data['email_exists'] = $email_check;

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000', 'declined_if:email_exists,1'],
            'password' => $this->passwordRules(),
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
            'password' => Hash::make($request->password),
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
            'email' => ['required', 'string', 'max:1000', 'declined_if:email_exists,1'],
        ]);

        $artist = Artist::find($id);
        $artist->name = $request->name;
        $artist->email = $request->email;
        $artist->password = Hash::make($request->password);
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

    public function destroyArtist($id){
        $artist = Artist::find($id);
        $artist->state = $request->status;
        $artist->save();
    }
}
