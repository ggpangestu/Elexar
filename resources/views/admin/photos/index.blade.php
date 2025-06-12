@extends('layouts.admin')

@section('title', 'ADMIN FOLDERS-PHOTO')

@section('content')
<div x-data="photoManager()" x-cloak class="p-4 text-gray-800 dark:text-gray-100 min-h-screen">

    {{-- Header --}}
    <div class="max-w-8xl mx-auto bg-white dark:bg-gray-800 rounded-t-xl shadow p-6 flex items-center justify-between">
        <div class="flex items-center gap-4 mt-2">
            <a href="{{ route('admin.folders.index') }}"
            class="text-sm px-3 py-2 mt-2 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition text-gray-700 dark:text-gray-100">
                <i class="ph-fill ph-arrow-u-up-left text-black dark:text-white text-2xl"></i>
            </a>
            <h2 class="text-4xl font-bold">{{ $folder->name }}</h2>
        </div>

        <div class="flex gap-2">
            <button @click="openUpload()" 
                    :disabled="editMode || deleteMode"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                + Tambah Foto
            </button>
            <button @click="toggleEditMode()" 
                    :disabled="deleteMode"
                    class="px-4 py-2 rounded text-white transition disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="editMode ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-yellow-500 hover:bg-yellow-600'">
                Edit Urutan
            </button>
            <button @click="toggleDeleteMode()" 
                    :disabled="editMode"
                    class="px-4 py-2 rounded text-white transition disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="deleteMode ? 'bg-red-700 hover:bg-red-800' : 'bg-red-600 hover:bg-red-700'">
                Hapus Foto
            </button>
        </div>
    </div>
    
    <div class="max-w-8xl mx-auto border-t border-gray-300 dark:border-gray-600"></div>

    {{-- Grid Foto --}}
    <div class="max-w-8xl mx-auto bg-white dark:bg-gray-800 rounded-b-xl shadow p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <template x-for="(photo, index) in photos" :key="photo.id">
            <div 
                class="relative group rounded-xl overflow-hidden shadow transition hover:scale-105"
                :class="[(editMode || deleteMode) ? 'opacity-70' : 'opacity-100', editMode ? 'cursor-move' : '']"
                :draggable="editMode"
                @dragstart="editMode && dragStart(index)"
                @dragover.prevent
                @drop="editMode && drop(index)"
            >
                <img :src="photo.url" alt="Photo" class="w-full h-32 object-cover" @click="openPreview(index)">

                {{-- Checkbox is_display --}}
                <div
                    x-show="!editMode && !deleteMode"
                    class="absolute top-1 right-1 bg-opacity-80 rounded-full p-1 opacity-0 group-hover:opacity-100 transition">
                    <input type="checkbox"
                        :checked="photo.is_display"
                        @change="toggleDisplay(photo.id, $event)"
                        class="form-checkbox h-5 w-5 text-blue-600"
                        title="Tampilkan/ Sembunyikan Foto">
                </div>

                {{-- Hapus --}}
                <button
                    x-show="deleteMode"
                    @click="deletePhoto(photo.id)"
                    class="absolute inset-0 flex items-center justify-center bg-red-500 bg-opacity-30 hover:bg-opacity-70 transition"
                >
                    <i class="ph-fill ph-trash text-white text-4xl"></i>
                </button>

                {{-- Urutan --}}
                <div
                    x-show="editMode"
                    class="absolute top-1 left-1 bg-yellow-500 text-white text-xs px-2 py-1 rounded">
                    Urutan: <span x-text="index + 1"></span>
                </div>
            </div>
        </template>

        <template x-if="photos.length === 0">
            <p class="col-span-full text-center text-gray-500 dark:text-gray-400">Belum ada foto.</p>
        </template>
    </div>

    {{-- Modal Upload --}}
    <div
        x-show="showUpload"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div
            x-show="showUpload"
            x-transition.scale
            @click.away="closeUpload()"
            class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md shadow-lg"
        >
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Upload Foto</h3>
            <form method="POST" action="{{ route('admin.photos.store', $folder->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="file" name="photo[]" multiple accept="image/*"
                       class="w-full px-3 py-2 rounded border dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="closeUpload()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Upload</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Preview Gambar --}}
    <div
        x-show="previewPhoto !== null"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50"
    >
        <button @click="prevPhoto()" class="absolute left-10 top-1/2 -translate-y-1/2 text-white text-4xl z-10">&lt;</button>
        <div class="relative w-full max-w-4xl">
            <button @click="previewPhoto = null" class="absolute top-2 right-2 text-white text-3xl z-10">&times;</button>
            <img :src="photos[previewIndex]?.url" class="mx-auto max-h-[90vh] rounded shadow-lg">
        </div>
        <button @click="nextPhoto()" class="absolute right-10 top-1/2 -translate-y-1/2 text-white text-4xl z-10">&gt;</button>
    </div>
</div>

<script>
function photoManager() {
    return {
        showUpload: false,
        editMode: false,
        deleteMode: false,
        dragIndex: null,
        previewPhoto: null,
        previewIndex: null,

        @php
            $photosData = collect($photos)->map(function ($p) {
                return [
                    'id' => $p->id,
                    'url' => asset('storage/' . $p->path_photo),
                    'is_display' => (bool) $p->is_display,
                ];
            })->values();
        @endphp

        photos: @json($photosData),

        openUpload() {
            if (this.editMode || this.deleteMode) return;
            this.showUpload = true;
        },
        closeUpload() {
            this.showUpload = false;
        },

        toggleEditMode() {
            this.editMode = !this.editMode;
            if (this.editMode) {
                this.deleteMode = false;
                this.showUpload = false;
            }
        },
        toggleDeleteMode() {
            this.deleteMode = !this.deleteMode;
            if (this.deleteMode) {
                this.editMode = false;
                this.showUpload = false;
            }
        },

        dragStart(index) {
            this.dragIndex = index;
        },
        drop(index) {
            if (!this.editMode) return;
            const moved = this.photos.splice(this.dragIndex, 1)[0];
            this.photos.splice(index, 0, moved);
            this.dragIndex = null;
        },

        deletePhoto(id) {
            Swal.fire({
                title: 'Hapus Foto?',
                text: 'Foto ini akan dihapus secara permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/photos/${id}`;
                    form.innerHTML = `@csrf<input type="hidden" name="_method" value="DELETE">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        },

        openPreview(index) {
            if (!this.editMode && !this.deleteMode) {
                this.previewIndex = index;
                this.previewPhoto = this.photos[index];
            }
        },
        nextPhoto() {
            if (this.previewIndex !== null && this.previewIndex < this.photos.length - 1) {
                this.previewIndex++;
                this.previewPhoto = this.photos[this.previewIndex];
            }
        },
        prevPhoto() {
            if (this.previewIndex !== null && this.previewIndex > 0) {
                this.previewIndex--;
                this.previewPhoto = this.photos[this.previewIndex];
            }
        },

        toggleDisplay(id, event) {
            const photo = this.photos.find(p => p.id === id);
            if (!photo) return;

            const isCurrentlyChecked = photo.is_display;
            const displayedCount = this.photos.filter(p => p.is_display).length;

            const tryingToEnable = !isCurrentlyChecked;

            // Kalau mau menambahkan tapi sudah 3 foto yang tampil
            if (tryingToEnable && displayedCount >= 3) {
                // Kembalikan checkbox ke posisi sebelumnya
                event.target.checked = false;

                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Tercapai',
                    text: 'Maksimal 3 foto yang ditampilkan.',
                    confirmButtonColor: '#3b82f6',
                });

                return;
            }

            fetch(`/admin/photos/${id}/toggle-display`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ is_display: tryingToEnable })
            }).then(response => {
                if (response.ok) {
                    photo.is_display = tryingToEnable;
                } else {
                    event.target.checked = isCurrentlyChecked; // rollback checkbox
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memperbarui status tampil.',
                        confirmButtonColor: '#ef4444',
                    });
                }
            }).catch(() => {
                event.target.checked = isCurrentlyChecked; // rollback checkbox
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan',
                    text: 'Tidak dapat terhubung ke server.',
                    confirmButtonColor: '#ef4444',
                });
            });
        }
    }
}
</script>
@endsection
