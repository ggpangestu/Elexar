<x-guest-layout>
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md rounded-2xl shadow-xl p-8 text-white">
        <h2 class="text-3xl font-bold text-center mb-6">Login ke Akun Anda</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-sm text-green-200" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-white"/>
                <x-text-input id="email" class="block mt-1 py-2 px-2 w-full bg-white/20 border-none text-white placeholder-white focus:ring-white focus:border-white"
                              type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-200" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="text-white" />
                <x-text-input id="password" class="block mt-1 py-2 px-2 w-full bg-white/20 border-none text-white placeholder-white focus:ring-white focus:border-white"
                              type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-200" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded border-white/40 text-indigo-300 shadow-sm bg-white/10 focus:ring-white"
                           name="remember">
                    <span class="ms-2 text-sm text-white">{{ __('Remember me') }}</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col gap-3 mt-6">
                <x-primary-button class="w-full py-3 justify-center bg-white/10 hover:bg-white/20 focus:ring-white">
                    {{ __('Log in') }}
                </x-primary-button>

                <a href="{{ route('google.login') }}"
                   class="w-full flex items-center justify-center gap-2 border border-white/30 text-white rounded mt-3 px-4 py-2 hover:bg-white/20 transition">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                    Login with Google
                </a>

                @if (Route::has('password.request'))
                    <a class="text-sm text-white text-center underline hover:text-gray-100"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <a class="text-sm text-white text-center underline hover:text-gray-100"
                    href="{{ route('register') }}">
                    {{ __("Belum punya akun? Daftar di sini") }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
