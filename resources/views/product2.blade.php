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
    /* Minimal custom CSS to complement Tailwind */
    :root{
      --glass: rgba(255,255,255,0.06);
      --glass-border: rgba(255,255,255,0.08);
      --accent: #7c3aed; /* violet-ish accent */
    }

    /* Glass container with subtle blur */
    .glass {
      background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border: 1px solid var(--glass-border);
      border-radius: 14px;
    }

    /* Soft entrance */
    .mount-up {
      transform: translateY(8px);
      opacity: 0;
      animation: mountUp 520ms cubic-bezier(.2,.9,.3,1) forwards;
    }
    @keyframes mountUp {
      to { transform: translateY(0); opacity: 1; }
    }

    /* Fancy subtle focus for nav buttons */
    .nav-pill {
      transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
    }
    .nav-pill:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(15,23,42,0.12); }

    /* Chart container shadow / border */
    .chart-shell {
      border-radius: 12px;
      padding: 1.25rem;
      box-shadow: 0 8px 30px rgba(2,6,23,0.12);
    }

    /* Dark mode tweaks for custom elements */
    .dark .glass { background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.008)); border-color: rgba(255,255,255,0.04); }
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
          <a href="/status2" class="nav-pill px-3 py-2 rounded-md text-sm">Status</a>
          <a href="/order2" class="nav-pill px-3 py-2 rounded-md text-sm">Order By Month</a>
        </nav>
      </div>
    </div>
  </header>

  <main class="max-w-4xl mx-auto px-6 mt-12">
    <section class="text-center mount-up">
      <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Jumlah Order per Bulan</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Statistik bulanan dari pembelian — ringkas dan mudah dibaca.</p>
    </section>

    <section class="mt-8 glass chart-shell mount-up" style="animation-delay: 80ms;">
      <canvas id="categoryChart" class="w-full" height="220"></canvas>
    </section>

    <section class="mt-8 flex flex-wrap justify-center gap-4 mount-up" style="animation-delay: 160ms;">
      <a href="/home" class="px-5 py-2 rounded-md bg-white/90 dark:bg-white/6 text-slate-900 dark:text-white glass nav-pill">🏠 Home</a>
      <a href="/vendor2" class="px-5 py-2 rounded-md bg-cyan-600 text-white nav-pill">📦 Vendor</a>
      <a href="/status2" class="px-5 py-2 rounded-md bg-pink-600 text-white nav-pill">📈 Status</a>
      <a href="/order2" class="px-5 py-2 rounded-md bg-green-600 text-white nav-pill">🚚 Order By Month</a>
    </section>
  </main>


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
