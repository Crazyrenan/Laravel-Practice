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
        <canvas id="statusChart" class="w-full h-auto"></canvas>
    </div>

    <!-- Navigation Buttons -->
    <div class="mt-10 flex flex-wrap justify-center gap-4 px-6">
        <a href="/home" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-md shadow">🏠 Home</a>
        <a href="/vendor2" class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold px-5 py-2 rounded-md shadow">📦 Vendor</a>
        <a href="/order2" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-5 py-2 rounded-md shadow">🚚 Order By Month</a>
        <a href="/product2" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-md shadow">📊 Avg Product</a>
    </div>

    <!-- Table for Status Details -->
    <div id="detailTableWrapper" class="hidden mt-10">
    <h2 class="text-center font-bold text-xl text-gray-800 dark:text-white mb-4">
        Detail Status: <span id="statusName "></span>
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl shadow">
        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Item Code</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Purchase Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Expected Delivery Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Status</th>
            </tr>
        </thead>
        <tbody id="detailTableBody" class="divide-y divide-gray-200 dark:divide-gray-600">
            <!-- Filled via JS -->
        </tbody>
        </table>
    </div>
    </div>


    <script>
        $(document).ready(function () {
            $.ajax({
                url: '{{ url("/api/pembelian/status2") }}',
                method: 'GET',
                success: function (data) {
                    const ctx = document.getElementById('statusChart').getContext('2d');
                    const statusLabels = Object.keys(data);
                    const statusCounts = Object.values(data);

                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: statusLabels,
                            datasets: [{
                                label: 'Jumlah Status',
                                data: statusCounts,
                                backgroundColor: ['#F7374F', '#F77F00', '#FCBF49']
                            }]
                        },
                        options: {
                            responsive: true,
                            onClick: function (event, elements) {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const status = statusLabels[index];
                                    fetchStatusDetail(status);
                                }
                            },
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

            function fetchStatusDetail(status) {
                $.ajax({
                    url: `/api/pembelian/status2/detail/${status}`,
                    method: 'GET',
                    success: function (data) {
                        const tbody = $('#detailTableBody');
                        tbody.empty();

                        if (data.length === 0) {
                            tbody.append(`<tr><td colspan="4" class="text-center">No data available</td></tr>`);
                        } else {
                            data.forEach(item => {
                                tbody.append(`
                                    <tr>
                                        <td class="px-6 py-3 text-left text-white">${item.id}</td>
                                        <td class="px-6 py-3 text-left text-white">${item.item_code}</td>
                                        <td class="px-6 py-3 text-left text-white">${item.purchase_date}</td>
                                        <td class="px-6 py-3 text-left text-white">${item.expected_delivery_date}</td>
                                        <td class="px-6 py-3 text-left text-white">${item.status}</td>
                                    </tr>
                                `);
                            });
                        }

                        $('#statusName').text(status);
                        $('#detailTableWrapper').slideDown();
                    },
                    error: function (err) {
                        console.error("Gagal mengambil detail status:", err);
                    }
                });
            }
        });
    </script>
</body>
</html>
