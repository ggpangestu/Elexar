<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Rescheduled = 'rescheduled';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
}
