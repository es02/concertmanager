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
    Route::get('/artist/{id}', [ArtistController::class, 'getArtist']);
    Route::get('/artists/{pagenum?}', [ArtistController::class, 'getArtistList']);
    Route::get('/event/{id}', [EventController::class, 'getEvent']);
    Route::get('/events/{pagenum?}', [EventController::class, 'getEventList']);
    Route::get('/venue/{id}', [VenueController::class, 'getVenue']);
    Route::get('/venues/{pagenum?}', [VenueController::class, 'getVenueList']);

    Route::post('/artist/create', [ArtistController::class, 'createArtist']);
    Route::post('/event/create', [EventController::class, 'createEvent']);
    Route::post('/venue/create', [VenueController::class, 'createVenue']);
    Route::post('/artist/update', [ArtistController::class, 'updateArtist']);
    Route::post('/event/update', [EventController::class, 'updateEvent']);
    Route::post('/venue/update', [VenueController::class, 'updateVenue']);
    Route::post('/event/createSet', [EventController::class, 'createSet']);
});
