@extends('layouts.admin')

@section('title', 'ADMIN DASHBOARD')

@section('content')
<div x-data="bookingDetailModal()">
  <h1 class="text-3xl font-bold mb-4">Dashboard Admin</h1>

  <!-- Ringkasan Total -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
      <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Jumlah User</h2>
      <p class="text-3xl font-bold text-blue-500">{{ $userCount }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
      <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Jumlah Admin</h2>
      <p class="text-3xl font-bold text-green-500">{{ $adminCount }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
      <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Total Booking</h2>
      <p class="text-3xl font-bold text-red-500">{{ $bookingCount }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
      <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Booking Diterima</h2>
      <p class="text-3xl font-bold text-emerald-500">{{ $bookingAccepted }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
      <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Booking Pending</h2>
      <p class="text-3xl font-bold text-yellow-500">{{ $bookingPending }}</p>
    </div>
  </div>

  <!-- Grafik Statistik Dinamis -->
  <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <button onclick="downloadPdf()">Download PDF</button>
    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-4">
      <div>
        <label for="dataType" class="block font-semibold text-gray-700 dark:text-gray-200 mb-1">Jenis Data</label>
        <select id="dataType" class="p-2 rounded border dark:bg-gray-700 dark:text-white">
          <option value="users" selected>Data User</option>
          <option value="bookings">Data Booking</option>
        </select>
      </div>
      <div>
        <label for="timeRange" class="block font-semibold text-gray-700 dark:text-gray-200 mb-1">Rentang Waktu</label>
        <select id="timeRange" class="p-2 rounded border dark:bg-gray-700 dark:text-white">
          <option value="daily">Per Hari</option>
          <option value="monthly" selected>Per Bulan</option>
          <option value="yearly">Per Tahun</option>
        </select>
      </div>
    </div>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Statistik</h2>
    <canvas id="mainChart" height="100"></canvas>
  </div>

  <!-- Booking Terbaru & Booking Mendatang -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
    <!-- Booking Terbaru -->
    <div 
      x-data="{ filter: 'all' }"
      class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6"
    >
      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Booking Terbaru</h2>

      <!-- Dropdown Filter -->
      <div class="mb-4">
        <label for="filter" class="mr-2 text-gray-700 dark:text-gray-300">Tampilkan:</label>
        <select
          id="filter"
          x-model="filter"
          class="px-2 py-1 border rounded dark:bg-gray-700 dark:text-gray-100"
        >
          <option value="all">Semua</option>
          <option value="pending">Pending</option>
          <option value="accepted">Diterima</option>
        </select>
      </div>

      <ul class="space-y-2">
        @foreach($latestBookings as $booking)
          @php
            $status = $booking->status->value; // pending|accepted|rejected|...
          @endphp
          <li
            x-data="{ status: '{{ $status }}' }"
            x-show="filter === 'all' || filter === status"
            class="flex justify-between border-b pb-2 transition-all duration-200"
          >
            <div>
              <span class="block">{{ $booking->user->name ?? '-' }}</span>
              <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ $booking->booking_date_time->format('d M Y H:i') }}
              </span>
            </div>
            <span class="font-medium {{
                $status === 'accepted' ? 'text-green-500' :
                ($status === 'rejected' ? 'text-red-500' : 'text-yellow-500')
              }} capitalize">
              {{ ucfirst(str_replace('_',' ',$status)) }}
            </span>
          </li>
        @endforeach
      </ul>
    </div>

    <!-- Booking Mendatang -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Booking Mendatang</h2>
      <ul class="space-y-2">
        @foreach($upcomingBookings as $booking)
          <li class="flex justify-between items-center border-b pb-2 px-2 py-1 rounded transition">
            <div>
              <p class="font-medium">
                {{ $booking->user->name ?? '-' }} - {{ $booking->booking_date_time->format('d M Y H:i') }}
              </p>
              <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">
                {{ str_replace('_', ' ', $booking->category) }}
              </p>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-emerald-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Accepted
              </span>
              <div x-data="{ detail: @js($booking) }">
                <button 
                  @click="openDetail(detail)"
                  class="text-blue-600 hover:underline text-sm"
                >
                  Lihat Detail
                </button>
              </div>
            </div>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  <!-- Modal Detail Booking -->
  <div 
    x-show="show" x-cloak
    class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center"
  >
    <div
      x-transition:enter="ease-out duration-300"
      x-transition:enter-start="opacity-0 scale-95"
      x-transition:enter-end="opacity-100 scale-100"
      x-transition:leave="ease-in duration-200"
      x-transition:leave-start="opacity-100 scale-100"
      x-transition:leave-end="opacity-0 scale-95"
      class="relative w-full max-w-md bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-2xl shadow-xl p-6"
    >
      <button @click="show = false"
              class="absolute top-3 right-4 text-gray-400 hover:text-red-500 text-xl transition">
        &times;
      </button>

      <h2 class="text-2xl font-bold mb-6 text-center">Detail Booking</h2>

      <template x-if="booking">
        <div class="space-y-3 text-sm">
          <div class="flex justify-between"><span class="font-semibold">Nama User</span><span x-text="booking.user?.name ?? '-'"></span></div>
          <div class="flex justify-between"><span class="font-semibold">Email</span><span x-text="booking.user?.email ?? '-'"></span></div>
          <div class="flex justify-between"><span class="font-semibold">Kategori</span><span x-text="booking.category.replace('_', ' ')"></span></div>
          <div class="flex justify-between"><span class="font-semibold">Waktu</span><span x-text="new Date(booking.booking_date_time).toLocaleString('id-ID')"></span></div>
          <div class="flex justify-between"><span class="font-semibold">Status</span><span x-text="booking.status" class="capitalize"></span></div>

          <div>
            <p class="font-semibold mb-1">Catatan User</p>
            <p x-text="booking.notes ?? '-'" class="italic text-gray-600 dark:text-gray-300"></p>
          </div>
          <div>
            <p class="font-semibold mb-1">Link Meeting</p>
            <template x-if="booking.meeting_link">
              <a :href="booking.meeting_link" target="_blank"
                class="text-blue-600 underline break-all"
                x-text="booking.meeting_link"></a>
            </template>
            <template x-if="!booking.meeting_link">
              <p class="italic text-gray-400">Belum tersedia</p>
            </template>
          </div>
          <div>
            <p class="font-semibold mb-1">Catatan Admin</p>
            <p x-text="booking.meeting_link_note ?? '-'"></p>
          </div>

          <template x-if="booking.reschedule_reason">
            <div>
              <p class="font-semibold mb-1">Alasan Reschedule</p>
              <p class="italic text-gray-600 dark:text-gray-300" x-text="booking.reschedule_reason"></p>
            </div>
          </template>
        </div>
      </template>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  let chart;
  const ctx = document.getElementById('mainChart').getContext('2d');

  function fetchAndRenderChart(type, range) {
    const url = type === 'users' 
      ? `/admin/chart-data?range=${range}` 
      : `/admin/chart-booking?range=${range}`;

    fetch(url)
      .then(res => res.json())
      .then(data => {
        if (chart) chart.destroy();

        const datasets = type === 'users'
          ? [{
              label: 'Data User',
              data: data.data,
              backgroundColor: '#60A5FA',
              borderRadius: 6
            }]
          : [
              {
                label: 'Diterima',
                data: data.accepted,
                backgroundColor: '#10B981',
                borderRadius: 6
              },
              {
                label: 'Ditolak',
                data: data.rejected,
                backgroundColor: '#EF4444',
                borderRadius: 6
              },
              {
                label: 'Pending',
                data: data.pending,
                backgroundColor: '#FBBF24',
                borderRadius: 6
              }
            ];

        chart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets
          },
          options: {
            responsive: true,
            plugins: {
              legend: { position: 'top' }
            },
            scales: {
              x: { stacked: type === 'bookings' },
              y: { stacked: type === 'bookings', beginAtZero: true }
            }
          }
        });
      });
  }

  fetchAndRenderChart('users', 'monthly');

  document.getElementById('dataType').addEventListener('change', function () {
    const type = this.value;
    const range = document.getElementById('timeRange').value;
    fetchAndRenderChart(type, range);
  });

  document.getElementById('timeRange').addEventListener('change', function () {
    const type = document.getElementById('dataType').value;
    const range = this.value;
    fetchAndRenderChart(type, range);
  });

  window.bookingDetailModal = function () {
    return {
      show: false,
      booking: null,
      openDetail(data) {
        this.booking = data;
        this.show = true;
      }
    }
  }

  function downloadPdf() {
      const chartBase64 = document.getElementById('mainChart').toDataURL();
      const dataType = document.getElementById('dataType').value;
      const timeRange = document.getElementById('timeRange').value;

      const form = document.createElement('form');
      form.method = 'POST';
      form.action = "{{ route('admin.bookings.export.pdf') }}";
      form.target = '_blank';

      // CSRF token
      const csrf = document.createElement('input');
      csrf.type = 'hidden';
      csrf.name = '_token';
      csrf.value = '{{ csrf_token() }}';
      form.appendChild(csrf);

      // Chart image
      const chartInput = document.createElement('input');
      chartInput.type = 'hidden';
      chartInput.name = 'chart';
      chartInput.value = chartBase64;
      form.appendChild(chartInput);

      // Data type (users / bookings)
      const dataTypeInput = document.createElement('input');
      dataTypeInput.type = 'hidden';
      dataTypeInput.name = 'data_type';
      dataTypeInput.value = dataType;
      form.appendChild(dataTypeInput);

      // Time range (daily / monthly / yearly)
      const timeRangeInput = document.createElement('input');
      timeRangeInput.type = 'hidden';
      timeRangeInput.name = 'time_range';
      timeRangeInput.value = timeRange;
      form.appendChild(timeRangeInput);

      // Submit
      document.body.appendChild(form);
      form.submit();
      document.body.removeChild(form);
  }
</script>
@endsection
