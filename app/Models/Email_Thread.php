<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email_Thread extends Model
{
    use HasFactory;

    protected $table = 'email_thread';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'email_account_id',
        'api_id', // what does gmail think the id of this email is?
        'subject',
    ];
}
