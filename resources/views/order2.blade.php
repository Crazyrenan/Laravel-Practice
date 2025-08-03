<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Bulanan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/css/app.css')
<div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-6 px-4">
  <div class="max-w-6xl mx-auto">

    <!-- Background-->
<div class="fixed inset-0 z-0">
   <img src="{{ asset('img/143.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-30"/>
    <div class="absolute inset-0 bg-black/40"></div> <!-- Dark overlay -->
</div>
  
    <!-- Navigation Bar -->
    <header class="sticky top-0 z-0 w-full backdrop-blur-md bg-white/60 dark:bg-gray-900/60 shadow-sm border-b border-white/20 dark:border-gray-700">
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
    <div class="relative z-10 text-center mb-10">
      <h2 class="text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">Jumlah Order per Bulan</h2>
      <p class="text-gray-500 dark:text-gray-400 text-sm">Statistik bulanan dari pembelian</p>
    </div>

    <!-- Chart -->
    <div class="relative z-10 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg" data-aos="fade-in-up">
      <canvas id="ordersChart" class="w-full h-auto"></canvas>
    </div>

    <!-- Buttons -->
    <div class="relative z-10 mt-10 flex flex-wrap justify-center gap-4 px-6">
        <a href="/home" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-md shadow">🏠 Home</a>
        <a href="/vendor2" class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold px-5 py-2 rounded-md shadow">📦 Vendor</a>
        <a href="/status2" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-5 py-2 rounded-md shadow">📈 Status</a>
        <a href="/product2" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-md shadow">📊 Avg Product</a>
    </div>

    <!-- Detail Table -->
    <div id="detailTableWrapper" class="hidden mt-10" data-aos="fade-in-up">
      <h2 class="text-center font-bold text-xl text-gray-800 dark:text-white mb-4">Detail Order Bulan: <span id="selectedMonth"></span></h2>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl">
          <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-white-500 dark:text-gray-300 uppercase">Item Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-white-500 dark:text-gray-300 uppercase">Item Code</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-white-500 dark:text-gray-300 uppercase">Purchase Date</th>
            </tr>
          </thead>
          <tbody id="detailTableBody" class="relative z-10 divide-y divide-gray-200 dark:divide-gray-600">
            <!-- Dynamic Content -->
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

    <script data-aos="zoom-in">
        const monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        $(document).ready(function () {
            $.ajax({
                url: '{{ url("/api/pembelian/ordermonth2") }}', 
                method: 'GET',
                success: function (data) {
                    data.sort((a, b) => a.month - b.month);
                    const labels = data.map(entry => monthNames[entry.month - 1]);
                    const values = data.map(entry => entry.total);

                    const ctx = document.getElementById('ordersChart').getContext('2d');
                    const chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Order',
                                data: values,
                                borderColor: '#fbbf24',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                pointBackgroundColor: '#111827',
                                pointRadius: 6,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            onClick: function (event, elements) {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const month = index + 1;

                                    $.ajax({
                                        url: `/api/pembelian/month2/detail/${month}`,
                                        method: 'GET',
                                        success: function (details) {
                                            const tbody = $('#detailTableBody');
                                            tbody.empty();

                                            if (details.length === 0) {
                                                tbody.append(`<tr><td colspan="3" class="text-center">No data</td></tr>`);
                                            } else {
                                                details.forEach(item => {
                                                    tbody.append(`
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">${item.item_name}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">${item.item_code}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">${item.purchase_date}</td>
                                                        </tr>
                                                    `);
                                                });
                                            }

                                            $('#selectedMonth').text(monthNames[month - 1]);
                                            $('#detailTableWrapper').slideDown();
                                        },
                                        error: function (err) {
                                            console.error("Gagal ambil detail bulan:", err);
                                        }
                                    });
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Jumlah Order'
                                    }
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>

   <!-- AOS Animation -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      once: true, // animation only once
      duration: 800, // animation duration in ms
    });
  </script>
</body>
</html>
