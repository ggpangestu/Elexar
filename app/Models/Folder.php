<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'background_folder'];

    public function photos()
    {
        return $this->hasMany(Photo::class)->orderBy('order'); // otomatis urut berdasarkan order
    }
}
