@extends('layouts.admin')

@section('title', 'ADMIN DASHBOARD')

@section('content')
<h1 class="text-3xl font-bold mb-4">Dashboard Admin</h1>

<!-- Ringkasan Total -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
  <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Jumlah User</h2>
    <p class="text-3xl font-bold text-blue-500">{{ $userCount }}</p>
  </div>

  <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Jumlah Admin</h2>
    <p class="text-3xl font-bold text-green-500">{{ $adminCount }}</p>
  </div>

  <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Permintaan Meeting</h2>
    <p class="text-3xl font-bold text-red-500">34</p>
  </div>
</div>

<!-- Grafik Statistik -->
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
  <div class="flex flex-col sm:flex-row justify-between mb-4 gap-4">
    <!-- Jenis Data -->
    <div>
      <label for="dataType" class="block font-semibold text-gray-700 dark:text-gray-200 mb-1">Jenis Data</label>
      <select id="dataType" class="p-2 rounded border dark:bg-gray-700 dark:text-white">
        <option value="users" selected>Pendaftaran</option>
        <option value="meetings">Permintaan Meeting</option>
      </select>
    </div>

    <!-- Rentang Waktu -->
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
  <canvas id="singleChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('singleChart').getContext('2d');
  let chart;

  function fetchAndRenderUserChart(range) {
    fetch(`/admin/chart-data?range=${range}`)
      .then(response => response.json())
      .then(res => {
        if (chart) chart.destroy();

        chart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: res.labels,
            datasets: [{
              label: 'Pendaftaran User',
              data: res.data,
              backgroundColor: '#3B82F6',
              borderRadius: 6
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: { position: 'top' }
            },
            scales: {
              y: { beginAtZero: true }
            }
          }
        });
      })
      .catch(err => console.error('Gagal ambil data chart:', err));
  }

  // Inisialisasi awal
  fetchAndRenderUserChart('monthly');

  // Sembunyikan dropdown Jenis Data
  document.getElementById('dataType').closest('div').style.display = 'none';

  document.getElementById('timeRange').addEventListener('change', () => {
    const range = document.getElementById('timeRange').value;
    fetchAndRenderUserChart(range);
  });
</script>
@endsection
