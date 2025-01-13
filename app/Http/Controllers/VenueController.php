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

        // don't skip ahead a page
        if ($pagenum !== 0) {
            $pagenum--;
        }

        $venues = Venue::where('state', '!=', 'deleted')
            ->orderBy('venue_name')
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

        if(is_uploaded_file($request->pic_url)){
            $pic = $request->pic_url->storePublicly(
                'venue-images', ['disk' => 'public']
            );
            $pic = "../storage/" . $pic;
        }

        $venue = Venue::Create([
            'tenant_id' => 1,
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

        return redirect()->route("venue", $venue->id)->with('status', 'Created');
    }

    public function updateVenue(Request $request){
        $validator = $request->validate([
            'id' => ['required', 'numeric'],
            'venue_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $id = $request->id;

        $venue = Venue::find($id);

        Log::debug('Updating venue: {venue}', ['venue' => $venue->venue_name]);

        $photo = $venue->pic_url;

        if (is_uploaded_file($request->pic_url)) {
            $photo = $request->pic_url->storePublicly(
                'venue-images', ['disk' => 'public']
            );

            $photo = "../storage/" . $photo;
        }

        $cut_type = null;
        $fee_type = null;
        if($request->cut_type !== ''){$cut_type = $request->cut_type;}
        if($request->fee_type !== ''){$fee_type = $request->fee_type;}

        $venue->venue_name = $request->venue_name;
        $venue->email = $request->email;
        $venue->bio = $request->bio;
        $venue->pic_url = $photo;
        $venue->location = $request->location;
        $venue->capacity = $request->capacity;
        $venue->standard_fee = $request->standard_fee;
        $venue->ticket_cut = $request->ticket_cut;
        $venue->cut_type = $cut_type;
        $venue->fee_type = $fee_type;
        $venue->additional_fees = $request->additional_fees;
        $venue->tech_specs = $request->tech_specs;
        $venue->backline = $request->backline;
        // $venue->state = $request->status;
        $venue->save();
        return back()->with('status', 'venue-updated');
    }

    public function destroyVenue($id){
        Log::debug('Deleting venue: {venue}', ['venue' => $id]);
        $venue = Venue::find($id)->delete();
        return back()->with('status', 'venue-deleted');
    }

    public function exportCSV() {
        $filename = 'venues.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "inline; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'X-Accel-Buffering' => 'no'
        ];
        Log::info('Exporting venue CSV');

        $columns = [
            'Name *',
            'Email *',
            'bio',
            'Location',
            'Capacity',
            'Standard Fee',
            'Fee Type',
            'Ticket Cut',
            'Cut Type',
            'Additional Fees',
            'Tech Specs',
            'Backline',
        ];

        $callback = function() use ($filename, $columns) {
            $handle = fopen('php://output', 'w');
            Log::debug('Exporting venue CSV: {name}', ['name' => $filename]);

            fputcsv($handle, $columns);

             // Fetch and process data in chunks
            Venue::chunk(25, function ($venues) use ($handle) {
                foreach ($venues as $venue) {
                    Log::debug('Exporting venue: {name}', ['name' => $venue->venue_name]);
                    // Extract data from each venue.
                    $data = [
                        isset($venue->venue_name)?          $venue->venue_name          : '',
                        isset($venue->email)?               $venue->email               : '',
                        isset($venue->bio)?                 trim($venue->bio,'"')       : '',
                        isset($venue->location)?            $venue->location            : '',
                        isset($venue->capacity)?            $venue->capacity            : '',
                        isset($venue->standard_fee)?        $venue->standard_fee        : '',
                        isset($venue->fee_type)?            $venue->fee_type            : '',
                        isset($venue->ticket_cut)?          $venue->ticket_cut          : '',
                        isset($venue->cut_type)?            $venue->cut_type            : '',
                        isset($venue->additional_fees)?     $venue->additional_fees     : '',
                        isset($venue->tech_specs)?          $venue->tech_specs          : '',
                        isset($venue->backline)?            $venue->backline            : '',
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
        $file = $request->file('import_csv');
        $handle = fopen($file->path(), 'r');
        Log::debug('Importing venue CSV');

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

        return redirect()->route('venues')->with('success', 'Data has been added successfully.');
    }

    public function getchunkdata($chunkdata)
    {
        foreach($chunkdata as $column){
            Log::debug('Importing venue: {name}', ['name' => $column[0]]);

            $venueCheck = Venue::Where('tenant_id', 1)
                ->where('venue_name', $column[0])
                ->count();

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
}
