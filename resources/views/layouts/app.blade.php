<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'MySite')</title>
        @vite('resources/css/app.css')
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <link rel="stylesheet" href="https://unpkg.com/photoswipe@5/dist/photoswipe.css" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


        <!-- Scripts -->
        <style>
            html {
                scroll-behavior: smooth;
            }
            .active-nav {
                color: #794f3c;
            }

            .card-top {
                transform: translateX(100px) translateY(24px) rotate(12deg);
                transition: transform 0.4s ease;
            }

            .card-middle {
                transform: translateX(0) rotate(-4deg);
                transition: transform 0.4s ease;
            }

            .card-bottom {
                transform: translateX(-100px) rotate(-15deg) translateY(-10px);
                transition: transform 0.4s ease;
            }

            /* ===== Hover (desktop only) ===== */
            @media (min-width: 768px) {
                .group:hover .card-top {
                transform: translateX(220px) rotate(5deg) scale(1.02) translateY(12px);
                transition-delay: 150ms;
                }

                .group:hover .card-middle {
                transform: translateX(0) rotate(0deg) scale(1.02);
                transition-delay: 100ms;
                }

                .group:hover .card-bottom {
                transform: translateX(-245px) rotate(-5deg) scale(1.02) translateY(10px);
                transition-delay: 50ms;
                }
            }

            /* ===== Transisi smooth ===== */
            .group:hover .card-top,
            .group:hover .card-middle,
            .group:hover .card-bottom {
                transition: transform 0.4s ease;
            }

            .masonry-gallery {
                column-gap: 1rem;
            }

            .gallery-img {
                break-inside: avoid;
                margin-bottom: 1rem; /* atur jarak bawah */
            }

            .masonry-gallery a {
                display: block;
                margin-bottom: 1rem;
                break-inside: avoid;
            }

            .masonry-gallery img {
                width: 100%;
                display: block;
                border-radius: 8px;
                margin-bottom: 1rem;
                break-inside: avoid;
            }
            [x-cloak] { display: none !important; }

            /* Warna tombol "Selesai" */
            .flatpickr-confirm {
                color: black;
                border: 1px solid
                border-radius: 6px;
                padding: 6px 14px;
                font-weight: 500;
                transition: background-color 0.3s ease;
            }

            .flatpickr-confirm:hover {
                background-color: rgba(71, 209, 53, 0.3);
                color: black;
            }
        </style>

    </head>
    <body class="font-sans text-base bg-black text-white overflow-x-hidden">

        @if(session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-xl text-sm shadow-lg'
                }
            });
        </script>
        @endif

        @if($errors->any())
        <script>
            Swal.fire({
                toast: true,
                position: 'top',
                icon: 'error',
                title: 'Terjadi kesalahan input',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-xl text-sm shadow-lg'
                }
            });
        </script>
        @endif


        <nav id="main-nav" class="fixed top-0 left-0 w-full z-50 px-4 py-1 bg-black md:bg-transparent flex items-center justify-between transition-colors duration-300">
            <!-- KIRI: Logo -->
            <div class="h-16 flex items-center">
                <img src="{{ asset('img/logo3.png') }}" alt="Logo" class="h-full object-contain" />
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

                <div class="space-x-4">
                    @guest
                    <a href="{{ route('login') }}" class="hidden md:inline-block px-4 py-2 border border-white rounded-full hover:bg-white hover:text-black transition">
                        Login
                    </a>
                    @endguest

                    @auth
                    <div class="flex items-center gap-4">
                        <!-- Tombol Booking -->
                        <button 
                            onclick="openBookingModal()" 
                            class="inline-flex items-center gap-2 px-2 py-2  rounded-full border border-white/70 text-white/90 backdrop-blur-sm bg-white/10 hover:bg-white/20 transition duration-200"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l4-4h10l4 4M4 8v12h16V8M10 14h4" />
                            </svg>
                            Booking
                        </button>

                        <!-- Dropdown User -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="w-8 h-8 rounded-full" />
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50">
                                <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1118.878 6.196a9 9 0 01-13.757 11.608z" />
                                    </svg>
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-left text-red-600 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endauth
                </div>
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
      @stack('scripts')
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
        let currentActiveSection = "";

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
              visibilityMap[id] = entry.intersectionRatio > 0.1;
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
        }, { threshold: [0, 0.1, 0.25, 0.5, 0.75, 1] });

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
        }, { threshold: [0, 0.1, 0.25, 0.5, 0.75, 1] });

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
      const images = @json($carousel->map(fn($c) => asset('storage/' . $c->image_path)));
      const imgA = document.getElementById("imgA");
      const imgB = document.getElementById("imgB");
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
    </script>

    <script>
        function openBookingModal() {
            document.getElementById('bookingModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Mencegah scroll
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Mengembalikan scroll
        }
    </script>
</html>
