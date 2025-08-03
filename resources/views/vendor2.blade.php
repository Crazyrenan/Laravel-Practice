<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Vendor Pie Chart</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @vite('resources/css/app.css')
  <style>
    .chart-legend-scroll {
      max-height: 300px;
      overflow-y: auto;
    }
  </style>
</head>
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
    <h2 class="text-3xl font-semibold tracking-tight mb-1">Jumlah Order per Vendor</h2>
    <p class="text-gray-500 dark:text-gray-400 text-sm">Distribusi order berdasarkan vendor</p>
  </div>

  <!-- Chart -->
  <div class="mt-10 max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
    <canvas id="vendorChart" width="400" height="400"></canvas>
  </div>

  <!-- Navigation -->
  <div class="mt-10 flex flex-wrap justify-center gap-4 px-6">
    <a href="/home" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-md shadow">🏠 Home</a>
    <a href="/status2" class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold px-5 py-2 rounded-md shadow">📋 Status</a>
    <a href="/order2" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-5 py-2 rounded-md shadow">📈 Order By Month</a>
    <a href="/product2" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-md shadow">📊 Avg Product</a>
  </div>

    <!-- Add inside <body>, below the chart -->
    <div class="mt-6 flex justify-center">
      <label for="vendorSelect" class="mr-2 font-medium">Select Vendor:</label>
      <select id="vendorSelect" class="bg-white dark:bg-gray-800 text-black dark:text-white px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600">
        <option value="">-- Choose Vendor --</option>
      </select>
    </div>

  <!-- Search (Initially Hidden) -->
  <div id="searchWrapper" class="hidden w-full max-w-sm mt-6 px-4 mx-auto">
    <input
      type="text"
      id="searchInput"
      placeholder="🔍 Search item..."
      class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 shadow-sm"
    />
  </div>

  <!-- Table -->
  <div id="detailTableWrapper" class="hidden mt-10">
    <h2 class="text-center font-bold text-xl text-gray-800 dark:text-white mb-4">
      Detail Vendor: <span id="selectedVendor"></span>
    </h2>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl shadow">
        <thead class="bg-gray-100 dark:bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Vendor ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">PO Number</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Item Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Item Code</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Category</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Quantity</th>
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
    let vendorData = [];

    $.ajax({
      url: '{{ url("/api/pembelian/vendor2") }}',
      method: 'GET',
      success: function (data) {
        vendorData = data;

        // Pie chart setup
        const labels = data.map(item => 'Vendor #' + item.vendor_id);
        const values = data.map(item => item.total);
        const colors = labels.map(() => `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`);

        const ctx = document.getElementById('vendorChart').getContext('2d');
        new Chart(ctx, {
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
            plugins: {
              legend: {
                position: 'right',
                labels: {
                  usePointStyle: true,
                  padding: 20
                }
              },
              tooltip: {
                callbacks: {
                  label: context => `${context.label}: ${context.parsed}`
                }
              }
            }
          }
        });

        // Populate dropdown
        const vendorSelect = $('#vendorSelect');
        vendorSelect.empty().append('<option value="">-- Choose Vendor --</option>');
        data.forEach(item => {
          vendorSelect.append(`<option value="${item.vendor_id}">Vendor #${item.vendor_id}</option>`);
        });
      },
      error: err => console.error("Gagal ambil data vendor:", err)
    });

    // Handle dropdown change
    $('#vendorSelect').on('change', function () {
      const vendor_id = $(this).val();
      if (!vendor_id) {
        $('#detailTableWrapper').slideUp();
        $('#searchWrapper').slideUp();
        return;
      }

      $.ajax({
        url: `/api/pembelian/vendor2/detail/${vendor_id}`,
        method: 'GET',
        success: function (details) {
          const tbody = $('#detailTableBody');
          tbody.empty();

          if (details.length === 0) {
            tbody.append(`<tr><td colspan="6" class="text-center p-4">No data available</td></tr>`);
          } else {
            details.forEach(item => {
              tbody.append(`
                <tr class="hover:bg-gray-700 transition">
                  <td class="px-6 py-3 text-white text-left">${item.vendor_id}</td>
                  <td class="px-6 py-3 text-white text-left">${item.purchase_order_number}</td>
                  <td class="px-6 py-3 text-white text-left">${item.item_name}</td>
                  <td class="px-6 py-3 text-white text-left">${item.item_code}</td>
                  <td class="px-6 py-3 text-white text-left">${item.category}</td>
                  <td class="px-6 py-3 text-white text-left">${item.quantity}</td>
                </tr>
              `);
            });
          }

          $('#selectedVendor').text('Vendor #' + vendor_id);
          $('#detailTableWrapper').slideDown();
          $('#searchWrapper').slideDown();
        },
        error: err => console.error("Gagal mengambil detail vendor:", err)
      });
    });

    // Filter function
    $('#searchInput').on('keyup', function () {
      const searchTerm = $(this).val().toLowerCase();
      $('#detailTableBody tr').each(function () {
        const rowText = $(this).text().toLowerCase();
        $(this).toggle(rowText.includes(searchTerm));
      });
    });
  });
</script>
</body>
</html>
