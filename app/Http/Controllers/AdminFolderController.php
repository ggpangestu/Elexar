<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class AdminFolderController extends Controller
{
    public function index()
    {
        $folders = Folder::withCount('photos')->latest()->paginate(100);
        return view('admin.folders.index', compact('folders'));
    }

    public function create()
    {
        return view('admin.folders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'background_folder' => 'nullable|image|max:30720', // max 30MB
        ]);

        $data = $request->only('name');

        if ($request->hasFile('background_folder')) {
            $data['background_folder'] = $request->file('background_folder')->store('backgrounds', 'public');
        }

        Folder::create($data);

        return redirect()->route('admin.folders.index')->with('success', 'Folder created successfully.');
    }

    public function edit(Folder $folder)
    {
        return view('admin.folders.edit', compact('folder'));
    }

    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'background_folder' => 'nullable|image|max:30720',
        ]);

        $data = $request->only('name');

        if ($request->hasFile('background_folder')) {
            $data['background_folder'] = $request->file('background_folder')->store('backgrounds', 'public');
        }

        $folder->update($data);

        return redirect()->route('admin.folders.index')->with('success', 'Folder updated successfully.');
    }

    public function delete(Folder $folder)
    {
        $folder->delete();

        return redirect()->route('admin.folders.index')->with('success', 'Folder deleted successfully.');
    }
}
