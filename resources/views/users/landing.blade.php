@extends('layouts.app')

@section('title', 'ELEXAR HOME')

@section('content')

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- Section HOME dengan background gambar dan teks ELEXAR --}}
<section id="home" class="min-h-screen relative flex items-center justify-center bg-black">
  @if($section && $section->home_img)
    <img src="{{ asset('storage/' . $section->home_img) }}" alt="ELEXAR Image" class="absolute inset-0 w-full h-full object-cover" />
  @else
    <div class="absolute inset-0 w-full h-full bg-gray-800"></div>
  @endif
  <div class="relative z-10">
    <h1 class="text-white text-5xl md:text-7xl mb-32 font-semibold tracking-[0.4em] text-center">ELEXAR</h1>
  </div>
</section>

{{-- Section ABOUT --}}
<section id="about" class=" relative min-h-screen flex flex-col bg-[#fcead9] text-black px-6 md:px-20 gap-10 py-20">

  <!-- Kontainer Konten: Gambar + Teks -->
  <div class="flex flex-col md:flex-row items-center justify-center mt-28 gap-10 w-full z-10">
    
    <!-- Gambar -->
    <div class="w-full md:w-1/2 flex justify-center">
      <div class="aspect-square w-full max-w-lg md:max-w-xl overflow-hidden rounded-xl shadow-lg">
        @if($section && $section->about_img)
          <img src="{{ asset('storage/' . $section->about_img) }}"
              alt="About Image"
              class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
        @else
          <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500">
            No image uploaded
          </div>
        @endif
      </div>
    </div>

    <!-- Teks -->
    <div class="w-full md:w-1/2 space-y-6">
      <h2 class="text-4xl md:text-5xl font-bold tracking-wide text-center md:text-left">ABOUT US</h2>
      <p class="text-lg md:text-2xl leading-relaxed text-gray-700 text-center md:text-left">
        {{ $section->about_text ?? 'Deskripsi tentang kami belum tersedia. Silakan tambahkan melalui dashboard admin.' }}
      </p>
      <div class="text-center md:text-left pb-10">
        <button onclick="scrollToSection('work')" class="inline-block mt-4 px-6 py-3 bg-[#242121] text-white rounded-lg hover:bg-[#000000] hover:text-amber-500 transition">
          <span class="relative z-10 tracking-[0.2em]">SEE OUR WORK</span>
        </button>
      </div>
    </div>
  </div>
</section>


{{-- SECTION: CAROUSEL --}}
<section id="work" class="relative w-screen h-screen overflow-hidden bg-[#fcead9] z-0">
  <!-- Gambar Carousel -->
  <img id="imgA" class="fade-img absolute top-0 left-0 w-full h-full object-cover z-10 transition-opacity duration-1000 ease-in-out opacity-100" alt="carousel" />
  <img id="imgB" class="fade-img absolute top-0 left-0 w-full h-full object-cover z-0 transition-opacity duration-1000 ease-in-out opacity-0" alt="carousel" />

  <!-- Teks Tengah -->
  <div id="textOverlayPlain" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-center z-20 pointer-events-none transition-opacity duration-1000 opacity-100">
    <h1 class="text-7xl font-bold drop-shadow tracking-[0.4em]">OUR WORK.</h1>
  </div>
</section>

{{-- SECTION: Video --}}

<section id="portofolio" class="relative bg-[#fcead9] py-20 px-6 md:px-12" x-data="galleryModal()">

  <div class="w-full max-w-[1700px] mx-auto flex flex-col md:flex-row items-start gap-10 bg-black/40 border border-black/50 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden p-12">
    
    {{-- Logo --}}
    <div class="absolute top-6 right-6 z-30">
      <img src="{{ asset('img/logo 3.png') }}" alt="Logo" class="h-12 md:h-16 object-contain" />
    </div>

    {{--Video --}}
    <div class="w-full md:w-1/2 aspect-video bg-black border border-white/10 rounded-xl overflow-hidden shadow-md">
      @if($video && $video->video_path)
        <video
          id="portfolioVideo"
          class="w-full h-full object-contain"
            src="{{ asset('storage/' . $video->video_path) }}"
            playsinline
            muted
            preload="auto"
            controls
            controlsList="nodownload nofullscreen"
            loop>
        </video>
      @else
        <div class="flex items-center justify-center h-full text-gray-400 text-center p-4">Belum ada video</div>
      @endif
    </div>

    {{-- Info --}}
    <div class="w-full md:w-1/2 text-white space-y-8">
      <h3 id="previewTitle" class="text-3xl md:text-6xl font-bold leading-tight tracking-[0.2em]" >{{ $video->title ?? 'Judul Video' }}</h3>
      <p id="previewDescription" class="w-4/5 text-sm md:text-base text-gray-300 leading-relaxed">{{ $video->description ?? 'Deskripsi video belum tersedia.' }}</p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-16 px-14 mt-20">
    @foreach ($folders as $folder)
      <div class="group relative w-[820px] h-[480px] rounded-3xl border border-black/60 bg-white/40 backdrop-blur-sm shadow-lg flex items-center justify-center"
        style="background-image: url('{{ asset('storage/' . $folder->background_folder) }}'); background-size: cover; background-position: center;">
        
        <!-- Card Stack -->
        @php
          $cardClasses = ['card-bottom', 'card-middle', 'card-top'];
        @endphp

        <div class="relative w-64 h-[340px] ml-6 card-stack cursor-pointer">
          @foreach ($folder->photos->take(3) as $index => $photo)
            <div class="absolute inset-0 rounded-2xl shadow-lg flex items-center justify-center font-semibold text-lg p-6 {{ $cardClasses[$index] }}"
              style="background-image: url('{{ asset('storage/' . $photo->path_photo) }}'); background-size: cover; background-position: center; z-index: {{ 10 + $index * 10 }};">
            </div>
          @endforeach
        </div>

        <!-- Overlay on Hover -->
        <div class="absolute inset-0 bg-black/30 rounded-3xl flex flex-col items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 group-hover:opacity-100 group-hover:pointer-events-auto z-40">
          <h3 class="text-white text-2xl font-semibold mb-4">{{ $folder->name }}</h3>
          @auth
            <button
              @click="openGallery({{ $folder->id }})"
              class="px-6 py-2 bg-white text-blue-800 font-semibold rounded-full shadow hover:bg-gray-100 transition transform scale-95 group-hover:scale-100"
            >
              CHECK MORE
            </button>
          @endauth

          @guest
            <a
              href="{{ route('login') }}"
              class="px-6 py-2 bg-white text-blue-800 font-semibold rounded-full shadow hover:bg-gray-100 transition transform scale-95 group-hover:scale-100">
              CHECK MORE
            </a>
          @endguest
        </div>
      </div>
    @endforeach
  </div>

  <!-- Modal -->
  <div x-show="isOpen" x-cloak
     class="fixed inset-0 z-50 bg-black/90 backdrop-blur-sm flex items-center justify-center"
     x-transition>
    <div class="relative w-full max-w-6xl h-[80vh] overflow-y-auto p-6 bg-white rounded-xl shadow-xl">
      <button @click.prevent="close()" class="absolute top-4 right-4 text-2xl text-black hover:text-red-600">&times;</button>
      <h2 class="text-2xl font-bold mb-4 text-gray-800" x-text="folderName"></h2>
      
      <div id="gallery-content" class="columns-1 sm:columns-2 md:columns-3 gap-4 space-y-4"></div>
    </div>
  </div>
</section>

<script src="https://unpkg.com/photoswipe@5/dist/photoswipe-lightbox.esm.min.js" type="module"></script>
<script type="module">
  import PhotoSwipeLightbox from 'https://unpkg.com/photoswipe@5/dist/photoswipe-lightbox.esm.min.js';

  const lightbox = new PhotoSwipeLightbox({
    gallery: '#gallery-content',
    children: 'a',
    pswpModule: () => import('https://unpkg.com/photoswipe@5/dist/photoswipe.esm.min.js')
  });

  lightbox.init();
</script>

<script>
  function galleryModal() {
    return {
      isOpen: false,
      folderId: null,
      folderName: '',

      openGallery(id) {
        this.folderId = id;
        this.isOpen = true;
        this.loadImages(id);
      },

      close() {
        this.isOpen = false;
        document.getElementById('gallery-content').innerHTML = '';
      },

      loadImages(id) {
        fetch(`/gallery/${id}`)
          .then(res => res.json())
          .then(data => {
            this.folderName = data.folder.name;
            const container = document.getElementById('gallery-content');
            container.innerHTML = '';

            data.photos.forEach(photo => {
              const link = document.createElement('a');
              link.href = `/storage/photos/${photo.name}`;
              link.setAttribute('target', '_blank');

              const img = document.createElement('img');
              img.src = `/storage/photos/${photo.name}`;
              img.className = 'w-full rounded-lg gallery-img';

              // Setelah gambar dimuat, ambil ukuran aslinya
              img.onload = function () {
                link.setAttribute('data-pswp-width', img.naturalWidth);
                link.setAttribute('data-pswp-height', img.naturalHeight);
              };

              link.appendChild(img);
              container.appendChild(link);
            });
          });
      }
    }
  }
</script>


@endsection
