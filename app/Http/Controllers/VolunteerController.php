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
use App\Models\Volunteer;
use App\Models\User;
use App\Http\Controllers\Utils;

class VolunteerController extends Controller
{
    public function getVolunteerList($pagenum = 0, $sortby = 'name'){
        $count = Volunteer::count();

        // don't skip ahead a page
        if ($pagenum !== 0) {
            $pagenum--;
        }

        Log::debug('Generating volunteer list sorted by: {sort}', ['sort' => $sortby]);

        $volunteers = Volunteer::where('state', '!=', 'deleted')
            ->orderBy($sortby, 'asc')
            ->skip($pagenum * 10)
            ->take(10)
            ->get();

        return Inertia::render('Volunteer/List', [
            'Volunteers' => $volunteers,
            'count' => $count,
        ]);
    }

    public function getVolunteer($id){
        $volunteer = Volunteer::find($id);

        return Inertia::render('Volunteer/Show', [
            'Volunteer' => $volunteer,
        ]);
    }

    public function createVolunteer(Request $request){
        $validator = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $photo = '';

        if(is_uploaded_file($request->pic_url)) {
            $photo = $request->pic_url->storePublicly(
                'volunteer-images', ['disk' => 'public']
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

        $volunteer = Volunteer::Create([
            'tenant_id' => 1,
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'pic_url' => $photo,
            'location' => $request->location,
            'user_id' => $user->id,
            'state' => 'active'
        ]);

        return redirect()->route("Volunteer", $volunteer->id)->with('status', 'Created');
    }

    public function updateVolunteer(Request $request){
        $validator = $request->validate([
            'id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $id = $request->id;
        $volunteer = Volunteer::find($id);

        $photo = $volunteer->pic_url;

        if(is_uploaded_file($request->pic_url)) {
            $photo = $request->pic_url->storePublicly(
                'volunteer-images', ['disk' => 'public']
            );

            $photo = "../storage/" . $photo;
        }

        $volunteer->name = $request->name;
        $volunteer->email = $request->email;
        $volunteer->bio = $request->bio;
        $volunteer->pic_url = $photo;
        $volunteer->location = $request->location;
        // $volunteer->user_id = $request->user_id;
        // $volunteer->notes = $request->notes;
        // $volunteer->state = $request->status;
        $volunteer->save();
        return back()->with('status', 'Volunteer-updated');
    }

    public function destroyVolunteer($id){
        $volunteer = Volunteer::find($id)->delete();

        return redirect()->route("Volunteers", 1)->with('success', 'Deleted');
    }

    public function importCSV(Request $request)
    {
        $request->validate([
            'import_csv' => 'required|mimes:csv',
        ]);
        //read csv file and skip data
        Utils::importCSV($request, 'media');

        return redirect()->route('tasks')->with('success', 'Data has been added successfully.');
    }
}
