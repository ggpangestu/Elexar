<?php

namespace App\Http\Controllers;
use App\Models\LandingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAboutSectionController extends Controller
{
    public function showForm()
    {
        $section = LandingImage::first();
        return view('admin.layout.about', compact('section'));
    }

    // Proses upload dan timpa gambar lama
    public function upload(Request $request)
    {
        $request->validate([
            'about_img' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:30720',
            'about_text' => 'nullable|string',
        ]);

        $section = LandingImage::first() ?? new LandingImage();

        // Cek apakah ada perubahan yang berarti
        $inputText = $request->input('about_text') ?? '';
        $oldText = $section->about_text ?? '';

        if (
            !$request->hasFile('about_img') &&
            (trim($inputText) === '' || trim($inputText) === trim($oldText))
        ) {
            return redirect()->route('admin.layout.about')->with('nochange', 'Tidak ada perubahan yang disimpan.');
        }

        if ($request->hasFile('about_img')) {
            // Hapus gambar lama jika ada
            if ($section->about_img && Storage::disk('public')->exists($section->about_img)) {
                Storage::disk('public')->delete($section->about_img);
            }

            // Simpan file baru
            $path = $request->file('about_img')->store('about', 'public');
            $section->about_img = $path;
        }

        $section->about_text = $request->input('about_text');
        $section->save();

        return redirect()->route('admin.layout.about')->with('success', 'Berhasil diperbarui.');
    }
}
