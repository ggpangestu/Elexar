<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\BookingStatus;
use App\Enums\UserResponseStatus;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'booking_date_time',
        'category',
        'notes',
        'status',
        'reschedule_token',
        'reschedule_expires_at',
        'reschedule_reason',
        'user_response_status',
        'user_responded_at',
        'pending_meeting_link',
        'meeting_link',
        'meeting_link_note',
    ];

    protected $casts = [
        'status' => BookingStatus::class,
        'user_response_status' => UserResponseStatus::class,
        'booking_date_time' => 'datetime',
        'reschedule_expires_at' => 'datetime',
        'user_responded_at' => 'datetime',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
