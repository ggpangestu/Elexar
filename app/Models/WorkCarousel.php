<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkCarousel extends Model
{
    protected $table = 'work_carousel';
    protected $fillable = ['image_path', 'order'];
}
