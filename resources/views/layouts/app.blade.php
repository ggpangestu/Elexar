<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MySite')</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
      html {
        scroll-behavior: smooth;
      }
      .active-nav {
        color: #794f3c;
      }
      .marquee-track {
        animation: marquee-scroll 20s linear infinite;
      }

      @keyframes marquee-scroll {
        0% {
          transform: translateX(0%);
        }
        100% {
          transform: translateX(-120%);
        }
      }
    </style>
</head>
<body class="font-sans bg-black text-white overflow-x-hidden">

  <nav id="main-nav" class="fixed top-0 left-0 w-full z-50 px-4 py-1 bg-black md:bg-transparent flex items-center justify-between transition-colors duration-300">
    <!-- KIRI: Logo -->
    <div class="h-16 flex items-center">
      <img src="{{ asset('img/logo 3.png') }}" alt="Logo" class="h-full object-contain" />
    </div>

    <!-- TENGAH: Nav menu (hidden di mobile) -->
    <div class="hidden md:flex flex-1 justify-center">
      <ul class="flex gap-64 items-center">
        <li>
          <button data-target="home" onclick="scrollToSection('home')" class="nav-link group relative px-2 py-1">
            <span class="relative z-10 group-hover:text-amber-800 tracking-[0.2em]">HOME</span>
            <span class="absolute inset-0 bg-gray-500 bg-opacity-30 scale-x-0 group-hover:scale-x-100 active:scale-x-100 transition-transform duration-300 origin-left rounded-md z-0"></span>
          </button>
        </li>
        <li>
          <button data-target="about" onclick="scrollToSection('about')" class="nav-link group relative px-2 py-1">
            <span class="relative z-10 group-hover:text-amber-800 tracking-[0.2em]">ABOUT</span>
            <span class="absolute inset-0 bg-gray-500 bg-opacity-30 scale-x-0 group-hover:scale-x-100 active:scale-x-100 transition-transform duration-300 origin-left rounded-md z-0"></span>
          </button>
        </li>
        <li>
          <button data-target="work" onclick="scrollToSection('work')" class="nav-link group relative px-2 py-1">
            <span class="relative z-10 group-hover:text-amber-800 tracking-[0.2em]">WORK</span>
            <span class="absolute inset-0 bg-gray-500 bg-opacity-30 scale-x-0 group-hover:scale-x-100 active:scale-x-100 transition-transform duration-300 origin-left rounded-md z-0"></span>
          </button>
        </li>
      </ul>
    </div>

    <!-- KANAN: Burger button di mobile & login di desktop -->
    <div class="flex items-center gap-4">
      <!-- Burger button -->
      <button id="menu-toggle" class="md:hidden text-white focus:outline-none">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <!-- Login (hanya tampil di desktop) -->
      <a href="#" class="hidden md:inline-block px-4 py-2 border border-white rounded-full hover:bg-white hover:text-black transition">
        Login
      </a>
    </div>

    <!-- MENU MOBILE -->
    <div id="nav-menu" class="md:hidden absolute top-full left-0 w-full bg-black hidden px-3 py-2">
      <ul class="flex flex-col gap-4">
        <li><button onclick="scrollToSection('home')" class="text-left nav-link active:text-amber-600">HOME</button></li>
        <li><button onclick="scrollToSection('about')" class="text-left nav-link active:text-amber-600">ABOUT</button></li>
        <li><button onclick="scrollToSection('work')" class="text-left nav-link active:text-amber-600">WORK</button></li>
        <li><a href="#" class="block mt-2 border-t pt-2 active:text-amber-600">Login</a></li>
      </ul>
    </div>
  </nav>

  {{-- Main content --}}
  <main>
    @yield('content')
  </main>

</body>
<script>
  let autoScrollTriggered = false;
  let lastScrollY = 0;

  function scrollToSection(id) {
    const target = document.getElementById(id);
    if (!target) return;

    const offset = 0; // jika ada navbar fixed, bisa sesuaikan
    const top = target.getBoundingClientRect().top + window.scrollY - offset;

    window.scrollTo({
      top,
      behavior: 'smooth'
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    // === 1. Auto Scroll dari Home ke About ===
    const triggerPoint = 50;
    const homeSection = document.getElementById("home");
    const aboutSection = document.getElementById("about");
    let isInHome = false;
    let isInAbout = false;
    let lastScrollY = window.scrollY;
    let autoScrollTriggered = false;

    const sectionObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.target.id === "home") isInHome = entry.isIntersecting;
        if (entry.target.id === "about") isInAbout = entry.isIntersecting;
      });
    }, { threshold: 0.6 });

    sectionObserver.observe(homeSection);
    sectionObserver.observe(aboutSection);

    window.addEventListener("scroll", () => {
      const currentScrollY = window.scrollY;
      const scrollingDown = currentScrollY > lastScrollY;

      if (!autoScrollTriggered && scrollingDown && isInHome && currentScrollY > triggerPoint) {
        autoScrollTriggered = true;
        scrollToSection("about");
        setTimeout(() => autoScrollTriggered = false, 800);
      }

      lastScrollY = currentScrollY;
    });

    // Nonaktifkan autoScroll saat klik nav
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        autoScrollTriggered = true;
        setTimeout(() => autoScrollTriggered = false, 1500);
      });
    });

    // === 2. Highlight Active Nav ===
    const sections = document.querySelectorAll("section");
    const navLinks = document.querySelectorAll(".nav-link");
    let currentActiveSection = "home";

    const navObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          let id = entry.target.id;
          if (id === "portofolio") id = "work";

          if (currentActiveSection === id) return;
          currentActiveSection = id;

          navLinks.forEach(link => {
            link.classList.remove("text-[#A0522D]");
            if (link.dataset.target === id) {
              link.classList.add("text-[#A0522D]");
            }
          });
        }
      });
    }, { threshold: 0.6 });

    sections.forEach(section => navObserver.observe(section));

    // === 3. Nav Background Opacity Scroll ===
    const nav = document.getElementById("main-nav");
    const sectionsToWatch = ["about", "work", "portofolio"];
    const visibilityMap = {
      about: false,
      work: false
    };

    const backgroundObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        const id = entry.target.id;
        if (sectionsToWatch.includes(id)) {
          visibilityMap[id] = entry.isIntersecting;
        }
      });

      const anyVisible = sectionsToWatch.some(id => visibilityMap[id]);

      if (window.innerWidth >= 768) {
        if (anyVisible) {
          nav.classList.add("md:bg-black", "md:bg-opacity-60", "md:backdrop-blur");
          nav.classList.remove("md:bg-transparent");
        } else {
          nav.classList.remove("md:bg-black", "md:bg-opacity-60", "md:backdrop-blur");
          nav.classList.add("md:bg-transparent");
        }
      }
    }, { threshold: 0.4 });

    sectionsToWatch.forEach(id => {
      const section = document.getElementById(id);
      if (section) backgroundObserver.observe(section);
    });

    // === 4. Play Video Saat Portofolio Masuk Layar ===
    const video = document.getElementById("portfolioVideo");
    const portofolioSection = document.getElementById("portofolio");

    const videoObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.target.id === "portofolio") {
          if (entry.isIntersecting) {
            video.play().catch(() => {});
          } else {
            video.pause();
            video.currentTime = 0;
          }
        }
      });
    }, { threshold: 0.5 });

    if (portofolioSection && video) {
      videoObserver.observe(portofolioSection);
    }

    // Helper
    function scrollToSection(id) {
      const section = document.getElementById(id);
      if (section) section.scrollIntoView({ behavior: "smooth" });
    }
  });

  document.getElementById('menu-toggle').addEventListener('click', function () {
    const menu = document.getElementById('nav-menu');
    menu.classList.toggle('hidden');
  });

  // script carousel

  const images = [
    "img/DJI_0058.jpg",
    "img/2.jpg",
    "img/1.jpg",
    "img/4.jpg"
  ];

  const imgA = document.getElementById("imgA");
  const imgB = document.getElementById("imgB");

  let current = 0;
  let showingA = true;

  // Set gambar awal
  imgA.src = images[current];
  imgA.style.opacity = 1;
  imgB.style.opacity = 0;

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

  setInterval(() => {
    const next = (current + 1) % images.length;
    crossFade(next);
  }, 9000);


</script>
</html>
