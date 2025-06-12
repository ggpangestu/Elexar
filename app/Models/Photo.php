<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['folder_id', 'path_photo', 'order', 'is_display'];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}