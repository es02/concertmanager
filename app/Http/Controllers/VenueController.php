<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Venue;

class VenueController extends Controller
{
    public function getVenueList($pagenum = 0){
        $count = Venue::count();
        $venues = Venue::where('state', '!=', 'deleted')
            ->orderBy('name')
            ->skip($pagenum*10)
            ->take(10)
            ->get();

        return Inertia::render('Venue/List', [
            'venues' => $venues,
            'count' => $count,
        ]);
    }

    public function getVenue($id){
        $venue = Venue::find($id);

        return Inertia::render('Venue/Show', [
            'venue' => $venue,
        ]);
    }

    public function createVenue(Request $request){
        // due to multitenant architecture we need to check here if the email already exists
        // and reject form validation if it does

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return redirect('Venue/Create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $venue = Venue::Create([
            'tenant_id' => 0,
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'pic_url' => $request->pic_url,
            'location' => $request->location,
            'capacity' => $request->capacity,
            'standard_fee' => $request->standard_fee,
            'ticket_cut' => $request->ticket_cut,
            'cut_type' => $request->cut_type,
            'fee_type' => $request->fee_type,
            'additional_fees' => $request->additional_fees,
            'tech_specs' => $request->tech_specs,
            'backline' => $request->backline,
            'state' => 'active'
        ]);

    }

    public function updateVenue(Request $request, $id){
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $venue = Venue::find($id);
        $venue->name = $request->name;
        $venue->email = $request->email;
        $venue->bio = $request->bio;
        $venue->pic_url = $request->pic_url;
        $venue->location = $request->location;
        $venue->capacity = $request->capacity;
        $venue->standard_fee = $request->standard_fee;
        $venue->ticket_cut = $request->ticket_cut;
        $venue->cut_type = $request->cut_type;
        $venue->fee_type = $request->fee_type;
        $venue->additional_fees = $request->additional_fees;
        $venue->tech_specs = $request->tech_specs;
        $venue->backline = $request->backline;
        $venue->state = $request->status;
        $venue->save();
        return back()->with('status', 'venue-updated');
    }

    public function destroyVenue($id){
        $venue = Venue::find($id);
        $venue->state = $request->status;
        $venue->save();
    }
}
