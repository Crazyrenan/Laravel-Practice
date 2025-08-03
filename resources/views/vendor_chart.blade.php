<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vendor Chart by Month</title>
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

  <!-- Title & Dropdown -->
  <div class="relative z-10 text-center mt-10" data-aos="zoom-in">
    <h2 class="text-2xl font-bold">📅 Jumlah Order per Vendor per Bulan</h2>
<div class="flex justify-center gap-4 mt-4">
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

  <!-- Chart -->
  <div class="relative z-10 mt-6 max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow">
    <canvas id="vendorChart" width="400" height="400"></canvas>
  </div>

  <!-- Detail Table -->
  <div id="detailTableWrapper" class="relative z-10 hidden mt-10 max-w-4xl mx-auto">
    <h2 class="text-xl text-center font-bold text-gray-800 dark:text-white mb-4">
      Detail Vendor: <span id="selectedVendor"></span>
    </h2>
    <div class="overflow-x-auto">
      <table class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl shadow">
        <thead>
          <tr class="bg-gray-100 dark:bg-gray-700 text-white text-sm uppercase">
            <th class="px-4 py-2">PO Number</th>
            <th class="px-4 py-2">Item Name</th>
            <th class="px-4 py-2">Item Code</th>
            <th class="px-4 py-2">Category</th>
            <th class="px-4 py-2">Quantity</th>
          </tr>
        </thead>
        <tbody id="detailTableBody" class="text-white text-sm">
          <!-- JS will populate this -->
        </tbody>
      </table>
    </div>
  </div>

  
<div class="w-full mt-12 px-6 py-10 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl text-white" data-aos="fade-up">
  <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

    <!-- Home Card -->
    <a href="/home"
       class="flex flex-col items-center justify-center text-center bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 transition rounded-xl p-6 shadow-lg hover:shadow-xl">
      <div class="text-3xl mb-2">🏠</div>
      <h3 class="text-lg font-semibold">Home</h3>
      <p class="text-sm text-white/80">Go to main dashboard</p>
    </a>

    <!-- Profit Card -->
    <a href="/profit"
       class="flex flex-col items-center justify-center text-center bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 transition rounded-xl p-6 shadow-lg hover:shadow-xl">
      <div class="text-3xl mb-2">💸</div>
      <h3 class="text-lg font-semibold">Profit</h3>
      <p class="text-sm text-white/80">View top-selling product profits</p>
    </a>

    <!-- Profit by Category Card -->
    <a href="/profitcategory"
       class="flex flex-col items-center justify-center text-center bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 transition rounded-xl p-6 shadow-lg hover:shadow-xl">
      <div class="text-3xl mb-2">🤑</div>
      <h3 class="text-lg font-semibold">Profit by Category</h3>
      <p class="text-sm text-white/80">Analyze profit by product categories</p>
    </a>

  </div>
</div>

  </div>

  <!-- Chart Logic -->
  <script>
    let chartInstance;

    function loadChart(startDate = '', endDate = '') {
    const query = [];
    if (startDate) query.push(`start_date=${startDate}`);
    if (endDate) query.push(`end_date=${endDate}`);
    const url = `/api/pembelian/vendor-by-range${query.length ? '?' + query.join('&') : ''}`;

    $.get(url, function (data) {
      const labels = data.map(item => 'Vendor #' + item.vendor_id);
      const values = data.map(item => item.total);
      const colors = labels.map(() => `hsl(${Math.random() * 360}, 70%, 60%)`);

      const ctx = document.getElementById('vendorChart').getContext('2d');
      if (chartInstance) chartInstance.destroy();

      chartInstance = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: labels,
          datasets: [{
            label: 'Total Orders',
            data: values,
            backgroundColor: colors
          }]
        },
        options: {
          responsive: true,
          animation: {
            duration: 1000,
            easing: 'easeoutQuart',
          },
          transitions: {
            active: {
              animation: {
                duration: 500,
                easing: 'easeInOutSine'
              }
            }
          },
          plugins: {
            legend: { position: 'bottom' }
          },
          onClick: function (evt, elements) {
            if (elements.length > 0) {
              const index = elements[0].index;
              const vendor_id = data[index].vendor_id;

              const detailQuery = [];
              if (startDate) detailQuery.push(`start_date=${startDate}`);
              if (endDate) detailQuery.push(`end_date=${endDate}`);
              const detailUrl = `/api/pembelian/vendor2/detail/${vendor_id}?${detailQuery.join('&')}`;

              $.get(detailUrl, function (details) {
                const tbody = $('#detailTableBody');
                tbody.empty();

                if (details.length === 0) {
                  tbody.append(`<tr><td colspan="5" class="text-center text-white py-4">No data available</td></tr>`);
                } else {
                  details.forEach(item => {
                    tbody.append(`
                      <tr class="border-t border-gray-700 hover:bg-gray-700">
                        <td class="px-4 py-2">${item.purchase_order_number}</td>
                        <td class="px-4 py-2">${item.item_name}</td>
                        <td class="px-4 py-2">${item.item_code}</td>
                        <td class="px-4 py-2">${item.category}</td>
                        <td class="px-4 py-2">${item.quantity}</td>
                      </tr>
                    `);
                  });
                }

                $('#selectedVendor').text('Vendor #' + vendor_id);
                $('#detailTableWrapper').slideDown();
              });
            }
          }
        }
      });
    });
  }


      $(document).ready(function () {
      loadChart(); // Initial load

      $('#applyDateRange').on('click', function () {
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

        if (startDate && endDate && startDate > endDate) {
          alert('Start date cannot be after end date.');
          return;
        }

        $('#detailTableWrapper').slideUp();
        loadChart(startDate, endDate);
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
