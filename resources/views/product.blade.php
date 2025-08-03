<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rata-rata Selisih Hari per Kategori</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/css/app.css')
 <body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white font-sans">

  <!-- Navigation Bar -->
  <header class="bg-white dark:bg-gray-800 shadow-sm w-full sticky top-0 z-10">
    <div class="max-w-6xl mx-auto px-6 py-3 flex justify-between items-center">
      <h1 class="text-xl sm:text-2xl font-bold">📊 All My Nodes</h1>
      <button id="toggleDark" class="bg-black text-white dark:bg-white dark:text-black px-4 py-2 rounded transition hover:opacity-80">
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
    <canvas id="kategoriChart" class="w-full h-auto"></canvas>
  </div>

  <!-- Navigation Buttons -->
  <div class="mt-10 flex flex-wrap justify-center gap-4 px-6">
    <a href="/home" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-md shadow">🏠 Home</a>
    <a href="/vendor" class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold px-5 py-2 rounded-md shadow">📦 Vendor</a>
    <a href="/status" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-5 py-2 rounded-md shadow">📈 Status</a>
    <a href="/order" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-md shadow">🚚 Order By Month</a>
  </div>

  <!-- Dark Mode Script -->
  <script>
    const html = document.documentElement;
    const toggleDark = document.getElementById('toggleDark');
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') html.classList.add('dark');
    toggleDark.addEventListener('click', () => {
      const isDark = html.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });
  </script>
    <script>
        $(document).ready(function () {
            $.ajax({
                url: '{{ url ("api/pembelian/Selisih") }}',
                method: 'GET',
                success: function (data) {
                    const labels = data.map(item => item.category);
                    const values = data.map(item => parseFloat(item.average_days));

                    const ctx = document.getElementById('kategoriChart').getContext('2d');
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
                                        label: function(context) {
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
