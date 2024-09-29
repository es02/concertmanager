<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Application_Parent extends Model
{
    use HasFactory;

    protected $table = 'event_application_parent';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'application_id',
        'shortlisted',
        'accepted',
    ];
}
