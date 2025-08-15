<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Status Count — Dashboard</title>

  {{-- Tailwind via Vite --}}
  @vite('resources/css/app.css')

  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    :root {
      --glass: rgba(255,255,255,0.06);
      --glass-border: rgba(255,255,255,0.08);
    }
    .glass {
      background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border: 1px solid var(--glass-border);
      border-radius: 14px;
    }
    .mount-up {
      transform: translateY(8px);
      opacity: 0;
      animation: mountUp 520ms cubic-bezier(.2,.9,.3,1) forwards;
    }
    @keyframes mountUp {
      to { transform: translateY(0); opacity: 1; }
    }
    .nav-pill {
      transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
    }
    .nav-pill:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 30px rgba(15,23,42,0.12);
    }
    .chart-shell {
      border-radius: 12px;
      padding: 1.25rem;
      box-shadow: 0 8px 30px rgba(2,6,23,0.12);
    }
    .dark .glass {
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.008));
      border-color: rgba(255,255,255,0.04);
    }
  </style>
</head>
<body class="antialiased bg-gray-50 dark:bg-[#0b0b0f] text-slate-900 dark:text-slate-100">

  <!-- Sticky Header -->
  <header class="sticky top-0 z-50 backdrop-blur-sm bg-white/60 dark:bg-[#06060a]/60 border-b border-white/10 dark:border-white/6">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
      <a href="/home" class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-violet-600 to-pink-500 flex items-center justify-center text-white shadow-sm">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 12h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <div class="text-lg font-semibold">All My Nodes</div>
      </a>
       <div class="flex items-center gap-3">
        <nav class="hidden sm:flex items-center gap-2">
          <a href="/home" class="nav-pill px-3 py-2 rounded-md text-sm bg-transparent hover:bg-white/6 dark:hover:bg-white/4">Home</a>
          <a href="/vendor2" class="nav-pill px-3 py-2 rounded-md text-sm">Vendor</a>
          <a href="/order2" class="nav-pill px-3 py-2 rounded-md text-sm">Order By Month</a>
          <a href="/product2" class="nav-pill px-3 py-2 rounded-md text-sm">Avg Product</a>
        </nav>
      </div>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-6 mt-12">
    <!-- Title -->
    <section class="text-center mount-up">
      <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Status Count</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Jumlah order per status — klik untuk detail.</p>
    </section>

    <!-- Chart -->
    <section class="mt-8 glass chart-shell mount-up" style="animation-delay: 80ms;">
      <canvas id="statusChart" class="w-full" height="220"></canvas>
    </section>

    <!-- Navigation Buttons -->
    <section class="mt-8 flex flex-wrap justify-center gap-4 mount-up" style="animation-delay: 160ms;">
      <a href="/home" class="px-5 py-2 rounded-md bg-blue-500 text-white nav-pill">🏠 Home</a>
      <a href="/vendor2" class="px-5 py-2 rounded-md bg-cyan-500 text-white nav-pill">📦 Vendor</a>
      <a href="/order2" class="px-5 py-2 rounded-md bg-pink-500 text-white nav-pill">🚚 Order By Month</a>
      <a href="/product2" class="px-5 py-2 rounded-md bg-green-500 text-white nav-pill">📊 Avg Product</a>
    </section>

    <!-- Detail Table -->
    <section id="detailTableWrapper" class="hidden mt-12 glass p-6 mount-up" style="animation-delay: 240ms;">
      <h2 class="text-center font-bold text-xl mb-6">
        Detail Status: <span id="statusName" class="text-violet-500"></span>
      </h2>
      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 dark:border-gray-700 rounded-xl overflow-hidden">
          <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase">Item Code</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase">Purchase Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase">Expected Delivery Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase">Status</th>
            </tr>
          </thead>
          <tbody id="detailTableBody" class="divide-y divide-gray-200 dark:divide-gray-600">
            <!-- Filled via JS -->
          </tbody>
        </table>
      </div>
    </section>
  </main>

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
