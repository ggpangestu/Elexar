<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <style>
    body {
      background: linear-gradient(to bottom right, #111827, #f9fafb);
      overflow: hidden;
      position: relative;
    }

    body::before {
      content: '';
      position: absolute;
      top: -20%;
      left: -20%;
      width: 140%;
      height: 140%;
      background: radial-gradient(circle at 30% 30%, #6366f1 0%, transparent 60%),
                  radial-gradient(circle at 70% 70%, #f472b6 0%, transparent 60%);
      opacity: 0.25;
      animation: moveAurora 20s infinite alternate;
      z-index: 0;
    }

    @keyframes moveAurora {
      0% { transform: translate(0, 0); }
      100% { transform: translate(20px, -30px); }
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">
  <div class="relative z-10 backdrop-blur-md bg-white/10 border border-white/20 shadow-2xl rounded-3xl p-12 md:p-16 max-w-4xl w-full text-white">
    <div class="flex items-center justify-between mb-10">
      <h2 class="text-4xl font-extrabold">My Profile</h2>
      <div class="flex gap-4">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-2 px-6 py-3 bg-white/10 border border-white/30 hover:bg-white/30 text-base rounded-xl font-semibold shadow transition">
          <!-- Edit icon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15.232 5.232l3.536 3.536M9 13l6-6m2-2a2.828 2.828 0 114 4l-10 10H5v-4l10-10z" />
          </svg>
          Edit
        </a>
        <a href="{{ route('users.landing') }}"
           class="flex items-center gap-2 px-6 py-3 bg-white/10 border border-white/30 hover:bg-white/20 text-base rounded-xl font-semibold shadow transition">
          <!-- Back icon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 19l-7-7 7-7" />
          </svg>
          Back
        </a>
      </div>
    </div>

    <div class="grid md:grid-cols-2 gap-10 text-lg">
      <div>
        <h4 class="text-sm font-semibold opacity-70">Name</h4>
        <p class="text-xl">{{ Auth::user()->name }}</p>
      </div>
      <div>
        <h4 class="text-sm font-semibold opacity-70">Email</h4>
        <p class="text-xl">{{ Auth::user()->email }}</p>
      </div>
      <div class="md:col-span-2">
        <h4 class="text-sm font-semibold opacity-70">Email Verified At</h4>
        <p class="text-xl">
          {{ Auth::user()->email_verified_at ? \Carbon\Carbon::parse(Auth::user()->email_verified_at)->format('d M Y, H:i') : 'Not verified' }}
        </p>
      </div>
    </div>
  </div>
</body>
</html>
