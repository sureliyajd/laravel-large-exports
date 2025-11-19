<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    /** @use HasFactory<\Database\Factories\RegistrationFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
        'event_date' => 'date',
        'payment_date' => 'datetime',
        'checked_in_at' => 'datetime',
        'badge_printed_at' => 'datetime',
        'last_contacted_at' => 'datetime',
        'archived_at' => 'datetime',
        'custom_fields' => 'array',
        'marketing_opt_in' => 'boolean',
        'sms_opt_in' => 'boolean',
        'email_opt_in' => 'boolean',
        'check_in_status' => 'boolean',
    ];
}
