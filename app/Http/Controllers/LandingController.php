<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingImage;
use App\Models\WorkCarousel;
use App\Models\WorkVideo;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        $section = LandingImage::first();
        $carousel = WorkCarousel::orderBy('order')->get();
        $video = WorkVideo::first();
        $folders = Folder::with(['photos' => function ($q) {
            $q->where('is_display', true)->take(3);
        }])->get();

        
        return view('users.landing', compact('section', 'carousel', 'video', 'folders'));
    }
}
