<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Average Delivery Estimate by Category</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/css/app.css')
 <body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white font-sans">

    <!-- Navigation Bar -->
    <header class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/60 dark:bg-gray-900/60 shadow-sm border-b border-white/20 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
        
        <!-- Logo / Brand -->
        <a href="/home" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white hover:underline transition">
        📊 All My Nodes
        </a>

        <!-- Dark Mode Toggle -->
        <button id="toggleDark" class="px-4 py-2 rounded-md bg-black text-white dark:bg-white dark:text-black transition duration-300 hover:opacity-80 hover:scale-105">
        Toggle Dark Mode
        </button>

    </div>
    </header>

  <!-- Title -->
  <div class="text-center mt-10">
    <h2 class="text-3xl font-semibold tracking-tight mb-1">Jumlah Order per Bulan</h2>
    <p class="text-gray-500 dark:text-gray-400 text-sm">Statistik bulanan dari pembelian</p>
  </div>

  <!-- Chart Container -->
  <div class="mt-10 max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
    <canvas id="categoryChart" class="w-full h-auto"></canvas>
  </div>

  <!-- Navigation Buttons -->
  <div class="mt-10 flex flex-wrap justify-center gap-4 px-6">
    <a href="/home" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-md shadow">🏠 Home</a>
    <a href="/vendor2" class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold px-5 py-2 rounded-md shadow">📦 Vendor</a>
    <a href="/status2" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-5 py-2 rounded-md shadow">📈 Status</a>
    <a href="/order2" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-md shadow">🚚 Order By Month</a>
  </div>

    <script>
        $(document).ready(function () {
            $.ajax({
                url: '{{ url("api/pembelian/selisih2") }}',
                method: 'GET',
                success: function (data) {
                    const labels = data.map(item => item.category);
                    const values = data.map(item => parseFloat(item.expected));

                    const ctx = document.getElementById('categoryChart').getContext('2d'); 
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Rata-rata Selisih Hari',
                                data: values,
                                backgroundColor: 'rgba(233, 196, 106, 1)',
                                borderColor: 'rgba(2, 48, 71, 0,5)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Hari'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            return `${context.parsed.y.toFixed(2)} hari`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Gagal memuat data:", error);
                }
            });
        });
    </script>
</body>
</html>
