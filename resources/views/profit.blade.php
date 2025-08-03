<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profit per Product</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white font-sans">

    <!-- Background-->
<div class="fixed inset-0 z-0">
   <img src="{{ asset('img/143.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-30"/>
    <div class="absolute inset-0 bg-black/40"></div> <!-- Dark overlay -->
</div>


  <div class="relative z-10 text-center mt-10">
    <h2 class="text-2xl font-bold">💰 Profit per Product</h2>

    <!-- Date Range Filter -->
  <div class="flex justify-center gap-4 mt-6">
    <div>
      <label for="startDate" class="block mb-1 text-sm">📅 Start Date</label>
      <input type="date" id="startDate" class="px-3 py-2 rounded bg-white dark:bg-gray-800 text-black dark:text-white" />
    </div>
    <div>
      <label for="endDate" class="block mb-1 text-sm">📅 End Date</label>
      <input type="date" id="endDate" class="px-3 py-2 rounded bg-white dark:bg-gray-800 text-black dark:text-white" />
    </div>
    <div class="flex items-end">
      <button id="applyDateRange" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">
        🔄 Apply
      </button>
    </div>
</div>

  </div>

  <div class="relative z-10 mt-10 max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
    <canvas id="profitChart" height="120"></canvas>
  </div>

      <!-- Detail Table -->
  <div id="detailTableWrapper" class="relative z-10 hidden mt-10 max-w-5xl mx-auto">
    <h3 class="text-xl font-bold mb-4 text-center text-gray-800 dark:text-white">
      Detail Produk: <span id="selectedProduct"></span>
    </h3>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl shadow">
        <thead class="bg-gray-100 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-2 text-left text-white">Item Code</th>
            <th class="px-4 py-2 text-left text-white">Quantity</th>
            <th class="px-4 py-2 text-left text-white">Buy Price</th>
            <th class="px-4 py-2 text-left text-white">Sell Price</th>
            <th class="px-4 py-2 text-left text-white">Total Profit</th>
          </tr>
        </thead>
        <tbody id="detailTableBody" class="divide-y divide-gray-300 dark:divide-gray-600 text-white">
          <!-- Populated by JS -->
        </tbody>
      </table>
    </div>
  </div>
  
<!-- Glassmorphic Bottom Button Section -->
<div class="w-full mt-20 px-6 py-10 backdrop-blur-xl bg-white/10 border-t border-white/20 shadow-inner text-white" data-aos="fade-up">
  <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <!-- Home -->
    <a href="/home"
       class="flex flex-col items-center justify-center bg-white/10 hover:bg-white/20 transition rounded-xl p-6 shadow-lg border border-white/20 text-center backdrop-blur-md">
      <div class="text-3xl mb-2">🏠</div>
      <h3 class="text-lg font-semibold">Home</h3>
    </a>

    <!-- Vendor Chart -->
    <a href="/vendor_chart"
       class="flex flex-col items-center justify-center bg-white/10 hover:bg-white/20 transition rounded-xl p-6 shadow-lg border border-white/20 text-center backdrop-blur-md">
      <div class="text-3xl mb-2">🌙</div>
      <h3 class="text-lg font-semibold">Vendor by Month</h3>
    </a>

    <!-- Profit by Category -->
    <a href="/profitcategory"
       class="flex flex-col items-center justify-center bg-white/10 hover:bg-white/20 transition rounded-xl p-6 shadow-lg border border-white/20 text-center backdrop-blur-md">
      <div class="text-3xl mb-2">🤑</div>
      <h3 class="text-lg font-semibold">Profit by Category</h3>
    </a>

  </div>
</div>

  <script>
    let chartInstance;

    function loadProfitChart(startDate = '', endDate = '') {
      const url = `/api/pembelian/product-per-product?${startDate ? 'start_date=' + startDate + '&' : ''}${endDate ? 'end_date=' + endDate : ''}`;

      $.get(url, function (data) {
        const container = document.getElementById('profitChart').parentElement;

        if (!data.length) {
          if (chartInstance) chartInstance.destroy();
          container.style.display = 'none';
          $('#detailTableWrapper').slideUp();
          return;
        }

        container.style.display = 'block';

        const labels = data.map(item => item.item_name);
        const profits = data.map(item => item.total_profit);

        const ctx = document.getElementById('profitChart').getContext('2d');
        if (chartInstance) chartInstance.destroy();

        chartInstance = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Total Profit (Rp)',
              data: profits,
              backgroundColor: 'rgba(34, 197, 94, 0.7)',
              borderColor: 'rgba(22, 163, 74, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  callback: value => `Rp ${value.toLocaleString()}`
                }
              }
            },
            plugins: {
              tooltip: {
                callbacks: {
                  label: context => `Rp ${parseFloat(context.parsed.y).toLocaleString()}`
                }
              }
            },
            onClick: function (evt, elements) {
              if (elements.length > 0) {
                const index = elements[0].index;
                const itemName = labels[index];
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();

                $.get(`/api/pembelian/product-profit-detail/${encodeURIComponent(itemName)}?${startDate ? 'start_date=' + startDate + '&' : ''}${endDate ? 'end_date=' + endDate : ''}`, function (details) {
                  const tbody = $('#detailTableBody');
                  tbody.empty();

                  if (!details.length) {
                    tbody.append(`<tr><td colspan="5" class="text-center py-4">No data found</td></tr>`);
                  } else {
                    details.forEach(item => {
                      tbody.append(`
                        <tr>
                          <td class="px-4 py-2">${item.item_code}</td>
                          <td class="px-4 py-2">${item.quantity}</td>
                          <td class="px-4 py-2">Rp ${parseFloat(item.buy_price).toLocaleString()}</td>
                          <td class="px-4 py-2">Rp ${parseFloat(item.unit_price).toLocaleString()}</td>
                          <td class="px-4 py-2">Rp ${parseFloat(item.total_profit).toLocaleString()}</td>
                        </tr>
                      `);
                    });
                  }

                  $('#selectedProduct').text(itemName);
                  $('#detailTableWrapper').slideDown();
                });
              }
            }
          }
        });
      });
    }

    $(document).ready(function () {
      loadProfitChart();

      $('#applyDateRange').on('click', function () {
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

        if (startDate && endDate && startDate > endDate) {
          alert('Start date cannot be after end date.');
          return;
        }

        $('#detailTableWrapper').slideUp();
        $('#detailTableBody').empty();

        loadProfitChart(startDate, endDate);
      });
    });

  </script>
</body>
</html>
