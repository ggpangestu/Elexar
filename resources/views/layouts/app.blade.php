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
      <a href="#" id="desktop-login-button" class="nav-login-trigger hidden md:inline-block px-4 py-2 border border-white rounded-full hover:bg-white hover:text-black transition">
        Login
      </a>
    </div>

    <!-- MENU MOBILE -->
    <div id="nav-menu" class="md:hidden absolute top-full left-0 w-full bg-black hidden px-3 py-2">
      <ul class="flex flex-col gap-4">
        <li><button onclick="scrollToSection('home')" class="text-left nav-link active:text-amber-600">HOME</button></li>
        <li><button onclick="scrollToSection('about')" class="text-left nav-link active:text-amber-600">ABOUT</button></li>
        <li><button onclick="scrollToSection('work')" class="text-left nav-link active:text-amber-600">WORK</button></li>
        <li><a href="#" id="mobile-login-button" class="nav-login-trigger block mt-2 border-t pt-2 active:text-amber-600">Login</a></li>      </ul>
    </div>
  </nav>

  {{-- Main content --}}
  <main>
    @yield('content')
  </main>

<div id="auth-modal-backdrop" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-[999] hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
<div id="auth-modal" class="relative bg-[#fcead9]/30 text-white rounded-lg shadow-2xl w-11/12 max-w-md overflow-hidden transform scale-95 opacity-0 transition-all duration-300 ease-in-out flex flex-col justify-center items-center py-8 h-[500px]">        
  <button id="auth-modal-close" class="absolute top-4 right-4 text-white-600 hover:text-gray-900 focus:outline-none text-2xl z-50">&times;</button>

        <div id="login-form" class="auth-form absolute inset-0 p-8 opacity-100 pointer-events-auto transition-opacity duration-300">
    <h2 class="text-3xl font-bold text-center mb-6">Sign in</h2>
    <form>
        <div class="mb-4">
            <label for="login-email" class="block text-white-700 text-sm font-bold mb-2">Email</label>
            <input type="email" id="login-email" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white/50" placeholder="your@example.com">
        </div>
        <div class="mb-6">
            <label for="login-password" class="block text-white-700 text-sm font-bold mb-2">Password</label>
            <input type="password" id="login-password" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline bg-white/50" placeholder="********">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-[#1f1f1f] hover:bg-[#000000c4] text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition">
                SIGN IN
            </button>
        </div>
    </form>
    <p class="text-center text-sm mt-6">
        Forgot your password? <a href="#" id="show-forgot-password" class="text-[#A0522D] hover:underline font-semibold">Click here</a>
    </p>
    <p class="text-center text-sm mt-6">
        Don't have an account? <a href="#" id="show-register" class="text-[#A0522D] hover:underline font-semibold">Register here</a>
    </p>
</div>

<div id="register-form" class="auth-form absolute inset-0 p-8 opacity-0 pointer-events-none transition-opacity duration-300">
    <h2 class="text-3xl font-bold text-center mb-6">Create Account</h2>
    <form>
        <div class="mb-4">
            <label for="register-name" class="block text-white-700 text-sm font-bold mb-2">Name</label>
            <input type="text" id="register-name" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white/50" placeholder="Your Name">
        </div>
        <div class="mb-4">
            <label for="register-email" class="block text-white-700 text-sm font-bold mb-2">Email</label>
            <input type="email" id="register-email" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white/50" placeholder="your@example.com">
        </div>
        <div class="mb-4">
            <label for="register-password" class="block text-white-700 text-sm font-bold mb-2">Password</label>
            <input type="password" id="register-password" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline bg-white/50" placeholder="********">
        </div>
        <div class="mb-6">
            <label for="register-confirm-password" class="block text-white-700 text-sm font-bold mb-2">Confirm Password</label>
            <input type="password" id="register-confirm-password" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline bg-white/50" placeholder="********">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-[#1f1f1f] hover:bg-[#000000c4] text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition">
                SIGN UP
            </button>
        </div>
    </form>
    <p class="text-center text-sm mt-6">
        Already have an account? <a href="#" id="show-login" class="text-[#A0522D] hover:underline font-semibold">Login here</a>
    </p>
</div>

 <div id="forgot-password-form" class="auth-form absolute inset-0 p-8 opacity-0 pointer-events-none transition-opacity duration-300">
            <h2 class="text-3xl font-bold text-center mb-6">Forgot Password?</h2>
            <form>
                <div class="mb-4">
                    <label for="forgot-email" class="block text-white-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="forgot-email" class="shadow appearance-none rounded w-full py-2 px-3 bg-white/80 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="your@example.com">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-[#1f1f1f] hover:bg-[#000000c4] text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition">
                        Reset Password
                    </button>
                </div>
            </form>
            <p class="text-center text-sm mt-6">
                Remembered your password? <a href="#" id="back-to-login" class="text-[#A0522D] hover:underline font-semibold">Login here</a>
            </p>
        </div>

    </div>
</div>

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

  // === Login/Register Modal Logic ===
// Select all elements that could act as a login button.
// We'll use a more specific selector or add a custom class.
    const desktopLoginButton = document.getElementById('desktop-login-button');
    const mobileLoginButton = document.getElementById('mobile-login-button');
    const authModalBackdrop = document.getElementById('auth-modal-backdrop');
    const authModal = document.getElementById('auth-modal');
    const authModalClose = document.getElementById('auth-modal-close');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');
    const body = document.body; // body is a global object, but good to include it here for consistency if used often
    const forgotPasswordForm = document.getElementById('forgot-password-form');
    const showForgotPasswordLink = document.getElementById('show-forgot-password'); // The link from login form
    const backToLoginLink = document.getElementById('back-to-login'); // The link from forgot password form

    // Log the selected elements to confirm they are found
    console.log("Desktop Login Button:", desktopLoginButton);
    console.log("Mobile Login Button:", mobileLoginButton);
    console.log("Auth Modal Backdrop:", authModalBackdrop);
    console.log("Auth Modal Close Button:", authModalClose);
    // ... (add more logs for other elements if you want) ...


    // --- Define functions *inside* DOMContentLoaded scope ---
    // Function to show the modal
    function showAuthModal() {
        console.log("showAuthModal called.");
        authModalBackdrop.classList.remove('hidden');
        void authModalBackdrop.offsetWidth;
        authModalBackdrop.classList.remove('opacity-0');
        authModal.classList.remove('opacity-0', 'scale-95');
        body.classList.add('overflow-hidden');
    }

    // Function to hide the modal
    function hideAuthModal() {
        console.log("hideAuthModal called.");
        authModalBackdrop.classList.add('opacity-0');
        authModal.classList.add('opacity-0', 'scale-95');
        body.classList.remove('overflow-hidden');
        setTimeout(() => {
            authModalBackdrop.classList.add('hidden');
            loginForm.classList.add('active'); // Reset to login form state
            registerForm.classList.remove('active');
            registerForm.classList.add('translate-y-full');
            loginForm.classList.remove('translate-y-full');
            authModal.style.height = '';
        }, 300);
    }
    // --- End of function definitions ---


    // Event listener for opening the modal (desktop)
    if (desktopLoginButton) {
        desktopLoginButton.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("Desktop Login button clicked.");
            showAuthModal();
        });
    }

    // Event listener for opening the modal (mobile)
    if (mobileLoginButton) {
        mobileLoginButton.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("Mobile Login button clicked.");
            showAuthModal();
        });
    }

    // Event listener for closing the modal (button)
    if (authModalClose) {
        authModalClose.addEventListener('click', () => {
            console.log("Close button clicked.");
            hideAuthModal();
        });
    }


    // Event listener for closing the modal (clicking outside)
    if (authModalBackdrop) { // Check if backdrop exists before adding listener
        authModalBackdrop.addEventListener('click', (e) => {
            if (e.target === authModalBackdrop) {
                console.log("Backdrop clicked, closing modal.");
                hideAuthModal();
            }
        });
    }


    // Function to switch to register form
    // Function to switch to register form
    // Function to switch to register form (FADE)
    if (showRegisterLink) {
        showRegisterLink.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("Show Register link clicked (FADE).");

            // Hide login form
            loginForm.classList.remove('opacity-100', 'pointer-events-auto');
            loginForm.classList.add('opacity-0', 'pointer-events-none');

            // Show register form
            registerForm.classList.remove('opacity-0', 'pointer-events-none');
            registerForm.classList.add('opacity-100', 'pointer-events-auto');
        });
    }

    // Function to switch back to login form (FADE)
    if (showLoginLink) {
        showLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("Show Login link clicked (FADE).");

            // Hide register form
            registerForm.classList.remove('opacity-100', 'pointer-events-auto');
            registerForm.classList.add('opacity-0', 'pointer-events-none');

            // Show login form
            loginForm.classList.remove('opacity-0', 'pointer-events-none');
            loginForm.classList.add('opacity-100', 'pointer-events-auto');
        });
    }

    // --- IMPORTANT: Update hideAuthModal to reset to Login form state (fade) ---
    function hideAuthModal() {
        console.log("hideAuthModal called.");
        authModalBackdrop.classList.add('opacity-0');
        authModal.classList.add('opacity-0', 'scale-95');
        body.classList.remove('overflow-hidden');
        setTimeout(() => {
            authModalBackdrop.classList.add('hidden');
            
            // Reset to login form state when closing (fade)
            loginForm.classList.remove('opacity-0', 'pointer-events-none');
            loginForm.classList.add('opacity-100', 'pointer-events-auto');
            
            registerForm.classList.remove('opacity-100', 'pointer-events-auto');
            registerForm.classList.add('opacity-0', 'pointer-events-none');

            authModal.style.height = ''; // Reset modal height if it was adjusted
        }, 300);
    }

    // ... (existing showRegisterLink and showLoginLink listeners) ...

// Function to switch to forgot password form (FADE)
if (showForgotPasswordLink) {
    showForgotPasswordLink.addEventListener('click', (e) => {
        e.preventDefault();
        console.log("Show Forgot Password link clicked (FADE).");

        // Hide currently active form (login or register)
        loginForm.classList.remove('opacity-100', 'pointer-events-auto');
        loginForm.classList.add('opacity-0', 'pointer-events-none');
        registerForm.classList.remove('opacity-100', 'pointer-events-auto');
        registerForm.classList.add('opacity-0', 'pointer-events-none');

        // Show forgot password form
        forgotPasswordForm.classList.remove('opacity-0', 'pointer-events-none');
        forgotPasswordForm.classList.add('opacity-100', 'pointer-events-auto');
    });
}

// Function to switch back to login form from forgot password (FADE)
if (backToLoginLink) {
    backToLoginLink.addEventListener('click', (e) => {
        e.preventDefault();
        console.log("Back to Login link clicked from Forgot Password (FADE).");

        // Hide forgot password form
        forgotPasswordForm.classList.remove('opacity-100', 'pointer-events-auto');
        forgotPasswordForm.classList.add('opacity-0', 'pointer-events-none');

        // Show login form
        loginForm.classList.remove('opacity-0', 'pointer-events-none');
        loginForm.classList.add('opacity-100', 'pointer-events-auto');
    });
}

// --- IMPORTANT: Update hideAuthModal to reset to Login form state (fade) ---
function hideAuthModal() {
    console.log("hideAuthModal called.");
    authModalBackdrop.classList.add('opacity-0');
    authModal.classList.add('opacity-0', 'scale-95');
    body.classList.remove('overflow-hidden');
    setTimeout(() => {
        authModalBackdrop.classList.add('hidden');

        // Reset all forms to their initial hidden/shown state when closing
        loginForm.classList.remove('opacity-0', 'pointer-events-none');
        loginForm.classList.add('opacity-100', 'pointer-events-auto');

        registerForm.classList.remove('opacity-100', 'pointer-events-auto');
        registerForm.classList.add('opacity-0', 'pointer-events-none');

        forgotPasswordForm.classList.remove('opacity-100', 'pointer-events-auto'); // Ensure this is reset too
        forgotPasswordForm.classList.add('opacity-0', 'pointer-events-none');

        authModal.style.height = ''; // Reset modal height if it was adjusted
    }, 300);
}

</script>
</html>
