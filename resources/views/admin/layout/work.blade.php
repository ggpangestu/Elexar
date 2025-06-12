@extends('layouts.admin')

@section('title', 'ADMIN LAYOUTS-WORK')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-10">

    {{-- Preview Carousel --}}
    <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow p-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white">Preview Carousel</h2>
        <div class="relative w-full aspect-video overflow-hidden rounded-xl border dark:border-gray-600 shadow-lg">
            <img id="previewA" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out opacity-100 z-10" />
            <img id="previewB" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out opacity-0 z-0" />
        </div>
    </section>

    {{-- Daftar Gambar Carousel --}}
    <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow p-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white">Kelola Gambar Carousel</h2>
        <ul id="sortable" class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($carousel as $item)
                <li data-id="{{ $item->id }}" class="border rounded overflow-hidden relative group">
                    <img src="{{ asset('storage/'.$item->image_path) }}" class="w-full h-48 object-cover">
                    <form action="{{ route('admin.carousel.delete', $item->id) }}" method="POST" class="absolute top-2 right-2">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-2 py-1 text-xs rounded opacity-0 group-hover:opacity-100 transition">Hapus</button>
                    </form>
                </li>
            @endforeach
        </ul>
        <form id="orderForm" action="{{ route('admin.carousel.reorder') }}" method="POST" class="mt-4">
            @csrf
            <input type="hidden" name="order" id="orderInput">
            {{-- Submit Button --}}
            <button
                type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700
                    text-sm font-medium px-6 py-2 rounded-full shadow transition
                    focus:outline-none focus:ring-2 focus:ring-offset-2
                    focus:ring-gray-400 dark:focus:ring-offset-gray-900
                    transform hover:scale-105 duration-150">
                Simpan Perubahan
            </button>
        </form>
    </section>

    {{-- Upload Gambar --}}
    <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow p-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white">Upload Gambar Carousel (Max 5 gambar)</h2>
        <form action="{{ route('admin.layout.work.storeCarousel') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateUpload()" class="space-y-4">
            @csrf
            <input type="hidden" id="maxImages" value="{{ 5 - $carousel->count() }}">
            <input type="hidden" id="maxSize" value="150">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Gambar</label>
                <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="w-full p-2 border rounded dark:bg-gray-900 dark:text-white dark:border-gray-600">
            </div>
            {{-- Submit Button --}}
            <button
                type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700
                    text-sm font-medium px-6 py-2 rounded-full shadow transition
                    focus:outline-none focus:ring-2 focus:ring-offset-2
                    focus:ring-gray-400 dark:focus:ring-offset-gray-900
                    transform hover:scale-105 duration-150">
                Upload
            </button>
        </form>
    </section>

    {{-- Preview Video --}}

    <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg p-6 mt-6">
        <div class="relative z-20">
            <div class="flex flex-col md:flex-row items-start gap-8">
                <!-- Video Preview -->
                <div class="w-full md:w-1/2 aspect-video bg-black border border-white/10 rounded-xl overflow-hidden shadow-md">
                    @if($video && $video->video_path)
                        <video id="portfolioVideo"
                            class="w-full h-full object-contain"
                            src="{{ asset('storage/' . $video->video_path) }}"
                            playsinline
                            muted
                            preload="auto"
                            controls
                            controlsList="nodownload"
                            loop>
                        </video>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400 text-center p-4">
                            Belum ada video
                        </div>
                    @endif
                </div>

                <!-- Info Preview -->
                <div class="w-full md:w-1/2 text-gray-800 dark:text-white space-y-4">
                    <h3 id="previewTitle" class="text-3xl md:text-4xl font-bold leading-tight">
                        {{ $video->title ?? 'Judul Video' }}
                    </h3>
                    <p id="previewDescription" class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        {{ $video->description ?? 'Deskripsi video belum tersedia.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Upload Video --}}
    <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow p-6 mt-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white">Upload Video Baru (Max 100MB, mp4/webm)</h2>
        <form action="{{ route('admin.layout.work.storeVideo') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateVideoUpload()" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul Video</label>
                <input type="text" name="title" required class="w-full p-2 border rounded dark:bg-gray-900 dark:text-white dark:border-gray-600" placeholder="Masukkan judul video">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi Video</label>
                <textarea name="description" rows="3" class="w-full p-2 border rounded dark:bg-gray-900 dark:text-white dark:border-gray-600" placeholder="Masukkan deskripsi video"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Video</label>
                <input type="file" name="video" id="videoInput" accept="video/mp4,video/webm" class="w-full p-2 border rounded dark:bg-gray-900 dark:text-white dark:border-gray-600" required>
            </div>
            {{-- Submit Button --}}
            <button
                type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700
                    text-sm font-medium px-6 py-2 rounded-full shadow transition
                    focus:outline-none focus:ring-2 focus:ring-offset-2
                    focus:ring-gray-400 dark:focus:ring-offset-gray-900
                    transform hover:scale-105 duration-150">
                Simpan Perubahan
            </button>
        </form>
    </section>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    // Drag & drop
    $('#sortable').sortable();
    $('#orderForm').on('submit', function () {
        const order = $('#sortable').sortable('toArray', { attribute: 'data-id' });
        $('#orderInput').val(order.join(','));
    });

    // Preview Slider
    const images = @json($carousel->map(fn($c) => asset('storage/' . $c->image_path)));
    const imgA = document.getElementById("previewA");
    const imgB = document.getElementById("previewB");
    let current = 0;
    let showingA = true;
    if (images.length > 0) {
        imgA.src = images[0];
        imgB.style.opacity = 0;
        setInterval(() => {
            const next = (current + 1) % images.length;
            crossFade(next);
        }, 5000);
    }
    function crossFade(nextIndex) {
        if (showingA) {
            imgB.src = images[nextIndex];
            imgB.style.zIndex = 20;
            imgA.style.zIndex = 10;
            imgB.style.opacity = 1;
            imgA.style.opacity = 0;
        } else {
            imgA.src = images[nextIndex];
            imgA.style.zIndex = 20;
            imgB.style.zIndex = 10;
            imgA.style.opacity = 1;
            imgB.style.opacity = 0;
        }
        current = nextIndex;
        showingA = !showingA;
    }

    // Validasi upload

    function validateUpload() {
        const files = document.getElementById('imageInput').files;
        const maxImages = parseInt(document.getElementById('maxImages').value);
        const maxTotalSize = parseInt(document.getElementById('maxSize').value) * 1024 * 1024;
        const maxPerFileSize = 30 * 1024 * 1024; 
        if (files.length > maxImages) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: `Maksimal ${maxImages} gambar yang bisa ditambahkan.`,
            });
            return false;
        }
        let total = 0;
        for (let i = 0; i < files.length; i++) {
            if (files[i].size > maxPerFileSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran Terlalu Besar',
                    text: `Gambar "${files[i].name}" melebihi 30MB.`,
                });
                return false;
            }
            total += files[i].size;
        }

        if (total > maxTotalSize) {
            Swal.fire({
                icon: 'error',
                title: 'Ukuran Total Terlalu Besar',
                text: `Total ukuran gambar melebihi batas ${maxTotalSize / (1024 * 1024)} MB.`,
            });
            return false;
        }

        Swal.fire({
            title: 'Mengunggah...',
            text: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        return true;

        
    }

    function validateVideoUpload() {
        const fileInput = document.getElementById('videoInput');
        const file = fileInput.files[0];
        const maxVideoSize = 100 * 1024 * 1024; // 100MB
        const allowedTypes = ['video/mp4', 'video/webm'];

        if (!file) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak ada video yang dipilih!',
                text: 'Silakan pilih file video terlebih dahulu.',
            });
            return false;
        }

        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format video tidak didukung',
                text: 'Format yang diperbolehkan: MP4, WEBM.',
            });
            return false;
        }

        if (file.size > maxVideoSize) {
            Swal.fire({
                icon: 'error',
                title: 'Ukuran video terlalu besar',
                text: 'Ukuran video maksimal 100MB.',
            });
            return false;
        }

        Swal.fire({
            title: 'Mengunggah...',
            text: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        return true;
    }
</script>
@endsection
