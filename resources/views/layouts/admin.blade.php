@php
  use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', 'MySite')</title>
	@vite('resources/css/app.css')
	<script src="https://unpkg.com/@phosphor-icons/web"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css">
	<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  
	<style>

		.custom-toast-warning {
			background-color: #e53e3e !important;
			color: white !important;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
		}
		.custom-toast-success {
			background-color: #38a169 !important;
			color: white !important;
		}

		.custom-toast-errors {
			background-color: #e53e3e !important;
			color: white !important;
			font-weight: 600;
			box-shadow: 0 4px 12px rgba(229, 62, 62, 0.5);
		}

		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
			overflow-y: auto;
			box-sizing: border-box;
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

		[x-cloak] { display: none !important; }
	</style>
</head>
<body x-data="mainSidebar()" x-init="init()" class="flex h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">

	@if(session('success'))
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil!',
					text: @json(session('success')),
					showConfirmButton: false,
					timer: 2500,
					timerProgressBar: true,
					toast: true,
					position: 'top',
					customClass: {
						popup: 'custom-toast-success'
					}
				});
			});
		</script>
	@endif

	@if ($errors->any())
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				Swal.fire({
					icon: 'error',
					title: 'Terjadi Kesalahan!',
					html: `{!! implode('<br>', $errors->all()) !!}`,
					showConfirmButton: true,
                    timer: null,
  					toast: false,
                    position: 'center',
                    customClass: { popup: 'custom-toast-errors' }
				});
			});
		</script>
	@endif

	@if(session('nochange'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Perubahan',
                    text: '{{ session('nochange') }}',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top',
                    customClass: { popup: 'custom-toast-warning' }
                });
            });
        </script>
    @endif


	<!-- Sidebar -->
	<aside
		:class="open ? 'w-64' : 'w-20'"
		class="transition-[width] duration-300 ease-in-out
			bg-white dark:bg-gray-800
			border-r border-gray-300 dark:border-gray-700
			flex flex-col
			shrink-0"
		id="sidebar"
	>
		<div class="flex items-center justify-between p-4 ml-2 border-b dark:border-gray-700">
			<h1 :class="open ? 'block' : 'hidden'" class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Admin</h1>
			<button @click="toggleSidebar()" class="text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">
				<i class="ph ph-list text-2xl"></i>
			</button>
		</div>

		<!-- Navigation -->
		<nav class="flex-1 px-4 py-4 space-y-2 overflow-x-hidden" data-active-route="{{ Route::currentRouteName() }}">
			<!-- Dashboard -->
			<a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-200 dark:bg-indigo-800 font-semibold' : '' }}">
				<i class="ph ph-house-simple text-xl"></i>
				<span :class="open ? 'block' : 'hidden'">Dashboard</span>
			</a>

			<!-- Layout Dropdown -->
			<div class="px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900">
				<div class="flex items-center justify-between cursor-pointer" @click="handleDropdown('layout')">
				<div class="flex items-center space-x-2">
					<i class="ph ph-squares-four text-xl"></i>
					<span :class="open ? 'block' : 'hidden'">Layout</span>
				</div>
				<svg :class="open ? 'block' : 'hidden'" :class="{ 'rotate-180': dropdowns.layout }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
				</svg>
				</div>
				<div class="ml-6 mt-2 space-y-1" x-show="dropdowns.layout" x-transition>
					<a :class="open ? 'block' : 'hidden'" href="{{ route('admin.layout.home') }}" class="block px-3 py-1 rounded hover:bg-indigo-200 dark:hover:bg-indigo-800 {{ request()->routeIs('admin.layout.home') ? 'bg-indigo-200 dark:bg-indigo-800 font-semibold' : '' }}">
						Home
					</a>
					<a :class="open ? 'block' : 'hidden'" href="{{ route('admin.layout.about') }}" class="block px-3 py-1 rounded hover:bg-indigo-200 dark:hover:bg-indigo-800 {{ request()->routeIs('admin.layout.about') ? 'bg-indigo-200 dark:bg-indigo-800 font-semibold' : '' }}">
						About
					</a>
					<a :class="open ? 'block' : 'hidden'" href="{{ route('admin.layout.work') }}" class="block px-3 py-1  rounded hover:bg-indigo-200 dark:hover:bg-indigo-800 {{ request()->routeIs('admin.layout.work') ? 'bg-indigo-200 dark:bg-indigo-800 font-semibold' : '' }}">
						Work
					</a>
				</div>
			</div>

			<!-- Folder -->
			<a href="{{ route('admin.folders.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('admin.folders.index') ? 'bg-indigo-200 dark:bg-indigo-800 font-semibold' : '' }}">
				<i class="ph ph-folders text-xl"></i>
				<span :class="open ? 'block' : 'hidden'">Folders</span>
			</a>

			<!-- Users -->
			<a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('admin.users.index') ? 'bg-indigo-200 dark:bg-indigo-800 font-semibold' : '' }}">
				<i class="ph ph-users text-xl"></i>
				<span :class="open ? 'block' : 'hidden'">Users</span>
			</a>

			<!-- Settings -->
			<a href="{{ route('admin.bookings.index') }}" 
				class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900 
				{{ request()->routeIs('admin.bookings.index') ? 'bg-indigo-200 dark:bg-indigo-800 font-semibold' : '' }}">
					<i class="ph ph-calendar-check text-xl"></i>
					<span :class="open ? 'block' : 'hidden'">Booking</span>
			</a>
		</nav>

		<!-- Mode Toggle -->
		<button id="themeToggleBtn" class="menu-item flex items-center ml-4 mr-4 mb-2 p-2 rounded text-gray-600 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-900">
			<i id="sunIcon" class="ph ph-sun text-xl hidden ml-1"></i>
			<i id="moonIcon" class="ph ph-moon text-xl hidden ml-1"></i>
			<span id="themeLabel" class="text-sm font-medium" :class="open ? 'block' : 'hidden'">Light</span>
		</button>

		<!-- Logout -->
		<form action="{{ route('admin.logout') }}" method="POST" class="p-4 border-t dark:border-gray-700" onsubmit="localStorage.removeItem('dropdown_layout')">
			@csrf
			<button type="submit" class="menu-item w-full flex items-center justify-center space-x-3 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 relative">
				<i class="ph ph-sign-out text-xl"></i>
				<span :class="open ? 'block' : 'hidden'" class="label">Logout</span>
			</button>
		</form>
	</aside>

	<!-- Main Content -->
	<main class="flex-1 p-8 overflow-auto relative min-h-0">
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

		function mainSidebar() {
			return {
				open: true,
				dropdowns: {
					layout: false,
					user: false
				},
				init() {

					const activeRoute = document.querySelector('[data-active-route]').dataset.activeRoute;
					
					if (activeRoute.startsWith('admin.layout.')) this.dropdowns.layout = true;

					if (activeRoute.startsWith('admin.users.')) this.dropdowns.user = true;
				},

				toggleSidebar() {
					this.open = !this.open;
					if (!this.open) {
						Object.keys(this.dropdowns).forEach(key => this.dropdowns[key] = false);
					}
				},

				handleDropdown(name) {
					if (!this.open) {
						this.open = true;
						this.dropdowns[name] = true;
					} else {
						this.dropdowns[name] = !this.dropdowns[name];
					}
				}
			}
		}
	</script>
</body>
</html>