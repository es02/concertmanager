<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Sponsor;
use App\Models\User;

class SponsorController extends Controller
{
    public function getSponsorList($pagenum = 0, $sortby = 'name'){
        $count = Sponsor::count();

        // don't skip ahead a page
        if ($pagenum !== 0) {
            $pagenum--;
        }

        Log::debug('Generating sponsor list sorted by: {sort}', ['sort' => $sortby]);

        $sponsors = Sponsor::where('state', '!=', 'deleted')
            ->orderBy($sortby, 'asc')
            ->skip($pagenum * 10)
            ->take(10)
            ->get();

        return Inertia::render('Sponsor/List', [
            'sponsors' => $sponsors,
            'count' => $count,
        ]);
    }

    public function getSponsor($id){
        $sponsor = Sponsor::find($id);

        return Inertia::render('Sponsor/Show', [
            'sponsor' => $sponsor,
        ]);
    }

    public function createSponsor(Request $request){
        $validator = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $photo = '';

        if(is_uploaded_file($request->pic_url)) {
            $photo = $request->pic_url->storePublicly(
                'sponsor-images', ['disk' => 'public']
            );

            $photo = "../storage/" . $photo;
        }

        $user = User::where('tenant_id', 1)
            ->where('email', $request->email)
            ->count();

        if ($user === 0) {
            $user = User::create([
                'tenant_id' => 1,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(40)), // new user will need to reset password to be able to log in
            ]);
        } else {
            $user = User::where('tenant_id', 1)
            ->where('email', $request->email)
            ->first();
        }

        $sponsor = Sponsor::Create([
            'tenant_id' => 1,
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'pic_url' => $photo,
            'location' => $request->location,
            'user_id' => $user->id,
            'state' => 'active'
        ]);

        return redirect()->route("sponsor", $sponsor->id)->with('status', 'Created');
    }

    public function updateSponsor(Request $request){
        $validator = $request->validate([
            'id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $id = $request->id;
        $sponsor = Sponsor::find($id);

        $photo = $sponsor->pic_url;

        if(is_uploaded_file($request->pic_url)) {
            $photo = $request->pic_url->storePublicly(
                'sponsor-images', ['disk' => 'public']
            );

            $photo = "../storage/" . $photo;
        }

        $sponsor->name = $request->name;
        $sponsor->email = $request->email;
        $sponsor->bio = $request->bio;
        $sponsor->pic_url = $photo;
        $sponsor->location = $request->location;
        // $sponsor->user_id = $request->user_id;
        // $sponsor->notes = $request->notes;
        // $sponsor->state = $request->status;
        $sponsor->save();
        return back()->with('status', 'sponsor-updated');
    }

    public function destroySponsor($id){
        $sponsor = Sponsor::find($id)->delete();

        return redirect()->route("sponsors", 1)->with('success', 'Deleted');
    }
}
