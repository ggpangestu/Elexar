<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  @vite('resources/css/app.css')
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <style>
    .transition-all {
      transition: all 0.3s ease;
    }
  </style>
</head>

<body class="flex h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
  <!-- Sidebar -->
  <aside id="sidebar" class="bg-white dark:bg-gray-800 border-r border-gray-300 dark:border-gray-700 transition-all duration-300 w-64 min-h-screen flex flex-col">
    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
      <h1 id="sidebar-title" class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Admin</h1>
      <button id="toggleBtn" class="text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">
        <i class="ph ph-list text-2xl"></i>
      </button>
    </div>

    <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
      <a href="#" class="menu-item flex items-center space-x-3 px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900">
        <i class="ph ph-house-simple text-xl"></i>
        <span class="label">Dashboard</span>
      </a>
      <a href="#"
        class="menu-item flex items-center space-x-2 px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900 transition-colors">
          <i class="ph ph-squares-four text-xl icon"></i>
          <span class="label">Layout</span>
      </a>
      <a href="#" class="menu-item flex items-center space-x-3 px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900">
        <i class="ph ph-users text-xl"></i>
        <span class="label">Users</span>
      </a>
      <a href="#" class="menu-item flex items-center space-x-3 px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900">
        <i class="ph ph-gear-six text-xl"></i>
        <span class="label">Settings</span>
      </a>
    </nav>

    <button id="themeToggleBtn" class="menu-item flex items-center ml-4 mr-2 mb-2 p-2 rounded text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
      <i id="sunIcon" class="ph ph-sun text-xl hidden mr-2"></i>
      <i id="moonIcon" class="ph ph-moon text-xl hidden mr-2"></i>
      <span id="themeLabel" class="label text-sm font-medium">Light</span>
    </button>

    <form action="{{ route('admin.logout') }}" method="POST" class="p-4 border-t dark:border-gray-700">
      @csrf
      <button type="submit" class="menu-item w-full flex items-center justify-center space-x-3 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
        <i class="ph ph-sign-out text-xl"></i>
        <span class="label">Logout</span>
      </button>
    </form>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-8 overflow-auto relative">
    <h2 class="text-2xl font-bold mb-4">Welcome, Admin!</h2>

    @if (session('success'))
    <div id="alert" class="fixed top-5 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded shadow-lg bg-green-100 dark:bg-green-800 border border-green-400 text-green-700 dark:text-green-100 font-semibold transition-opacity duration-1000 ease-in-out" style="max-width: 400px;">
      <strong>Berhasil!</strong>
      <span class="block mt-1">{{ session('success') }}</span>
    </div>
    @endif

    <p>This is the admin dashboard content.</p>
  </main>

  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebarTitle = document.getElementById('sidebar-title');
    const menuItems = document.querySelectorAll('.menu-item .label');
    let isCollapsed = false;

    toggleBtn.addEventListener('click', () => {
      isCollapsed = !isCollapsed;
      sidebar.classList.toggle('w-64');
      sidebar.classList.toggle('w-16');
      sidebarTitle.classList.toggle('hidden');
      menuItems.forEach(el => el.classList.toggle('hidden'));
    });

    window.addEventListener('DOMContentLoaded', () => {
      const alert = document.getElementById('alert');
      if (!alert) return;
      setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => {
          alert.style.display = 'none';
        }, 1000);
      }, 3000);
    });

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
  </script>
</body>

</html>
