@extends('layouts.app')

@section('title', 'ELEXAR')

@section('content')

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
<section id="about" class="relative min-h-screen flex flex-col bg-[#fcead9] text-black px-6 md:px-20 gap-10 py-12">

  <!-- Kontainer Konten: Gambar + Teks -->
  <div class="flex flex-col md:flex-row items-center justify-center mt-28 gap-10 w-full z-10">
    
    <!-- Gambar -->
    <div class="w-full md:w-1/2 flex justify-center">
      <div class="aspect-square w-full max-w-lg md:max-w-xl overflow-hidden rounded-xl shadow-lg">
        <img src="{{ asset('img/4.jpg') }}" 
          alt="About Image"
          class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
      </div>
    </div>

    <!-- Teks -->
    <div class="w-full md:w-1/2 space-y-6">
      <h2 class="text-4xl md:text-5xl font-bold tracking-wide text-center md:text-left">About Us</h2>
      <p class="text-lg md:text-2xl leading-relaxed text-gray-700 text-center md:text-left">
        We’re passionate photographers and videographers dedicated to transforming you, your products, and even your wildest ideas into stunning, one-of-a-kind visuals. 
        We thrive on exploring concepts, emotions, and values to bring your vision to life in a way that’s truly unique. 
        We can’t wait to collaborate and turn your dreams into unforgettable stories. Let’s create something extraordinary together!
      </p>
      <div class="text-center md:text-left pb-10">
        <a href="#work" class="inline-block mt-4 px-6 py-3 bg-[#1f1f1f] text-white rounded-lg hover:bg-[#000000c4] transition">See Our Work</a>
      </div>
    </div>
  </div>

  <!-- Teks Berjalan-->

  <div class="marquee-container absolute bottom-0 left-0 w-full h-12 bg-black overflow-hidden">
    <div class="marquee-track flex">
      <div class="marquee-content text-white text-3xl md:text-5xl font-bold whitespace-nowrap px-6">
        📸 WEDDINGS 📸 PRODUCT LAUNCHES 📸 COMMERCIALS 📸 FILM & TV 📸 MILESTONES
      </div>
      <div class="marquee-content text-white text-3xl md:text-5xl font-bold whitespace-nowrap px-6">
        📸 WEDDINGS 📸 PRODUCT LAUNCHES 📸 COMMERCIALS 📸 FILM & TV 📸 MILESTONES
      </div>
    </div>
  </div>
</section>


{{-- SECTION: CAROUSEL --}}
<section id="work" class="relative w-full min-h-screen overflow-hidden bg-[#fcead9] z-0">
  <img id="imgA" class="fade-img absolute inset-0 w-full h-full object-cover z-10 transition-opacity duration-1000 ease-in-out opacity-100" alt="carousel" />
  <img id="imgB" class="fade-img absolute inset-0 w-full h-full object-cover z-0 transition-opacity duration-1000 ease-in-out opacity-0" alt="carousel" />

  <div id="textOverlayPlain" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-center z-20 pointer-events-none transition-opacity duration-1000 opacity-100">
    <h1 class="text-7xl font-bold drop-shadow tracking-[0.4em]">OUR WORK.</h1>
  </div>
</section>

<section id="portofolio" class="w-full min-h-screen bg-[#fcead9] py-20 px-6 md:px-20">
  <div class="relative z-20 mt-[6vh] px-6 md:px-20">
    <div class="bg-black/40 border border-black/50 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden max-w-6xl mx-auto p-2 md:p-10">

      <!-- Logo di kanan atas -->
      <div class="flex justify-end h-full object-contain">
        <img src="{{ asset('img/logo 3.png') }}" alt="Logo" class="h-12 md:h-16 object-contain" />
      </div>

      <div class="flex flex-col md:flex-row items-center gap-6">
        <!-- Video -->
        <div class="w-full md:w-1/2 aspect-video rounded-xl overflow-hidden border border-white/10 shadow-lg">
          <video id="portfolioVideo"
            class="w-full h-full object-cover"
            src="video/Homepage - Website.mp4"
            playsinline
            muted
            preload="auto"
            controls
            controlsList="nodownload"
            loop>
          </video>
        </div>

        <!-- Info -->
        <div class="w-full md:w-1/2 text-white space-y-4 mb-10">
          <h3 class="text-5xl font-semibold">Judul Video</h3>
          <p class="text-sm text-gray-300">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi amet iure est temporibus, quidem quos illum doloribus quas nam, delectus dolores veritatis et earum dolore iste,
             alias labore. A, ullam? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate distinctio quisquam magni facilis voluptatibus consectetur. 
             Cupiditate rem molestiae voluptate cum. Animi autem doloribus rem voluptate perferendis amet quis laboriosam quod. Lorem ipsum dolor sit amet consectetur adipisicing elit. 
             Quos quisquam obcaecati praesentium, architecto laborum numquam odit culpa nihil. Numquam at alias aliquam nihil quia recusandae, eius deleniti sed suscipit ex.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
