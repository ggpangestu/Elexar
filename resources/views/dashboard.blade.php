<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HOME</title>
  @vite('resources/css/app.css')
  <style>
    .fade-img {
      transition: opacity 3s ease-in-out;
    }
  </style>

</head>
<body class="overflow-hidden">

  <!-- ✅ Transparent Navbar -->
	<nav class="absolute top-0 left-0 w-full z-50 px-10 py-6 flex items-center text-white">
    <!-- Logo di kiri -->
    <div class="text-xl font-bold">
      MySite
    </div>

    <!-- Spacer supaya logo tetap kiri -->
    <div class="flex-grow"></div>

    <!-- Menu di tengah -->
    <ul class="flex gap-10 absolute left-1/2 transform -translate-x-1/2 text-xl font-bold">
      <li><a href="#" class="hover:underline">Home</a></li>
      <li><a href="#" class="hover:underline">About</a></li>
      <li><a href="#" class="hover:underline">Works</a></li>
    </ul>

    <!-- Tombol login di kanan -->
    <div>
      <a href="
      {{-- {{ route('login') }} --}}
       "
        class="flex items-center justify-center h-8 px-4 rounded-full border border-white hover:bg-white hover:text-black transition duration-300 shadow-lg backdrop-blur bg-white/10 text-white font-semibold">
        Login
      </a>
    </div>
  </nav>


  <!-- Carousel Container -->
  <div class="relative h-screen w-full overflow-hidden bg-black">
    {{-- <img id="imgA" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out opacity-100 z-10" alt="carousel" />
    <img id="imgB" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out opacity-0 z-0" alt="carousel" /> --}}

    <img id="imgA" class="fade-img absolute inset-0 w-full h-full object-cover z-10" alt="carousel" />
    <img id="imgB" class="fade-img absolute inset-0 w-full h-full object-cover z-0" alt="carousel" />


    <!-- Teks dengan blur hanya untuk gambar pertama -->
    <div id="textOverlayBlurred" class="absolute top-1/2 left-0 w-full transform -translate-y-1/2 px-6 py-6 bg-black/40 backdrop-blur-sm z-30 flex justify-center transition-opacity duration-[2000ms]">
      <div class="text-center text-white">
        <h1 class="text-5xl font-bold drop-shadow">ELEXAR</h1>
        <p class="mt-2 text-xl drop-shadow">Photography & Videography<br />based in Bali.</p>
      </div>
    </div>

    <!-- Teks biasa tanpa blur untuk slide berikutnya -->
    <div id="textOverlayPlain" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-center z-20 pointer-events-none transition-opacity duration-[2000ms] opacity-0">
      <h1 class="text-5xl font-bold drop-shadow">ELEXAR</h1>
      <p class="mt-2 text-xl drop-shadow">Photography & Videography<br />based in Bali.</p>
    </div>
  </div>




  <script>
    const images = [
      "img/DJI_0058.jpg",
      "img/2.jpg",
      "img/1.jpg",
      "img/4.jpg"
    ];

    const imgA = document.getElementById("imgA");
    const imgB = document.getElementById("imgB");
    const textBlurred = document.getElementById("textOverlayBlurred");
    const textPlain = document.getElementById("textOverlayPlain");

    let current = 0;
    let showingA = true;

    // Set gambar awal
    imgA.src = images[current];
    imgA.style.opacity = 1;
    imgB.style.opacity = 0;

    function updateTextOverlay(index) {
      if (index === 0) {
        textBlurred.classList.remove("opacity-0");
        textPlain.classList.add("opacity-0");
      } else {
        textBlurred.classList.add("opacity-0");
        textPlain.classList.remove("opacity-0");
      }
    }

    function crossFade(nextIndex) {
      updateTextOverlay(nextIndex);

      if (showingA) {
        imgB.src = images[nextIndex];
        imgB.style.zIndex = 20;
        imgA.style.zIndex = 10;

        // Start transition
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

    setInterval(() => {
      const next = (current + 1) % images.length;
      crossFade(next);
    }, 6000);
  </script>




</body>
</html>
