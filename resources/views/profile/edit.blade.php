<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script>
    window.addEventListener('beforeunload', function (e) {
      if (window.hasUnsavedChanges) {
        e.preventDefault();
        e.returnValue = '';
      }
    });
  </script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl w-full max-w-2xl p-6" x-data="profileForm({
    name: '{{ auth()->user()->name }}',
    email: '{{ auth()->user()->email }}'
  })">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Profile</h2>
    <form @submit.prevent="saveChanges" class="space-y-6">
      <div>
        <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-1">Name</label>
        <input type="text" x-model="form.name" @input="checkChange" class="w-full px-4 py-2 border rounded-xl dark:bg-gray-700 dark:border-gray-600 dark:text-white">
      </div>
      <div>
        <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-1">Email</label>
        <input type="email" x-model="form.email" @input="checkChange" class="w-full px-4 py-2 border rounded-xl dark:bg-gray-700 dark:border-gray-600 dark:text-white">
      </div>

      <div class="flex space-x-4" x-show="changed">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold">Save</button>
        <button type="button" @click="resetForm" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-xl font-semibold">Reset</button>
      </div>
    </form>
  </div>

  <script>
    function profileForm(initialData) {
      return {
        initial: { ...initialData },
        form: { ...initialData },
        changed: false,

        checkChange() {
          this.changed = JSON.stringify(this.form) !== JSON.stringify(this.initial);
          window.hasUnsavedChanges = this.changed;
        },

        resetForm() {
          this.form = { ...this.initial };
          this.changed = false;
          window.hasUnsavedChanges = false;
        },

        saveChanges() {
          fetch("{{ route('profile.update') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
              _method: 'PUT',
              name: this.form.name,
              email: this.form.email
            })
          })
          .then(response => {
            if (!response.ok) throw new Error('Update failed');
            return response.json();
          })
          .then(data => {
            this.initial = { ...this.form };
            this.changed = false;
            window.hasUnsavedChanges = false;
            alert('Profile updated successfully');
          })
          .catch(err => {
            alert('Failed to update profile');
          });
        }
      }
    }
  </script>
</body>
</html>
    