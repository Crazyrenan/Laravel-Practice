<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Status Count</title>
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
        <canvas id="statusChart" class="w-full h-auto"></canvas>
    </div>

    <!-- Navigation Buttons -->
    <div class="mt-10 flex flex-wrap justify-center gap-4 px-6">
        <a href="/home" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-md shadow">🏠 Home</a>
        <a href="/vendor" class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold px-5 py-2 rounded-md shadow">📦 Vendor</a>
        <a href="/order" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-5 py-2 rounded-md shadow">🚚 Order By Month</a>
        <a href="/product" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-md shadow">📊 Avg Product</a>
    </div>

    <script>
        $(document).ready(function () {
            $.ajax({
                url: '{{ url("/api/pembelian/status") }}',
                method: 'GET',
                success: function (data) {
                    const ctx = document.getElementById('statusChart').getContext('2d');
                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(data),
                            datasets: [{
                                label: 'Jumlah Status',
                                data: Object.values(data),
                                backgroundColor: ['#F7374F', '#F77F00', '#FCBF49']
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                },
                error: function (err) {
                    console.error("Gagal mengambil data status:", err);
                }
            });
        });
    </script>
</body>
</html>
