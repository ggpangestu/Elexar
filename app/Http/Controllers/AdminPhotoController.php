<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPhotoController extends Controller
{
    // Tampilkan daftar foto berdasarkan folder
    public function index(Folder $folder)
    {
        // ambil semua foto folder, sudah di order berdasarkan 'order' di model Folder
        $photos = $folder->photos;

        return view('admin.photos.index', compact('folder', 'photos'));
    }

    // Upload foto baru ke folder
    public function store(Request $request, Folder $folder)
    {
        $request->validate([
            'photo.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:30720', // max 30MB
            'photo' => 'required|array|min:1',
        ]);

        $photos = $request->file('photo'); // ini adalah array of UploadedFile
        $lastOrder = $folder->photos()->max('order') ?? 0;

        foreach ($photos as $index => $file) {
            $path = $file->store('photos', 'public'); // pastikan ini dipanggil pada $file, bukan $photos

            $folder->photos()->create([
                'path_photo' => $path,
                'order' => $lastOrder + $index + 1,
                'is_display' => false,
            ]);
        }

        return redirect()->route('admin.photos.index', $folder->id)
                        ->with('success', 'Photo(s) uploaded successfully.');
    }

    // Update foto (misal untuk order atau is_display toggle)
    public function update(Request $request, Photo $photo)
    {
        $request->validate([
            'order' => 'nullable|integer|min:0',
            'is_display' => 'nullable|boolean',
        ]);

        $photo->update($request->only('order', 'is_display'));

        return response()->json(['success' => true]);
    }

    // Hapus foto dan file di storage
    public function delete(Photo $photo)
    {
        // hapus file foto
        if (Storage::exists($photo->path_photo)) {
            Storage::delete($photo->path_photo);
        }

        $photo->delete();

        return redirect()->back()->with('success', 'Photo deleted successfully.');
    }

    // Update urutan foto setelah drag & drop
    public function reorder(Request $request, Folder $folder)
    {
        $orders = $request->input('orders'); // contoh: [photo_id => order, ...]

        foreach ($orders as $photoId => $order) {
            $folder->photos()->where('id', $photoId)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleDisplay(Photo $photo, Request $request)
    {
        $folder = $photo->folder;
        $maxDisplayed = 3;

        // Hitung total foto dengan is_display = true di folder ini
        $countDisplayed = $folder->photos()->where('is_display', true)->count();

        // Jika ingin mengaktifkan, cek batas maksimal
        if ($request->is_display && !$photo->is_display && $countDisplayed >= $maxDisplayed) {
            return response()->json(['message' => 'Maksimal 3 foto yang ditampilkan.'], 422);
        }

        $photo->is_display = $request->is_display;
        $photo->save();

        return response()->json(['success' => true]);
    }
}
