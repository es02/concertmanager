<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Application_Entry extends Model
{
    use HasFactory;

    protected $table = 'event_application_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'event_id',
        'event_application_id',
        'event_application_field_id',
        'value',
        'state',
    ];
}
