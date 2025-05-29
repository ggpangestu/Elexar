@php
  use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  @vite('resources/css/app.css')
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="flex h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
  <!-- Sidebar -->
 <aside class="bg-white dark:bg-gray-800 border-r border-gray-300 dark:border-gray-700 w-64 min-h-screen flex flex-col transition-all duration-300">
    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
      <h1 class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Admin</h1>
      <button id="toggleBtn" class="text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">
        <i class="ph ph-list text-2xl"></i>
      </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto"
    	x-data="sidebar"
		data-active-route="{{ Route::currentRouteName() }}"
		>
      <!-- Dashboard -->
      <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-200 dark:bg-indigo-800 font-semibold' : '' }}">
        <i class="ph ph-house-simple text-xl"></i>
        <span>Dashboard</span>
      </a>

      <!-- Layout Dropdown -->
      <div class="px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900">
        <div class="flex items-center justify-between cursor-pointer" @click="toggleDropdown('layout')">
          <div class="flex items-center space-x-2">
            <i class="ph ph-squares-four text-xl"></i>
            <span>Layout</span>
          </div>
          <svg :class="{ 'rotate-180': dropdowns.layout }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
		<div class="ml-6 mt-2 space-y-1" x-show="dropdowns.layout" x-transition>
          <a href="{{ route('admin.layout.home') }}" class="block px-3 py-1 rounded hover:bg-indigo-200 dark:hover:bg-indigo-800 {{ request()->routeIs('admin.layout.home') ? 'bg-indigo-200 dark:bg-indigo-800 font-semibold' : '' }}">
            Home
          </a>
          <a href="#" class="block px-3 py-1 rounded hover:bg-indigo-200 dark:hover:bg-indigo-800">
            About
          </a>
          <a href="#" class="block px-3 py-1 rounded hover:bg-indigo-200 dark:hover:bg-indigo-800">
            Work
          </a>
        </div>
      </div>

      <!-- Users -->
      <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900">
        <i class="ph ph-users text-xl"></i>
        <span>Users</span>
      </a>

      <!-- Settings -->
      <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900">
        <i class="ph ph-gear-six text-xl"></i>
        <span>Settings</span>
      </a>
    </nav>

    <!-- Mode Toggle -->
    <button id="themeToggleBtn" class="menu-item flex items-center ml-4 mr-2 mb-2 p-2 rounded text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
      <i id="sunIcon" class="ph ph-sun text-xl hidden mr-2"></i>
      <i id="moonIcon" class="ph ph-moon text-xl hidden mr-2"></i>
      <span id="themeLabel" class="text-sm font-medium">Light</span>
    </button>

    <!-- Logout -->
	<form action="{{ route('admin.logout') }}" method="POST" class="p-4 border-t dark:border-gray-700" onsubmit="localStorage.removeItem('dropdown_layout')">
		@csrf
		<button type="submit" class="menu-item w-full flex items-center justify-center space-x-3 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
			<i class="ph ph-sign-out text-xl"></i>
			<span class="label">Logout</span>
		</button>
	</form>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-8 overflow-auto relative">
    @yield('content')
  </main>

  <!-- Script for sidebar & theme toggle -->
  <script>
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');
    const themeLabel = document.getElementById('themeLabel');

    function setTheme(theme) {
      if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
        themeLabel.textContent = 'Light';
      } else {
        document.documentElement.classList.remove('dark');
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
        themeLabel.textContent = 'Dark';
      }
      localStorage.setItem('theme', theme);
    }

    document.addEventListener('DOMContentLoaded', () => {
      const savedTheme = localStorage.getItem('theme') || 'light';
      setTheme(savedTheme);
    });

    themeToggleBtn.addEventListener('click', () => {
      const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
      setTheme(currentTheme === 'dark' ? 'light' : 'dark');
    });


	  document.addEventListener('alpine:init', () => {
		Alpine.data('sidebar', () => {
			const activeRoute = document.querySelector('[x-data="sidebar"]').dataset.activeRoute;

			// Inisialisasi dropdown state
			const dropdowns = {
				layout: JSON.parse(localStorage.getItem('dropdown_layout')) ?? false,
				user: JSON.parse(localStorage.getItem('dropdown_user')) ?? false,
			};

			// AUTO OPEN berdasarkan route
			if (activeRoute.startsWith('admin.layout.')) {
				dropdowns.layout = true;
				localStorage.setItem('dropdown_layout', JSON.stringify(true));
			}

			if (activeRoute.startsWith('admin.users.')) {
				dropdowns.user = true;
				localStorage.setItem('dropdown_user', JSON.stringify(true));
			}

			return {
				dropdowns,
				toggleDropdown(name) {
					this.dropdowns[name] = !this.dropdowns[name];
					localStorage.setItem(`dropdown_${name}`, JSON.stringify(this.dropdowns[name]));
				}
			}
		});
	});

  </script>
</body>
</html>
