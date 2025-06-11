<?php

namespace App\Http\Controllers;
use App\Models\Folder;

class GalleryController extends Controller
{
    public function show($id)
    {
        $folder = Folder::with('photos')->findOrFail($id);

        return response()->json([
            'folder' => [
                'id' => $folder->id,
                'name' => $folder->name,
            ],
            'photos' => $folder->photos->map(function ($photo) {
                return [
                    'name' => basename($photo->path_photo)
                ];
            })
        ]);
    }
}
