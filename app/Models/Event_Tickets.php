<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Tickets extends Model
{
    use HasFactory;

    protected $table = 'event_tickets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'event_id',
        'event_ticket_id',
        'tickets_sold',
        'tickets_resold',
        'tickets_refunded',
        'state',
    ];
}
