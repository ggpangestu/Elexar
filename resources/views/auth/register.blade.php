<x-guest-layout>
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md rounded-2xl shadow-xl p-8 text-white">
        <h2 class="text-3xl font-bold text-center mb-6">Daftar Akun Baru</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" class="text-white" />
                <x-text-input id="name" class="block mt-1 py-2 px-2 w-full bg-white/20 border-none text-white placeholder-white focus:ring-white focus:border-white"
                              type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-200" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" class="text-white" />
                <x-text-input id="email" class="block mt-1 py-2 px-2 w-full bg-white/20 border-none text-white placeholder-white focus:ring-white focus:border-white"
                              type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-200" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="text-white" />
                <x-text-input id="password" class="block mt-1 py-2 px-2 w-full bg-white/20 border-none text-white placeholder-white focus:ring-white focus:border-white"
                              type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-200" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white" />
                <x-text-input id="password_confirmation" class="block mt-1 py-2 px-2 w-full bg-white/20 border-none text-white placeholder-white focus:ring-white focus:border-white"
                              type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-200" />
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-3 mt-6">
                <x-primary-button class="w-full py-3 justify-center bg-white/10 hover:bg-white/20 focus:ring-white">
                    {{ __('Register') }}
                </x-primary-button>

                <a href="{{ route('google.login') }}"
                   class="w-full flex items-center justify-center gap-2 border border-white/30 text-white rounded px-4 py-2 hover:bg-white/20 transition">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                    Register with Google
                </a>

                <a class="text-sm text-white text-center underline hover:text-gray-100"
                   href="{{ route('login') }}">
                    {{ __('Sudah punya akun? Masuk di sini') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
