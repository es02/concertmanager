<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

        $validator = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $photo = $request->pic_url->storePublicly(
            'venue-images', ['disk' => 'public']
        );

        $photo = "../storage/" . $photo;

        $description = '';
        $location = '';
        $capacity = '';
        $standard_fee = '';
        $ticket_cut = '';
        $pic = '';
        $cut_type = '';
        $fee_type = '';
        $additional_fees = '';
        $tech_specs = '';
        $backline = '';

        if($request->bio){$description = $request->bio;}
        if($request->location){$location = $request->location;}
        if($request->capacity){$capacity = $request->capacity;}
        if($request->standard_fee){$standard_fee = $request->standard_fee;}
        if($request->ticket_cut){$ticket_cut = $request->ticket_cut;}
        if($request->cut_type){$cut_type = $request->cut_type;}
        if($request->fee_type){$fee_type = $request->fee_type;}
        if($request->additional_fees){$additional_fees = $request->additional_fees;}
        if($request->tech_specs){$tech_specs = $request->tech_specs;}
        if($request->backline){$backline = $request->backline;}
        if($request->pic_url){
            $pic = $request->pic_url->storePublicly(
                'venue-images', ['disk' => 'public']
            );
            $pic = "../storage/" . $photo;
        }

        $venue = Venue::Create([
            'tenant_id' => 0,
            'venue_name' => $request->name,
            'email' => $request->email,
            'bio' => $description,
            'pic_url' => $pic,
            'location' => $location,
            'capacity' => $capacity,
            'standard_fee' => $standard_fee,
            'ticket_cut' => $ticket_cut,
            'cut_type' => $cut_type,
            'fee_type' => $fee_type,
            'additional_fees' => $additional_fees,
            'tech_specs' => $tech_specs,
            'backline' => $backline,
            'state' => 'active'
        ]);

    }

    public function updateVenue(Request $request, $id){
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $venue = Venue::find($id);

        $photo = $venue->pic_url;

        if (isset($request->pic_url)) {
            $photo = $request->pic_url->storePublicly(
                'venue-images', ['disk' => 'public']
            );

            $photo = Storage::url($request->pic_url);
        }
        $venue->venue_name = $request->name;
        $venue->email = $request->email;
        $venue->bio = $request->bio;
        $venue->pic_url = $photo;
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
