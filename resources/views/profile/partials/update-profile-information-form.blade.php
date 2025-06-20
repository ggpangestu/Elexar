<section class="bg-white/5 backdrop-blur-md p-6 rounded-2xl shadow-xl text-white space-y-6">
    <header>
        <h2 class="text-xl font-bold">Informasi Profil</h2>
        <p class="text-sm text-gray-300">
            Perbarui nama pengguna dan alamat email akun Anda.
        </p>
    </header>

    <!-- Form untuk kirim ulang email verifikasi -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Komponen Alpine.js untuk kontrol input -->
    <div 
        x-data="{
            initialName: '{{ old('name', $user->name) }}',
            initialEmail: '{{ old('email', $user->email) }}',
            passwordNull: {{ $user->password ? 'false' : 'true' }},
            name: '{{ old('name', $user->name) }}',
            email: '{{ old('email', $user->email) }}',
            reset() {
                this.name = this.initialName;
                this.email = this.initialEmail;
            },
            isChanged() {
                return this.name !== this.initialName || this.email !== this.initialEmail;
            },
            emailChanged() {
                return this.email !== this.initialEmail;
            }
        }"
        x-init="$watch('name', value => {}); $watch('email', value => {})"
    >
        <!-- Form Update Profil -->
        <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Field Nama -->
            <div>
                <x-input-label for="name" :value="__('Nama')" class="text-white"/>
                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-1 p-2 block w-full bg-white/10 border border-white/30 text-white placeholder-white rounded-md shadow-sm focus:ring-white focus:border-white"
                    x-model="name"
                    required
                    autocomplete="name"
                />
                <x-input-error class="mt-2 text-red-300" :messages="$errors->get('name')" />
            </div>

            <!-- Field Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-white"/>
                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    class="mt-1 p-2 block w-full bg-white/10 border border-white/30 text-white placeholder-white rounded-md shadow-sm focus:ring-white focus:border-white"
                    x-model="email"
                    required
                    autocomplete="username"
                />
                <x-input-error class="mt-2 text-red-300" :messages="$errors->get('email')" />
            </div>

            <!-- Notifikasi perubahan data -->
            <div x-show="isChanged()" x-transition class="text-yellow-200 text-sm bg-yellow-500/10 border border-yellow-400 rounded-md px-4 py-3">
                Anda akan mengubah data profil. Pastikan untuk menyimpan perubahan.
            </div>

            <!-- Tombol Simpan dan Batal -->
            <div class="flex items-center gap-4" x-show="isChanged()" x-transition>
                <button type="submit"
                    class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-md border border-white">
                    Simpan
                </button>

                <button type="button"
                    @click="reset"
                    class="bg-red-500/20 hover:bg-red-500/40 text-white px-4 py-2 rounded-md border border-red-500">
                    Batal
                </button>
            </div>


            <!-- Status simpan berhasil -->
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-300 mt-2"
                >✔️ Berhasil disimpan</p>
            @endif
        </form>
    </div>
</section>