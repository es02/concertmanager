<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Application_Field extends Model
{
    use HasFactory;

    protected $table = 'event_application_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'event_id',
        'event_application_id',
        'name',
        'expected_value',
        'mapped_value',
        'mandatory',
        'state',
    ];
}
