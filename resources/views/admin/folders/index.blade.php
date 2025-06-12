@extends('layouts.admin')

@section('title', 'ADMIN FOLDERS')

@section('content')
<div x-data="folderManager()" x-cloak class="p-4 text-gray-800 dark:text-gray-100 min-h-screen">

    {{-- Header + Controls --}}
    <div class="max-w-8xl mx-auto bg-white dark:bg-gray-800 rounded-t-xl shadow p-8 flex items-center justify-between gap-4">
        <h2 class="text-2xl font-bold">Folder Portofolio</h2>
        
        <div class="flex items-center gap-4">
            <button @click="openCreate()" 
                :disabled="deleteMode || editMode"
                class="bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-4 py-2 rounded hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-400">
                + Tambah Folder
            </button>

            <button 
                @click="toggleEditMode()" 
                :disabled="deleteMode"
                :class="editMode ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-yellow-500 hover:bg-yellow-600'"
                class="text-white px-4 py-2 rounded transition focus:outline-none focus:ring-2 focus:ring-yellow-400 disabled:opacity-50 disabled:cursor-not-allowed">
                Edit Folder
            </button>

            <button 
                @click="toggleDeleteMode()"
                :disabled="editMode"
                :class="deleteMode ? 'bg-red-700 hover:bg-red-800' : 'bg-red-600 hover:bg-red-700'" 
                class="text-white px-4 py-2 rounded transition focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                Hapus Folder
            </button>
        </div>
    </div>

    <div class="max-w-8xl mx-auto border-t border-gray-300 dark:border-gray-600"></div>

    {{-- Folder Grid --}}
    <div class="max-w-8xl mx-auto bg-white dark:bg-gray-800 rounded-b-xl shadow p-5 flex flex-wrap items-center justify-start gap-4">

        @foreach ($folders as $folder)
        <div 
            class="relative group cursor-pointer flex flex-col items-center ml-2 p-6 bg-white dark:bg-gray-900 rounded-xl shadow
            transition transform hover:scale-105 aspect-square w-32"
            :class="(deleteMode || editMode) ? 'opacity-70' : 'opacity-100'"
            @mouseenter="hoveredFolderId = {{ $folder->id }}"
            @mouseleave="hoveredFolderId = null"
        >
            {{-- Icon Folder dengan animasi buka tutup --}}
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-16 h-16 mb-2 text-yellow-400 transition-transform duration-300"
                :class="hoveredFolderId === {{ $folder->id }} ? 'rotate-6 scale-110' : 'rotate-0 scale-100'"
                fill="currentColor"
                viewBox="0 0 24 24"
            >
                <path d="M2 6a2 2 0 0 1 2-2h6l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6z"/>
            </svg>

            {{-- Nama Folder --}}
            <span class="text-center text-sm font-medium truncate w-full whitespace-nowrap" title="{{ $folder->name }}">
                {{ $folder->name }}
            </span>

            {{-- Overlay untuk tombol, muncul saat hover mode normal --}}
            <a href="{{ route('admin.photos.index', $folder->id) }}"
            x-show="hoveredFolderId === {{ $folder->id }} && !deleteMode && !editMode"
            x-transition.opacity
            class="absolute inset-0 flex items-center justify-center rounded-xl"
            style="background-color: rgba(34,197,94,0.3);"
            @mouseenter="$el.style.backgroundColor='rgba(34,197,94,0.6)'"
            @mouseleave="$el.style.backgroundColor='rgba(34,197,94,0.3)'"
            title=title="{{ $folder->name }}"
            >
                {{-- Icon gambar --}}
                <i class="ph-fill ph-file-plus text-5xl text-black"></i>
            </a>

            {{-- Overlay untuk tombol delete --}}
            <button
                x-show="deleteMode && hoveredFolderId === {{ $folder->id }}"
                x-transition.opacity
                @click="deleteFolder({{ $folder->id }})"
                class="absolute inset-0 flex items-center justify-center rounded-xl text-white"
                style="background-color: rgba(220,38,38,0.3);"
                @mouseenter="$el.style.backgroundColor='rgba(220,38,38,0.6)'"
                @mouseleave="$el.style.backgroundColor='rgba(220,38,38,0.3)'"
                title="Hapus Folder"
            >
                {{-- Icon trash --}}
                <i class="ph-fill ph-trash text-5xl text-black"></i>
            </button>

            {{-- Overlay untuk tombol edit --}}
            <button
                x-show="editMode && hoveredFolderId === {{ $folder->id }}"
                x-transition.opacity
                @click="openEdit({{ $folder->id }}, '{{ addslashes($folder->name) }}')"
                class="absolute inset-0 flex items-center justify-center rounded-xl text-white"
                style="background-color: rgba(202,138,4,0.3);"
                @mouseenter="$el.style.backgroundColor='rgba(202,138,4,0.6)'"
                @mouseleave="$el.style.backgroundColor='rgba(202,138,4,0.3)'"
                title="Edit Folder"
            >
                {{-- Icon edit --}}
                <i class="ph-fill ph-pencil text-5xl text-black"></i>
            </button>
        </div>
        @endforeach

        @if ($folders->count() === 0)
            <p class="col-span-full text-center text-gray-500 dark:text-gray-400 mt-6">Belum ada folder yang dibuat.</p>
        @endif
    </div>

    {{-- Modal Tambah Folder --}}
    <div
        x-show="showCreate"
        x-transition.opacity.duration.300ms
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div
            @click.away="closeCreate()"
            x-show="showCreate"
            x-transition.scale.duration.300ms.origin.center
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-md transform transition-all"
        >
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Tambah Folder</h3>
            <form method="POST" action="{{ route('admin.folders.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-1">Nama Folder</label>
                    <input type="text" name="name" required
                           class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-400">
                </div>
                <div>
                    <label class="block mb-1">Background (Opsional)</label>
                    <input type="file" name="background_folder" accept="image/*"
                           class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="closeCreate()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Folder --}}
    <div
        x-show="showEdit"
        x-transition.opacity.duration.300ms
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]"
    >
        <div
            @click.away="closeEdit()"
            x-show="showEdit"
            x-transition.scale.duration.300ms.origin.center
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-md transform transition-all"
        >
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Edit Folder</h3>
            <form :action="updateActionUrl" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block mb-1">Nama Folder</label>
                    <input type="text" name="name" required x-model="editName"
                           class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-yellow-400">
                </div>
                <div>
                    <label class="block mb-1">Background (Opsional)</label>
                    <input type="file" name="background_folder" accept="image/*"
                           class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="closeEdit()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Batal</button>
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function folderManager() {
    return {
        showCreate: false,
        showEdit: false,
        editId: null,
        editName: '',
        hoveredFolderId: null,

        deleteMode: false,
        editMode: false,

        get updateActionUrl() {
            return `/admin/folders/${this.editId}`;
        },

        openCreate() {
            if(this.deleteMode || this.editMode) return;
            this.showCreate = true;
            this.showEdit = false;
        },
        closeCreate() {
            this.showCreate = false;
        },

        openEdit(id, name) {
            if(this.deleteMode) return;
            this.editId = id;
            this.editName = name;
            this.showEdit = true;
            this.showCreate = false;
        },
        closeEdit() {
            this.showEdit = false;
            this.editId = null;
            this.editName = '';
        },

        toggleDeleteMode() {
            this.deleteMode = !this.deleteMode;
            if(this.deleteMode) {
                this.editMode = false;
                this.showCreate = false;
                this.showEdit = false;
            }
        },
        
        toggleEditMode() {
            this.editMode = !this.editMode;
            if(this.editMode) {
                this.deleteMode = false;
                this.showCreate = false;
                this.showEdit = false;
            }
        },

        deleteFolder(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus folder ini?',
                text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626', 
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                scrollbarPadding: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form secara dinamis untuk delete folder
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/folders/${id}`;
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    }
}
</script>
@endsection
