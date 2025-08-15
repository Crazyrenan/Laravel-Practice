<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Jumlah Order per Bulan — Dashboard</title>

  {{-- Tailwind via Vite --}}
  @vite('resources/css/app.css')

  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    :root {
      --glass-border: rgba(255, 255, 255, 0.08);
    }

    /* Glass panels */
    .glass {
      background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border: 1px solid var(--glass-border);
      border-radius: 14px;
    }

    /* Entrance animation */
    .mount-up {
      transform: translateY(10px);
      opacity: 0;
      animation: mountUp 500ms cubic-bezier(.2,.9,.3,1) forwards;
    }
    @keyframes mountUp {
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    /* Hover lift for nav buttons */
    .nav-pill {
      transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
    }
    .nav-pill:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    /* Dark mode */
    .dark .glass {
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.008));
      border-color: rgba(255,255,255,0.04);
    }
  </style>
</head>
<body class="antialiased bg-gray-50 dark:bg-[#0b0b0f] text-slate-900 dark:text-slate-100">

  <!-- Sticky Navbar -->
  <header class="sticky top-0 z-50 backdrop-blur-md bg-white/60 dark:bg-[#06060a]/60 border-b border-white/10 dark:border-white/6">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
      <!-- Logo -->
      <a href="/home" class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-violet-600 to-pink-500 flex items-center justify-center text-white shadow-sm">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M3 12h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <span class="text-lg font-semibold">All My Nodes</span>
      </a>

      <!-- Navigation -->
      <nav class="hidden sm:flex items-center gap-2">
        <a href="/home" class="nav-pill px-3 py-2 rounded-md text-sm hover:bg-white/10 dark:hover:bg-white/5">Home</a>
        <a href="/vendor2" class="nav-pill px-3 py-2 rounded-md text-sm hover:bg-white/10 dark:hover:bg-white/5">Vendor</a>
        <a href="/status2" class="nav-pill px-3 py-2 rounded-md text-sm hover:bg-white/10 dark:hover:bg-white/5">Status</a>
        <a href="/order2" class="nav-pill px-3 py-2 rounded-md text-sm hover:bg-white/10 dark:hover:bg-white/5">Order By Month</a>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-4xl mx-auto px-6 mt-12">
    <!-- Title -->
    <section class="text-center mount-up">
      <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Jumlah Order per Bulan</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Statistik bulanan dari pembelian — ringkas dan mudah dibaca.</p>
    </section>

    <!-- Chart -->
    <section class="mt-8 glass p-6 shadow-lg mount-up" style="animation-delay: 100ms;">
      <canvas id="ordersChart" class="w-full" height="220"></canvas>
    </section>

    <!-- Nav Buttons -->
    <section class="mt-8 flex flex-wrap justify-center gap-4 mount-up" style="animation-delay: 200ms;">
      <a href="/home" class="px-5 py-2 rounded-md bg-white/90 dark:bg-white/10 text-slate-900 dark:text-white glass nav-pill">🏠 Home</a>
      <a href="/vendor2" class="px-5 py-2 rounded-md bg-cyan-600 text-white nav-pill">📦 Vendor</a>
      <a href="/status2" class="px-5 py-2 rounded-md bg-pink-600 text-white nav-pill">📈 Status</a>
      <a href="/order2" class="px-5 py-2 rounded-md bg-green-600 text-white nav-pill">🚚 Order By Month</a>
    </section>
  </main>

  <div id="detailTableWrapper" class="hidden mt-14 max-w-5xl mx-auto animate-fade-up">
      <h2 class="text-center font-bold text-xl mb-6">Detail Order Bulan: 
          <span id="selectedMonth" class="text-indigo-500"></span>
      </h2>
      <div class="overflow-x-auto bg-white/60 dark:bg-gray-800/60 
                  backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
          <table class="min-w-full rounded-xl overflow-hidden">
              <thead class="bg-gray-100/80 dark:bg-gray-700/80">
                  <tr>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Item Name</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Item Code</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Purchase Date</th>
                  </tr>
              </thead>
              <tbody id="detailTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                  <!-- Dynamic Rows -->
              </tbody>
          </table>
      </div>
  </div>

</div>

<!-- Simple animations -->
<style>
@keyframes fade-up {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}
@keyframes fade-in {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
.animate-fade-up { animation: fade-up 0.6s ease forwards; }
.animate-fade-in { animation: fade-in 0.8s ease forwards; }
</style>

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
