<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Set extends Model
{
    use HasFactory;

    protected $table = 'event_set';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'event_id',
        'venue_id',
        'event_stage_id',
        'artist_id',
        'name',
        'time',
        'duration',
        'sideshow',
        'state',
    ];
}
