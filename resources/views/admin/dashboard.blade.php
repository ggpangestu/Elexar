@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-4">Dashboard Admin</h1>

<!-- Ringkasan Total -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
  <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Jumlah User</h2>
    <p class="text-3xl font-bold text-blue-500">120</p>
  </div>

  <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Jumlah Admin</h2>
    <p class="text-3xl font-bold text-green-500">5</p>
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
  const datasets = {
    users: {
      daily: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        data: [5, 8, 6, 10, 7, 4, 2]
      },
      monthly: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        data: [20, 35, 40, 25, 30, 50]
      },
      yearly: {
        labels: ['2020', '2021', '2022', '2023', '2024'],
        data: [200, 350, 420, 390, 480]
      }
    },
    meetings: {
      daily: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        data: [1, 2, 0, 3, 1, 1, 0]
      },
      monthly: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        data: [5, 10, 8, 15, 12, 20]
      },
      yearly: {
        labels: ['2020', '2021', '2022', '2023', '2024'],
        data: [50, 70, 60, 90, 100]
      }
    }
  };

  const ctx = document.getElementById('singleChart').getContext('2d');
  let chart;

  function renderChart(type, range) {
    const chartData = datasets[type][range];

    const config = {
      type: 'bar',
      data: {
        labels: chartData.labels,
        datasets: [{
          label: type === 'users' ? 'Pendaftaran User' : 'Permintaan Meeting',
          data: chartData.data,
          backgroundColor: type === 'users' ? '#3B82F6' : '#EF4444',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top'
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    };

    if (chart) chart.destroy();
    chart = new Chart(ctx, config);
  }

  // Inisialisasi awal
  renderChart('users', 'monthly');

  document.getElementById('dataType').addEventListener('change', () => {
    const type = document.getElementById('dataType').value;
    const range = document.getElementById('timeRange').value;
    renderChart(type, range);
  });

  document.getElementById('timeRange').addEventListener('change', () => {
    const type = document.getElementById('dataType').value;
    const range = document.getElementById('timeRange').value;
    renderChart(type, range);
  });
</script>
@endsection
