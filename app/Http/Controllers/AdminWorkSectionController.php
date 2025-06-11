<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkCarousel;
use App\Models\WorkVideo;
use Illuminate\Support\Facades\Storage;

class AdminWorkSectionController extends Controller
{
    /**
     * Tampilkan halaman admin untuk mengelola carousel dan video.
     */
    public function showForm()
    {
        // Ambil semua carousel, urut berdasarkan order
        $carousel = WorkCarousel::orderBy('order')->get();

        // Ambil video pertama (anggap hanya 1 video untuk work section)
        $video = WorkVideo::first();

        // Kirim data ke view
        return view('admin.layout.work', compact('carousel', 'video'));
    }

    /**
     * Proses upload gambar carousel (bisa multiple).
     */
    public function storeCarousel(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:30720',
        ]);

        if ($request->hasFile('images')) {
            // Mulai dari order tertinggi + 1 untuk gambar baru
            $lastOrder = WorkCarousel::max('order') ?? 0;

            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('work/carousel', 'public');

                WorkCarousel::create([
                    'image_path' => $path,
                    'order' => $lastOrder + 1 + $index,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Gambar carousel berhasil ditambahkan.');
    }

    public function deleteCarousel($id)
    {
        $item = WorkCarousel::findOrFail($id);
        if (Storage::disk('public')->exists($item->image_path)) {
            Storage::disk('public')->delete($item->image_path);
        }
        $item->delete();
        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    public function reorderCarousel(Request $request)
    {
        $order = explode(',', $request->input('order'));
        foreach ($order as $index => $id) {
            WorkCarousel::where('id', $id)->update(['order' => $index + 1]);
        }
        return back()->with('success', 'Urutan carousel diperbarui.');
    }

    /**
     * Proses upload/update video dan deskripsi work.
     */
    public function storeVideo(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'video' => 'nullable|mimes:mp4,mov,webm|max:102400', // max 100MB
            'description' => 'nullable|string|max:1000',
        ]);

        $video = WorkVideo::first() ?? new WorkVideo();

        if ($request->hasFile('video')) {
            // Hapus video lama jika ada
            if ($video->video_path && Storage::disk('public')->exists($video->video_path)) {
                Storage::disk('public')->delete($video->video_path);
            }

            $path = $request->file('video')->store('work/video', 'public');
            $video->video_path = $path;
        }

        // Simpan title dan deskripsi jika ada
        $video->title = $request->input('title');
        $video->description = $request->input('description');
        $video->save();

        return redirect()->back()->with('success', 'Video berhasil diperbarui.');
    }
}
