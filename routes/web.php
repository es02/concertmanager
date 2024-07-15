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

    Route::post('/artist/create', [ArtistController::class, 'createArtist'])->name('new.artist');
    Route::post('/event/create', [EventController::class, 'createEvent'])->name('new.event');
    Route::post('/venue/create', [VenueController::class, 'createVenue'])->name('new.venue');
    Route::post('/artist/update', [ArtistController::class, 'updateArtist'])->name('update.artist');
    Route::post('/event/update', [EventController::class, 'updateEvent'])->name('update.event');
    Route::post('/venue/update', [VenueController::class, 'updateVenue'])->name('update.venue');
    Route::post('/event/createSet', [EventController::class, 'createSet'])->name('new.artist.set');
});
