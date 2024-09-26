<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Ticket_Type extends Model
{
    use HasFactory;

    protected $table = 'event_ticket_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'event_id',
        'event_ticket_type',
        'ticket_price',
        'free',
        'allocation',
        'state',
        'state',
    ];
}
