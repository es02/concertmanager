<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/apply/{name}', [EventController::class, 'showApplication'])->name('apply');
Route::post('/apply', [EventController::class, 'applyForEvent'])->name('new.application');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/venue/create', function () {return Inertia::render('Venue/Create');})->name('venue.create');
    Route::get('/artist/{id}', [ArtistController::class, 'getArtist'])->name('artist');
    Route::get('/artists/{pagenum?}', [ArtistController::class, 'getArtistList'])->name('artists');
    Route::get('/event/{id}', [EventController::class, 'getEvent'])->name('event');
    Route::get('/events/{pagenum?}', [EventController::class, 'getEventList'])->name('events');
    Route::get('/venue/{id}', [VenueController::class, 'getVenue'])->name('venue');
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
    Route::post('/event/create', [EventController::class, 'createEvent'])->name('event.new');
    Route::post('/event/update', [EventController::class, 'updateEvent'])->name('event.update');
    Route::post('/event/createSet', [EventController::class, 'createSet'])->name('event.set.new'); // TODO:: Create controller endpoint and update me
    Route::post('/event/createApplication', [EventController::class, 'createSet'])->name('event.form.new'); // TODO:: Create controller endpoint and update me
    Route::post('/venue/create', [VenueController::class, 'createVenue'])->name('venue.new');
    Route::post('/venue/update', [VenueController::class, 'updateVenue'])->name('venue.update');
});
