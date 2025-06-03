<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingImage;

class LandingController extends Controller
{
    public function index()
    {
        $section = LandingImage::first();
        return view('users.landing', compact('section'));
    }
}
