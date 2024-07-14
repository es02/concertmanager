<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Venue extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'venue';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'user_id',
        'booking_agent_id', // TODO: Implement booking agent (single login to manage multiple bands)
        'venue_name',
        'email',
        'bio',
        'pic_url',
        'location',
        'capacity',
        'standard_fee',     // in dollars - may not match neotiated fee for an event
        'ticket_cut',       // in dollars
        'fee_type',         // Is this a total price, or a minimum backed with a ticket cut?
        'cut_type',         // If a ticket cut, is it per ticket cost, or a pewrcentage?
        'additional_fees',  // eg: sound/lighting tech, door person, backline, etc
        'tech_specs',
        'backline',         // if provided
        'state',
    ];
}
