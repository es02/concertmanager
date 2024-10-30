<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Event_Application;
use App\Models\Event_Application_Field;
use App\Models\Event_Application_Entry;
use App\Models\Event_Application_Parent;
use App\Models\User;
use App\Models\Venue;

class SearchController extends Controller
{
    public function index(Request $request): Response
    {
        // get our search value from the request here:
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

        // Application entries are slightly complicated
        // For each application form we need to find the name and email fields in order to match back to a parent record
        // $applications = Event_Application_Parent::query()
        //     // when we have a search value, see if it matches anything in an artist model:
        //     ->when($search,
        //         fn ($query) => $query->where('name', 'LIKE', '%'.$search.'%')
        //             ->orWhere('email', 'LIKE', '%'.$search.'%')
        //     )
        //     ->paginate();

        $events = Event::query()
            // when we have a search value, see if it matches anything in an event model:
            ->when($search,
                fn ($query) => $query->where('name', 'LIKE', '%'.$search.'%')
                    // ->orWhere('email', 'LIKE', '%'.$search.'%')
            )
            ->paginate();

        $venues = Event::query()
            // when we have a search value, see if it matches anything in an venue model:
            ->when($search,
                fn ($query) => $query->where('venue_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%')
            )
            ->paginate();

        return Inertia::render('Search/Results', [
            'artists' => $artists,
            // and return the search value as a page prop to inertia/vue.
            // This is the value we watch in the data() property of the SearchInput.vue component.
            'search' => $search,
        ]);
    }
}
