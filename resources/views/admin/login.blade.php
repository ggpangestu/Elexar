<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ADMIN LOGIN</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
  <form method="POST" action="{{ route('admin.login.post') }}" class="bg-white p-8 rounded shadow-md w-full max-w-sm">
    @csrf
    <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>

    @if($errors->any())
      <div class="text-red-500 text-sm mb-4">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="mb-4">
      <label for="email" class="block mb-1 font-semibold">Email</label>
      <input type="email" name="email" id="email" class="w-full border border-gray-300 px-3 py-2 rounded" required>
    </div>

    <div class="mb-6">
      <label for="password" class="block mb-1 font-semibold">Password</label>
      <input type="password" name="password" id="password" class="w-full border border-gray-300 px-3 py-2 rounded" required>
    </div>

    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
  </form>
</body>

<script>
  window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
      window.location.reload();
    }
  });
</script>
</html>
