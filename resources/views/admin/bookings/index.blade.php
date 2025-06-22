@extends('layouts.admin')

@section('title', 'Daftar Booking')

@section('content')
<div class="p-6" x-data="bookingHandler()" x-cloak>
  <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Manajemen Booking</h1>

    <div class="w-full overflow-x-auto bg-white dark:bg-gray-900 rounded-xl shadow border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm text-gray-800 dark:text-gray-300">
        <thead class="bg-indigo-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            <tr>
            <th class="whitespace-nowrap px-6 py-3 text-left">#</th>
            <th class="whitespace-nowrap px-6 py-3 text-left">User</th>
            <th class="whitespace-nowrap px-6 py-3 text-left">Kategori</th>
            <th class="whitespace-nowrap px-6 py-3 text-left">Tanggal</th>
            <th class="whitespace-nowrap px-6 py-3 text-left">Catatan</th>
            <th class="whitespace-nowrap px-6 py-3 text-left">Status</th>
            <th class="whitespace-nowrap px-6 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <td class="px-6 py-4">{{ $bookings->firstItem() + $loop->index }}</td>
                    <td class="px-6 py-4">{{ $booking->user->name ?? '-' }}</td>
                    <td class="px-6 py-4 capitalize">{{ str_replace('_', ' ', $booking->category) }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($booking->booking_date_time)->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400 max-w-xs break-words">{{ $booking->notes ?? '-' }}</td>
                    <td class="px-4 py-2 font-semibold">
                        @switch($booking->status)
                            @case(\App\Enums\BookingStatus::Pending)
                                <span class="text-yellow-600">Pending</span>
                                @break

                            @case(\App\Enums\BookingStatus::Accepted)
                                <span class="text-green-600">Diterima</span>
                                @break

                            @case(\App\Enums\BookingStatus::Rejected)
                                <span class="text-red-600">Ditolak</span>
                                @break

                            @default
                                <span class="text-gray-500 italic">{{ ucfirst($booking->status->value) }}</span>
                        @endswitch
                    </td>
                    <td class="px-6">
                        @if($booking->status === \App\Enums\BookingStatus::Pending)
                            <div class="flex flex-wrap gap-2">
                                
                                <button 
                                    class="text-green-600 hover:underline whitespace-nowrap"
                                    @click="openAcceptModal({{ $booking->id }})"
                                >
                                    Terima
                                </button>
                                <button 
                                    class="text-red-600 hover:underline whitespace-nowrap"
                                    @click.prevent="confirmReject({{ $booking->id }})"
                                >
                                    Tolak
                                </button>

                                <button
                                    @click.prevent="openRescheduleModal({{ $booking->id }}, '{{ $booking->booking_date_time }}')"
                                    class="text-blue-600 hover:underline whitespace-nowrap"
                                >
                                    Reschedule
                                </button>
                            </div>
                        @else
                            <button 
                                @click='openDetailModal(@json($booking))'
                                class="text-indigo-600 hover:underline whitespace-nowrap"
                            >
                                Detail
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada booking.</td>
            </tr>
            @endforelse
        </tbody>
        </table>

        <div class="px-6 py-4">
            {{ $bookings->onEachSide(1)->links('pagination::tailwind') }}
        </div>

        <!-- Modal Accept -->
        <div 
            x-show="showAcceptModal" 
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        >
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md space-y-4">
                <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-white">Isi Link Meeting</h2>
                <form :action="`/admin/bookings/${bookingId}/accept`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Meeting Link</label>
                        <input type="text" name="meeting_link" x-model="meetingLink" required class="w-full mt-1 p-2 rounded border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Catatan Untuk User(opsional)</label>
                        <textarea name="meeting_link_note" x-model="meetingNote" class="w-full mt-1 p-2 rounded border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="close()" class="text-gray-500 hover:underline">Batal</button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Kirim</button>
                    </div>
                </form>
            </div>
        </div>


        {{-- Modal Reschedule --}}
        <div x-cloak>
            <!-- Overlay -->
            <div x-show="showModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
                <!-- Modal Box -->
                <div class="bg-white dark:bg-gray-800 text-black dark:text-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
                    <button @click="close()" class="absolute top-2 right-3 text-lg text-gray-500 dark:text-white/50 hover:text-red-500">&times;</button>

                    <h2 class="text-xl font-bold mb-4">Reschedule Booking</h2>

                    <form :action="`/admin/bookings/${bookingId}/reschedule`" method="POST">
                        @csrf
                        @method('PUT')

                        <label class="block text-sm font-medium mb-1">Tanggal & Waktu Baru</label>
                        <input type="text" name="booking_date_time" x-ref="datetimeInput" class="w-full px-3 py-2 rounded border border-gray-300 focus:ring dark:bg-gray-700 dark:border-gray-600" required>

                        <label class="block mt-4 text-sm font-medium mb-1">Alasan Penjadwalan Ulang</label>
                        <textarea name="reschedule_reason" rows="3" class="w-full px-3 py-2 rounded border border-gray-300 focus:ring dark:bg-gray-700 dark:border-gray-600" required></textarea>

                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Meeting Link</label>
                        <input type="text" name="pending_meeting_link" x-model="rescheduleLink" required class="w-full mt-1 p-2 rounded border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">

                        <button type="submit" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                            Simpan Jadwal Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>


    
        <!-- Modal Detail Booking -->
        <div x-show="showDetailModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-800"
            :class="{ 'opacity-0 pointer-events-none': !showDetailModal, 'opacity-100': showDetailModal }"
            @keydown.escape.window="showDetailModal = false"
        >
            <div
                class="relative w-full max-w-md bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-2xl shadow-xl p-6 transform transition-all duration-300 scale-95 opacity-0"
                :class="{ 'scale-100 opacity-100': showDetailModal }"
            >
                <!-- Tombol Close -->
                <button @click="showDetailModal = false"
                        class="absolute top-3 right-4 text-gray-400 hover:text-red-500 text-xl transition">
                &times;
                </button>

                <!-- Judul Modal -->
                <h2 class="text-2xl font-bold mb-6 text-center">Detail Booking</h2>

                <!-- Isi Modal -->
                <template x-if="selectedBooking">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600 dark:text-gray-300">Nama User</span>
                            <span x-text="selectedBooking.user?.name ?? '-'" class="text-right font-medium"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600 dark:text-gray-300">Email</span>
                            <span x-text="selectedBooking.user?.email ?? '-'" class="text-right font-medium"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600 dark:text-gray-300">Kategori</span>
                            <span x-text="selectedBooking.category.replace('_', ' ')" class="text-right font-medium capitalize"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600 dark:text-gray-300">Waktu</span>
                            <span x-text="new Date(selectedBooking.booking_date_time).toLocaleString('id-ID')" class="text-right font-medium"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600 dark:text-gray-300">Status</span>
                            <span x-text="selectedBooking.status" class="text-right font-medium capitalize"></span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600 dark:text-gray-300 mb-1">Catatan User</p>
                            <p class="text-gray-700 dark:text-gray-300 italic" x-text="selectedBooking.notes ?? '-'"></p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600 dark:text-gray-300 mb-1">Link Meeting</p>
                            <template x-if="selectedBooking.meeting_link">
                                <a :href="selectedBooking.meeting_link" target="_blank"
                                class="text-blue-600 dark:text-blue-400 underline break-all"
                                x-text="selectedBooking.meeting_link"></a>
                            </template>
                            <template x-if="!selectedBooking.meeting_link">
                                <p class="italic text-gray-400">Belum tersedia</p>
                            </template>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600 dark:text-gray-300 mb-1">Catatan Admin</p>
                            <p class="text-gray-700 dark:text-gray-300 italic" x-text="selectedBooking.meeting_link_note ?? '-'"></p>
                        </div>
                        <template x-if="selectedBooking.reschedule_reason">
                        <div>
                            <p class="font-semibold text-gray-600 dark:text-gray-300 mb-1">Alasan Reschedule</p>
                            <p class="text-gray-700 dark:text-gray-300 italic" x-text="selectedBooking.reschedule_reason"></p>
                        </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
<script>
    function bookingHandler() {
        return {
            // Accept Modal
            showAcceptModal: false,
            bookingId: null,
            meetingLink: '',
            meetingNote: '',

            // Reschedule Modal
            showModal: false,

            selectedBooking: null,
            showDetailModal: false,

            openDetailModal(booking) {
                this.selectedBooking = booking;
                this.showDetailModal = true;
            },

            openAcceptModal(id) {
                this.showAcceptModal = true;
                this.bookingId = id;
                this.meetingLink = '';
                this.meetingNote = '';
            },

            openRescheduleModal(id, currentDateTime) {
                this.bookingId = id;
                this.showModal = true;

                // Delay untuk memastikan input sudah muncul di DOM
                setTimeout(() => {
                    flatpickr(this.$refs.datetimeInput, {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        defaultDate: currentDateTime,
                        minDate: new Date().fp_incr(1),
                        time_24hr: true,
                        plugins: [new confirmDatePlugin({
                            confirmText: "Simpan",
                            showAlways: true,
                            theme: "light"
                        })]
                    });
                }, 50);
            },

            close() {
                this.showModal = false;
                this.showAcceptModal = false;
            },

            confirmReject(id) {
                Swal.fire({
                    title: 'Yakin ingin menolak booking ini?',
                    text: "Tindakan ini tidak dapat dibatalkan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e53e3e',
                    cancelButtonColor: '#718096',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buat form dan submit otomatis
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/bookings/${id}/reject`;

                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        form.innerHTML = `
                            <input type="hidden" name="_token" value="${token}">
                            <input type="hidden" name="_method" value="PUT">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        }
    }
</script>
@endsection
