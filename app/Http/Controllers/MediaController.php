<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Media;

class MediaController extends Controller
{
    public function getMediaList($pagenum = 0, $sortby = 'name'){
        $count = Media::count();

        // don't skip ahead a page
        if ($pagenum !== 0) {
            $pagenum--;
        }

        Log::debug('Generating media list sorted by: {sort}', ['sort' => $sortby]);
        
        $medias = Media::where('state', '!=', 'deleted')
            ->orderBy($sortby, 'asc')
            ->skip($pagenum * 10)
            ->take(10)
            ->get();

        return Inertia::render('Media/List', [
            'Medias' => $medias,
            'count' => $count,
        ]);
    }
    
    public function getMedia($id){
        $media = Media::find($id);

        return Inertia::render('Media/Show', [
            'Media' => $media,
        ]);
    }
    
    public function createMedia(Request $request){
        $validator = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $photo = '';

        if(is_uploaded_file($request->pic_url)) {
            $photo = $request->pic_url->storePublicly(
                'media-images', ['disk' => 'public']
            );

            $photo = "../storage/" . $photo;
        }

        $media = Media::Create([
            'tenant_id' => 1,
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'pic_url' => $photo,
            'location' => $request->location,
            // 'user_id' => null,
            'state' => 'active'
        ]);

        return redirect()->route("Media", $media->id)->with('status', 'Created');
    }
    
    public function updateMedia(Request $request){
        $validator = $request->validate([
            'id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:1000'],
        ]);

        $id = $request->id;
        $media = Media::find($id);

        $photo = $media->pic_url;

        if(is_uploaded_file($request->pic_url)) {
            $photo = $request->pic_url->storePublicly(
                'Media-images', ['disk' => 'public']
            );

            $photo = "../storage/" . $photo;
        }

        $media->name = $request->name;
        $media->email = $request->email;
        $media->bio = $request->bio;
        $media->pic_url = $photo;
        $media->location = $request->location;
        // $media->user_id = $request->user_id;
        // $media->notes = $request->notes;
        // $media->state = $request->status;
        $media->save();
        return back()->with('status', 'Media-updated');
    }
    
    public function destroyMedia($id){
        $media = Media::find($id)->delete();

        return redirect()->route("Medias", 1)->with('success', 'Deleted');
    }
}
