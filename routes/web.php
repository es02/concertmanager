<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {return Inertia::render('Dashboard');})->name('dashboard');
Route::get('/dashboard', function () {return Inertia::render('Dashboard');})->name('dashboard');
Route::get('apply/success', function () {return Inertia::render('Apply/Success');})->name('success'); // Not really needed but helpful for prototyping/etc
Route::get('/apply/{name}', [ApplicationController::class, 'showApplicationForm'])->name('apply');
Route::post('/apply/{name}', [ApplicationController::class, 'applyForEvent'])->name('new.application');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/artist/create', function () {return Inertia::render('Artist/Create');})->name('artist.create');
    Route::get('/artist/{id}', [ArtistController::class, 'getArtist'])->name('artist');
    Route::get('/artists/export-csv', [ArtistController::class, 'exportCSV'])->name('artists.getCSV');
    Route::get('/artists/{pagenum}/{sortby?}', [ArtistController::class, 'getArtistList'])->name('artists');
    Route::get('/artists/{pagenum?}', [ArtistController::class, 'getArtistList'])->name('artists');
    Route::get('/event/create', [EventController::class, 'showCreateEvent'])->name('event.create');
    Route::get('/event/export-csv', [EventController::class, 'exportCSV'])->name('event.getCSV');
    Route::get('/event/{id}', [EventController::class, 'getEvent'])->name('event');
    Route::get('/event/createSet/{id}', [EventController::class, 'createSet'])->name('event.set.new'); // TODO:: Create controller endpoint and update me
    Route::get('/event/createApplication/{id}', [ApplicationController::class, 'showCreateApplication'])->name('event.form.new');
    Route::get('/event/applications/{id}/{pagenum?}', [ApplicationController::class, 'showApplications'])->name('event.applications');
    Route::get('/events/{pagenum?}', [EventController::class, 'getEventList'])->name('events');
    Route::get('/venue/create', function () {return Inertia::render('Venue/Create');})->name('venue.create');
    Route::get('/venue/export-csv', [VenueController::class, 'exportCSV'])->name('venue.getCSV');
    Route::get('/venue/{id}', [VenueController::class, 'getVenue'])->name('venue');
    Route::get('/venue/edit/{id}', [VenueController::class, 'getVenue'])->name('venue.edit');
    Route::get('/venues/{pagenum?}', [VenueController::class, 'getVenueList'])->name('venues');
    Route::get('/volunteers/{pagenum?}', [ArtistController::class, 'getArtistList'])->name('volunteers'); // TODO:: Create controller endpoint and update me
    Route::get('/volunteers/{id}', [ArtistController::class, 'getArtist'])->name('volunteer'); // TODO:: Create controller endpoint and update me
    Route::get('/staff/{pagenum?}', [ArtistController::class, 'getArtistList'])->name('staff'); // TODO:: Create controller endpoint and update me
    Route::get('/staff/{id}', [ArtistController::class, 'getArtist'])->name('staffmember'); // TODO:: Create controller endpoint and update me
    Route::get('/media/{pagenum?}', [ArtistController::class, 'getArtistList'])->name('media'); // TODO:: Create controller endpoint and update me
    Route::get('/media/{id}', [ArtistController::class, 'getArtist'])->name('mediaperson'); // TODO:: Create controller endpoint and update me
    Route::get('/email/{pagenum?}', [ArtistController::class, 'getArtistList'])->name('emails'); // TODO:: Create controller endpoint and update me
    Route::get('/email/{id}', [ArtistController::class, 'getArtist'])->name('email'); // TODO:: Create controller endpoint and update me
    Route::get('/settings', [ArtistController::class, 'getArtistList'])->name('settings'); // TODO:: Create controller endpoint and update me

    Route::post('/artist/create', [ArtistController::class, 'createArtist'])->name('artist.new');
    Route::post('/artist/update', [ArtistController::class, 'updateArtist'])->name('artist.update');
    Route::post('/artist/apply', [ArtistController::class, 'applyForEvent'])->name('artist.apply'); // TODO:: Add prefill logic to form and use seperate update endpoint
    Route::post('/artist/rate/{id}/{via}', [ArtistController::class, 'rate'])->name('artist.rate');
    Route::post('/artists/import-csv', [ArtistController::class, 'importCSV'])->name('artists.putCSV');
    Route::post('/event/applications/accept/{id}', [ApplicationController::class, 'accept'])->name('application.accept');
    Route::post('/event/applications/reject/{id}', [ApplicationController::class, 'reject'])->name('application.reject');
    Route::post('/event/applications/shortlist/{id}', [ApplicationController::class, 'shortlist'])->name('application.shortlist');
    Route::post('/event/import-csv', [EventController::class, 'importCSV'])->name('event.putCSV');
    Route::post('/event/create', [EventController::class, 'createEvent'])->name('event.new');
    Route::post('/event/update', [EventController::class, 'updateEvent'])->name('event.update');
    Route::post('/event/delete', [EventController::class, 'updateEvent'])->name('event.update');
    Route::post('/event/createSet/{id}', [EventController::class, 'createSet'])->name('event.set.new'); // TODO:: Create controller endpoint and update me
    Route::post('/event/createApplication/{id}', [ApplicationController::class, 'createOrUpdateApplication'])->name('event.form.new');
    Route::post('/event/publishApplication/{id}',[ApplicationController::class, 'publishApplication'])->name('event.form.publish');
    Route::post('/event/unpublishApplication/{id}',[ApplicationController::class, 'unpublishApplication'])->name('event.form.unpublish');
    Route::post('/event/deleteApplication/{id}', [ApplicationController::class, 'deleteApplication'])->name('event.form.delete');
    Route::post('/venue/import-csv', [VenueController::class, 'importCSV'])->name('venue.putCSV');
    Route::post('/venue/create', [VenueController::class, 'createVenue'])->name('venue.new');
    Route::post('/venue/update', [VenueController::class, 'updateVenue'])->name('venue.update');
    Route::post('/venue/delete', [VenueController::class, 'updateVenue'])->name('venue.destroy');
});
