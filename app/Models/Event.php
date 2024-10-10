<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'venue_id',
        'name',
        'description',
        'start',
        'end',
        'ticketing_provider', // eg: Oztix, Ticketmaster, Eventbrite, Humanitix, etc
        'free',
        'all_ages',
        'event_pic_url',
        'ticket_url',
        'location',
        'state',
    ];
}
