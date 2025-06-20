@php $hasPassword = !empty(Auth::user()->password); @endphp

<form method="POST"
      action="{{ route('password.update') }}"
      x-ref="form"
      x-data="passwordForm()"
      @submit.prevent="handleSubmit"
      class="space-y-5">
    @csrf
    @method('PUT')

    {{-- Password Lama (jika sudah ada) --}}
    @if ($hasPassword)
        <div>
            <x-input-label for="current_password" value="Password Lama" class="text-white" />
            <x-text-input
                id="current_password"
                name="current_password"
                type="password"
                x-model="current"
                class="mt-1 p-2 block w-full bg-white/10 border border-white/30 text-white rounded-md shadow-sm focus:ring-white focus:border-white"
                required
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-300" />
        </div>
    @endif

    {{-- Password Baru --}}
    <div>
        <x-input-label for="password" value="Password Baru" class="text-white" />
        <x-text-input
            id="password"
            name="password"
            type="password"
            minlength="8"
            x-model="password"
            @input="validate()"
            class="mt-1 p-2 block w-full bg-white/10 border border-white/30 text-white rounded-md shadow-sm focus:ring-white focus:border-white"
            required
        />
        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-300" />
    </div>

    {{-- Konfirmasi Password --}}
    <div>
        <x-input-label for="password_confirmation" value="Konfirmasi Password" class="text-white" />
        <x-text-input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            minlength="8"
            x-model="confirmation"
            @input="validate()"
            class="mt-1 p-2 block w-full bg-white/10 border border-white/30 text-white rounded-md shadow-sm focus:ring-white focus:border-white"
            required
        />
        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-300" />
    </div>

    {{-- Pesan jika password dan konfirmasi tidak sama --}}
    <template x-if="error">
        <div class="text-red-300 text-sm" x-text="error"></div>
    </template>

    {{-- Tombol Simpan --}}
    <div class="flex justify-start">
        <button
            type="submit"
            class="bg-white/20 hover:bg-white/30 text-white border border-white px-4 py-2 rounded-md"
        >
            Simpan
        </button>
    </div>
</form>
