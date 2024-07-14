<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Stage extends Model
{
    use HasFactory;

    protected $table = 'event_stage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'event_id',
        'venue_id',
        'stage_name',
        'bio',
        'doors', // Expected to open/close at the same time as an event
        'close', // But if it's a second/third stage this may not be the case
        'state',
    ];
}
