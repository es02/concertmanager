<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Artist extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'artist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'user_id',
        'booking_agent_id', // TODO: Implement booking agent (User who manages multiple bands)
        'name',
        'email',
        'genre',
        'bio',
        'pic_url',
        'location',
        'standard_fee',
        'standard_rider',
        'tech_specs',
        'epk_url',
        'rating',
        'blacklisted',
        'notes',
        'state',
    ];
}
