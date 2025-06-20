<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-white/10 backdrop-blur-md p-8 rounded-xl shadow-xl w-full max-w-md space-y-6">

            <div class="text-sm text-gray-300 leading-relaxed">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="text-sm text-green-400" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-white" />
                    <x-text-input 
                        id="email"
                        name="email"
                        type="email"
                        class="mt-2 block w-full bg-white/10 text-white placeholder-white border border-white/30 rounded-md px-4 py-3 shadow-sm focus:ring-white focus:border-white"
                        placeholder="you@example.com"
                        :value="old('email')" 
                        required 
                        autofocus 
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
                </div>

                <div class="flex justify-between items-center">
                    <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-black/80">
                        ⬅️ Kembali ke Halaman Login
                    </a>

                    <x-primary-button class="bg-white/20 hover:bg-white/30 text-white">
                        {{ __('Kirim Link Reset Password') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
