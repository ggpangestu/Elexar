<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingImage extends Model
{
    protected $fillable = [
        'home_img',
        'about_img',
        // tambahkan kolom lain seperti perlu: 'work_img', dll
    ];
}
