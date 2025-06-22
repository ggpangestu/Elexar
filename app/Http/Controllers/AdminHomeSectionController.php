<?php

namespace App\Http\Controllers;
use App\Models\LandingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHomeSectionController extends Controller
{
    public function showForm()
    {
        $section = LandingImage::first(); // Ambil data pertama, atau null
        return view('admin.layout.home', compact('section'));
    }

    // Proses upload dan timpa gambar lama
    public function upload(Request $request)
    {
        $request->validate([
            'home_img' => 'required|image|mimes:jpeg,png,jpg,webp|max:30720',
        ]);

        $section = LandingImage::first() ?? new LandingImage();

        // Hapus file lama kalau ada
        if ($section->home_img && Storage::disk('public')->exists($section->home_img)) {
            Storage::disk('public')->delete($section->home_img);
        }

        // Simpan file baru
        $path = $request->file('home_img')->store('home', 'public');
        $section->home_img = $path;
        $section->save();

        return redirect()->route('admin.layout.home')->with('success', 'Gambar berhasil diperbarui.');
    }
}
