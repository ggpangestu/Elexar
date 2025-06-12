<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkVideo extends Model
{
    protected $table = 'work_video';
    protected $fillable = ['title', 'video_path', 'description'];
}
