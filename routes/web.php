<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\VolunteerController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {return Inertia::render('Dashboard');})->name('home');
Route::get('/dashboard', function () {return Inertia::render('Dashboard');})->name('dashboard');
Route::get('apply/success', function () {return Inertia::render('Apply/Success');})->name('success');
Route::get('/apply/{name}', [ApplicationController::class, 'showApplicationForm'])->name('apply');
Route::post('/apply/{name}', [ApplicationController::class, 'applyForEvent'])->name('new.application');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/applications/{id}/export-csv', [ApplicationController::class, 'exportCSV'])->name('applications.getCSV');
    Route::get('/artist/create', function () {return Inertia::render('Artist/Create');})->name('artist.create');
    Route::get('/artist/{id}', [ArtistController::class, 'getArtist'])->name('artist');
    Route::get('/artists/export-csv', [ArtistController::class, 'exportCSV'])->name('artists.getCSV');
    Route::get('/artists/{pagenum}/{sortby?}', [ArtistController::class, 'getArtistList']);
    Route::get('/artists/{pagenum?}', [ArtistController::class, 'getArtistList'])->name('artists');
    Route::get('/search/artist', [ArtistController::class, 'search'])->name('artist.search');
    Route::get('/emails', [EventController::class, 'getEventList'])->name('emails');
    Route::get('/event/create', [EventController::class, 'showCreateEvent'])->name('event.create');
    Route::get('/event/export-csv', [EventController::class, 'exportCSV'])->name('event.getCSV');
    Route::get('/event/{id}', [EventController::class, 'getEvent'])->name('event');
    Route::get('/event/createSet/{id}', [EventController::class, 'createSet'])->name('event.set.create'); // TODO:: Create controller endpoint and update me
    Route::get('/event/createApplication/{id}', [ApplicationController::class, 'showCreateApplication'])->name('event.form.create');
    Route::get('/event/updateApplication/{id}/{form}', [ApplicationController::class, 'showCreateApplication'])->name('event.form.update');
    Route::get('/event/applications/{id}/filter/{filter}/{pagenum?}', [ApplicationController::class, 'showApplications']);
    Route::get('/event/applications/{id}/sort/{sortby}/{pagenum?}', [ApplicationController::class, 'showApplications']);
    Route::get('/event/applications/{id}/filter/{filter}/sort/{sortby}/{pagenum?}', [ApplicationController::class, 'showApplications']);
    Route::get('/event/applications/{id}/search/{searchTerm}/{pagenum?}', [ApplicationController::class, 'showApplications']);
    Route::get('/event/applications/{id}/{pagenum?}', [ApplicationController::class, 'showApplications'])->name('event.applications');
    Route::get('/events/{pagenum?}', [EventController::class, 'getEventList'])->name('events');
    Route::get('/medias/{pagenum?}', [MediaController::class, 'getMediaList'])->name('medias');
    Route::get('/media/{id}', [MediaController::class, 'getMedia'])->name('media');
    Route::get('/settings', [SettingsController::class, 'getSettings'])->name('settings');
    Route::get('/sponsors/create', function () {return Inertia::render('Sponsor/Create');})->name('sponsor.create');
    Route::get('/sponsors/{pagenum?}', [SponsorController::class, 'getSponsorList'])->name('sponsors');
    Route::get('/sponsor/{id}', [SponsorController::class, 'getSponsor'])->name('sponsor');
    Route::get('/staff', [EventController::class, 'getEventList'])->name('staff');
    Route::get('/tasks/export-csv', [TaskController::class, 'exportCSV'])->name('tasks.getCSV');
    Route::get('/tasks', [TaskController::class, 'getTasks'])->name('tasks');
    Route::get('/venue/create', function () {return Inertia::render('Venue/Create');})->name('venue.create');
    Route::get('/venue/export-csv', [VenueController::class, 'exportCSV'])->name('venue.getCSV');
    Route::get('/venue/{id}', [VenueController::class, 'getVenue'])->name('venue');
    Route::get('/venue/edit/{id}', [VenueController::class, 'getVenue'])->name('venue.edit');
    Route::get('/venues/{pagenum?}', [VenueController::class, 'getVenueList'])->name('venues');
    Route::get('/volunteers/create', function () {return Inertia::render('Volunteer/Create');})->name('volunteer.create');
    Route::get('/volunteers/{pagenum?}', [VolunteerController::class, 'getVolunteerList'])->name('volunteers');
    Route::get('/volunteer/{id}', [VolunteerController::class, 'getVolunteer'])->name('volunteer');

    Route::post('/artist/apply', [ArtistController::class, 'applyForEvent'])->name('artist.apply'); // TODO:: Add prefill logic to form and use seperate update endpoint
    Route::post('/artists/import-csv', [ArtistController::class, 'importCSV'])->name('artists.putCSV');
    Route::post('/artist/create', [ArtistController::class, 'createArtist'])->name('artist.new');
    Route::post('/artist/delete/{id}', [ArtistController::class, 'destroyArtist'])->name('artist.delete');
    Route::post('/artist/rate/{id}/{via}', [ArtistController::class, 'rate'])->name('artist.rate');
    Route::post('/artist/update', [ArtistController::class, 'updateArtist'])->name('artist.update');
    Route::post('/event/applications/accept/{id}', [ApplicationController::class, 'accept'])->name('application.accept');
    Route::post('/event/applications/delete/{id}', [ApplicationController::class, 'delete'])->name('application.delete');
    Route::post('/event/applications/reject/{id}', [ApplicationController::class, 'reject'])->name('application.reject');
    Route::post('/event/applications/seen/{id}', [ApplicationController::class, 'seen'])->name('application.seen');
    Route::post('/event/applications/shortlist/{id}', [ApplicationController::class, 'shortlist'])->name('application.shortlist');
    Route::post('/event/import-csv', [EventController::class, 'importCSV'])->name('event.putCSV');
    Route::post('/event/create', [EventController::class, 'createEvent'])->name('event.new');
    Route::post('/event/delete/{id}', [EventController::class, 'destroyEvent'])->name('event.destroy');
    Route::post('/event/update', [EventController::class, 'updateEvent'])->name('event.update');
    Route::post('/event/createApplication/{id}', [ApplicationController::class, 'createOrUpdateApplication'])->name('event.form.new');
    Route::post('/event/createSet/{id}', [EventController::class, 'createSet'])->name('event.set.new'); // TODO:: Create controller endpoint and update me
    Route::post('/event/deleteApplication/{id}', [ApplicationController::class, 'deleteApplication'])->name('event.form.delete');
    Route::post('/event/publishApplication/{id}',[ApplicationController::class, 'publishApplication'])->name('event.form.publish');
    Route::post('/event/unpublishApplication/{id}',[ApplicationController::class, 'unpublishApplication'])->name('event.form.unpublish');
    Route::post('/media/create', [MediaController::class, 'createMedia'])->name('media.new');
    Route::post('/media/delete/{id}', [MediaController::class, 'destroyMedia'])->name('media.destroy');
    Route::post('/media/import-csv', [MediaController::class, 'importCSV'])->name('media.putCSV');
    Route::post('/media/update', [MediaController::class, 'updateMedia'])->name('media.update');
    Route::post('/sponsor/create', [SponsorController::class, 'createSponsor'])->name('sponsor.new');
    Route::post('/sponsor/delete/{id}', [SponsorController::class, 'destroySponsor'])->name('sponsor.destroy');
    Route::post('/sponsor/import-csv', [SponsorController::class, 'importCSV'])->name('sponsor.putCSV');
    Route::post('/sponsor/update', [SponsorController::class, 'updateSponsor'])->name('sponsor.update');
    Route::post('/settings/update', [SettingsController::class, 'updateTenantData'])->name('settings.update');
    Route::post('/tasks/import-csv', [TaskController::class, 'importCSV'])->name('tasks.putCSV');
    Route::post('/tasks/create', [TaskController::class, 'newTask'])->name('tasks.new');
    Route::post('/tasks/delete', [TaskController::class, 'deleteTask'])->name('tasks.destroy');
    Route::post('/tasks/reorder', [TaskController::class, 'listReorder'])->name('tasks.reorder');
    Route::post('/tasks/update', [TaskController::class, 'updateTask'])->name('tasks.update');
    Route::post('/venue/import-csv', [VenueController::class, 'importCSV'])->name('venue.putCSV');
    Route::post('/venue/create', [VenueController::class, 'createVenue'])->name('venue.new');
    Route::post('/venue/delete/{id}', [VenueController::class, 'destroyVenue'])->name('venue.destroy');
    Route::post('/venue/update', [VenueController::class, 'updateVenue'])->name('venue.update');
    Route::post('/volunteer/create', [VolunteerController::class, 'createVolunteer'])->name('volunteer.new');
    Route::post('/volunteer/delete/{id}', [VolunteerController::class, 'destroyVolunteer'])->name('voluteer.destroy');
    Route::post('/volunteer/import-csv', [VolunteerController::class, 'importCSV'])->name('volunteer.putCSV');
    Route::post('/volunteer/update', [VolunteerController::class, 'updateVolunteer'])->name('volunteer.update');
});
