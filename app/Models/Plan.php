<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'period',       // Monthly or Annual
        'trial',
        'trial_length', // Measured in days
        'published',    // Do we surface this to customers?
        'default',      // If no plan is selected should a customer be placed on this one?
        'state',
    ];
}
