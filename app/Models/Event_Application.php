<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Application extends Model
{
    use HasFactory;

    protected $table = 'event_application';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'event_id',
        'name',
        'description',
        'open',
        'close',
        'type',
        'published',
        'state',
    ];
}
