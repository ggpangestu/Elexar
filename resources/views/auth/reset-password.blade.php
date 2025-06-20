<x-guest-layout>
    <div class="bg-white/10 backdrop-blur-md p-10 rounded-2xl shadow-xl w-full max-w-xl space-y-6">

        <h2 class="text-2xl font-semibold text-white mb-4">Reset Password</h2>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-white" />
                <x-text-input 
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    required 
                    autofocus 
                    autocomplete="username"
                    class="mt-2 block w-full bg-white/10 text-white placeholder-white border border-white/30 rounded-md px-4 py-3 shadow-sm focus:ring-white focus:border-white"
                    placeholder="you@example.com"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password Baru')" class="text-white" />
                <x-text-input 
                    id="password"
                    type="password"
                    name="password"
                    required 
                    autocomplete="new-password"
                    class="mt-2 block w-full bg-white/10 text-white placeholder-white border border-white/30 rounded-md px-4 py-3 shadow-sm focus:ring-white focus:border-white"
                    placeholder="********"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-white" />
                <x-text-input 
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required 
                    autocomplete="new-password"
                    class="mt-2 block w-full bg-white/10 text-white placeholder-white border border-white/30 rounded-md px-4 py-3 shadow-sm focus:ring-white focus:border-white"
                    placeholder="********"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-300" />
            </div>

            <div class="flex justify-end">
                <x-primary-button class="bg-white/20 hover:bg-white/30 text-white px-6 py-2 rounded-md">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
