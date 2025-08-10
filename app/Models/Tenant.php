<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $table = 'tenant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'fqdn',           // expected to auto fill as tenant_name.master_domain.com
        'plan_id',
        'payment_token',  // Stripe token for plan payment
        // 'welcome_title',
        // 'welcome_text',
        // 'show_welcome',
        // 'show_event_links',
        // 'dashboard_event_limit',
        // 'show_blog',
        // 'dashboard_post_limit',
        'state',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'payment_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payment_token' => 'hashed',
        ];
    }
}
