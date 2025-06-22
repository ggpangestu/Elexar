<x-user-layout>
    <div class="relative z-10 max-w-4xl mx-auto py-10 px-4 text-white space-y-10">
        <!-- Back Button -->
        <div class="flex justify-end">
            <a href="/" class="inline-flex items-center gap-2 text-sm text-white hover:text-black/80">
                ⬅️ Kembali ke Halaman Utama
            </a>
        </div>

        <!-- Informasi Profil -->
        <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl space-y-4">
            <h2 class="text-xl font-semibold">Informasi Akun</h2>
            @include('profile.partials.update-profile-information-form')

            @if (! Auth::user()->hasVerifiedEmail())
                <div class="p-4 bg-yellow-100 text-yellow-900 rounded-md">
                    <p>Email Anda belum terverifikasi.</p>

                    @if (session('status') == 'verification-link-sent')
                        <p class="text-sm text-green-600">Email verifikasi telah dikirim ke alamat Anda.</p>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <x-primary-button>
                            Kirim Email Verifikasi
                        </x-primary-button>
                    </form>
                </div>
            @else
                <div class="p-4 bg-green-100 text-green-700 rounded-md">
                    ✅ Email Anda sudah terverifikasi.
                </div>
            @endif
        </div>

        <!-- Kontrol Password -->
        <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl">
            <h2 class="text-xl font-semibold mb-4">Setting Password</h2>
            @include('profile.partials.update-password-form')
        </div>

        <!-- Hapus Akun -->
        <div x-data="deleteForm()" x-init="init()">
            <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl">
                <h2 class="text-xl font-semibold mb-4">Hapus Akun</h2>
                @include('profile.partials.delete-user-form')
            </div>
           <!-- Modal -->
            <div 
                x-show="showConfirm"
                x-cloak 
                x-transition 
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
            >
                <div class="bg-white/20 dark:bg-gray-800 text-white rounded-2xl w-full max-w-md p-6 shadow-lg space-y-5">
                    <form method="post" action="{{ route('profile.destroy') }}" @submit.prevent="submitForm($event)">
                        @csrf
                        @method('delete')

                        <h2 class="text-xl font-semibold mb-3">
                            Apakah Anda yakin ingin menghapus akun?
                        </h2>

                        <p class="text-sm text-white dark:text-gray-300 mb-4 leading-relaxed">
                            Aksi ini <strong>tidak bisa dibatalkan</strong>. Jika Anda yakin, ketik <strong class="text-red-500">DELETE</strong>.
                        </p>

                        <!-- Input Konfirmasi -->
                        <div class="space-y-1">
                            <x-input-label for="confirmation" :value="__('Ketik DELETE')" class="text-white" />
                            <x-text-input
                                id="confirmation"
                                name="confirmation"
                                type="text"
                                x-model="confirmation"
                                class="w-full px-4 py-2 bg-white/10 border border-white/30 text-white rounded-md focus:ring-white focus:border-white placeholder:text-white/50"
                                placeholder="DELETE"
                                required
                            />
                            <template x-if="confirmation && !valid">
                                <p class="text-sm text-red-400 mt-1">Teks tidak cocok. Harus diketik persis: <strong>DELETE</strong></p>
                            </template>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-6 flex justify-end gap-3">
                            <x-secondary-button @click.prevent="showConfirm = false">
                                {{ __('Batal') }}
                            </x-secondary-button>

                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 disabled:opacity-50 transition"
                                :disabled="!valid || !buttonEnabled || submitting"
                            >
                                <template x-if="submitting">
                                    <svg class="animate-spin h-4 w-4 mr-2 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l5-5-5-5v4a10 10 0 100 20v-4l-5 5 5 5v-4a8 8 0 01-8-8z" />
                                    </svg>
                                </template>
                                <span x-text="submitting ? 'Menghapus...' : 'Hapus Akun'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if (session('email_changed'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Email Berhasil Diubah',
                        html: "{{ session('password_null') 
                            ? 'Silahkan verifikasi email baru anda untuk login menggunakan akun Google atau atur password anda untuk login manual menggunakan password.'
                            : 'Email baru harus diverifikasi sebelum dapat digunakan untuk login.' }}",
                        confirmButtonText: 'Oke',
                        confirmButtonColor: '#3085d6'
                    });
                });
            </script>
        @endif

        <script>
            function passwordForm() {
                return {
                    current: '',
                    password: '',
                    confirmation: '',
                    error: '',

                    validate() {
                        if (this.password && this.confirmation && this.password !== this.confirmation) {
                            this.error = 'Password and confirmation do not match.';
                        } else {
                            this.error = '';
                        }
                    },

                    handleSubmit() {
                        this.validate();

                        if (this.error === '') {
                            this.$refs.form.submit();
                        }
                    }
                }
            }
        </script>

        @if (session('password_updated'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('password_updated') }}',
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>
        @endif

        <script>
            function deleteForm() {
                return {
                    showConfirm: false,
                    confirmation: '',
                    buttonEnabled: false,
                    submitting: false,

                    get valid() {
                        return this.confirmation === 'DELETE';
                    },

                    init() {
                        // Reset modal state on open
                        this.$watch('showConfirm', value => {
                            if (value) {
                                this.confirmation = '';
                                this.submitting = false;
                                this.buttonEnabled = false;
                                setTimeout(() => this.buttonEnabled = true, 3000);
                            }
                        });
                    },

                    submitForm(event) {
                        if (!this.valid || this.submitting) return;
                        this.submitting = true;
                        event.target.submit();
                    }
                }
            }
        </script>

    @endpush
</x-user-layout>