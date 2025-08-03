<!DOCTYPE html>
<html lang="en" class ="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <title>Profit by Category</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
  select#itemSelect option {
    color: black;
  }
   </style>

   <style>
    select#categorySelect option {
    color: black;
  }
   </style>

  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white font-sans">

  <!-- Background -->
 <div class="fixed inset-0 z-0">
    <img src="{{ asset('img/143.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-30"/>
    <div class="absolute inset-0 bg-black/40"></div> <!-- Dark overlay -->
  </div>

    <div class="relative z-10 text-center mt-10 space-y-4" data-aos="zoom-in">
    <h2 class="text-2xl font-bold text-white">📊 Profit by Category</h2>

    <!-- Category Dropdown -->
    <div class="flex justify-center mb-4" data-aos="fade-out">
    <select id="categorySelect"
        class="w-full max-w-xs px-4 py-2 rounded-xl border border-white/30 bg-white/10 backdrop-blur-md text-white shadow-md transition focus:outline-none focus:ring-2 focus:ring-cyan-400/50">
        <option value="" style="color: black;">-- Choose Category --</option>
    </select>
    </div>

    <!-- Item Name Dropdown: hidden by default -->
    <div id="itemDropdownWrapper" class="flex justify-center hidden">
    <select id="itemSelect"
        class="w-full max-w-xs px-4 py-2 rounded-xl border border-white/30 bg-white/10 backdrop-blur-md text-white shadow-md transition focus:outline-none focus:ring-2 focus:ring-indigo-400/50">
        <option value="" style="color: black;">-- Choose Item --</option>
    </select>
    </div>


  <!-- Table -->
  <div id="profitTableWrapper" data-aos="fade-up" class="relative z-10 mt-10 max-w-5xl mx-auto hidden">
    <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl shadow">
      <thead class="bg-gray-100 dark:bg-gray-700">
        <tr>
          <th class="px-4 py-2 text-left text-white">PO Number</th>
          <th class="px-4 py-2 text-left text-white">Item Name</th>
          <th class="px-4 py-2 text-left text-white">Quantity</th>
          <th class="px-4 py-2 text-left text-white">Category</th>
          <th class="px-4 py-2 text-left text-white">Total Profit</th>
        </tr>
      </thead>
      <tbody id="profitTableBody" class="divide-y divide-gray-300 dark:divide-gray-600 text-white">
        <!-- Filled by AJAX -->
      </tbody>
    </table>
  </div>

  <!-- Navigation Buttons -->
<div class="w-full mt-12 px-6 py-10 bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl shadow-2xl text-white" data-aos="fade-up">
  <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

    <!-- Home Button -->
    <a href="/home" class="flex flex-col items-center justify-center text-center 
       bg-white/10 hover:bg-white/20 backdrop-blur-md 
       border border-white/10 transition rounded-xl p-6 shadow-md hover:shadow-xl">
      <div class="text-2xl mb-2">🏠</div>
      <h3 class="font-semibold">Home</h3>
    </a>

    <!-- Vendor By Month -->
    <a href="/vendor_chart" class="flex flex-col items-center justify-center text-center 
       bg-white/10 hover:bg-white/20 backdrop-blur-md 
       border border-white/10 transition rounded-xl p-6 shadow-md hover:shadow-xl">
      <div class="text-2xl mb-2">📈</div>
      <h3 class="font-semibold">Vendor By Month</h3>
    </a>

    <!-- Profit By Product -->
    <a href="/profit" class="flex flex-col items-center justify-center text-center 
       bg-white/10 hover:bg-white/20 backdrop-blur-md 
       border border-white/10 transition rounded-xl p-6 shadow-md hover:shadow-xl">
      <div class="text-2xl mb-2">💰</div>
      <h3 class="font-semibold">Profit By Product</h3>
    </a>

  </div>
</div>

 <script>
  $(document).ready(function () {
    // Load profit data when a category is selected
    $('#categorySelect').on('change', function () {
      const category = $(this).val();

      $('#itemSelect').empty().append('<option value="">-- Choose Item --</option>');
      $('#profitTableWrapper').hide();
      $('#profitTableBody').empty();

      if (!category) {
        $('#itemDropdownWrapper').addClass('hidden');
        return;
      }

      $('#itemDropdownWrapper').removeClass('hidden');

      // Load item names based on category
      $.ajax({
        url: `/pembelian/getitemname/${encodeURIComponent(category)}`,
        method: 'GET',
        success: function (items) {
          $('#itemSelect').empty().append('<option value="">-- Choose Item --</option>');
          
          items.forEach((item, index) => {
            const color = 'black'; // set all options to black
            $('#itemSelect').append(`<option value="${item}" style="color: ${color};">${item}</option>`);
          });
        }
      });

      // Fetch profit data for category only
      fetchProfitData(category, '');
    });

    // Load profit data when an item is selected
    $('#itemSelect').on('change', function () {
      const category = $('#categorySelect').val();
      const itemName = $(this).val();

      if (!category) return;

      fetchProfitData(category, itemName);
    });

    function fetchProfitData(category, itemName = '') {
      let url = `/api/pembelian/profitcategory/${encodeURIComponent(category)}`;
      if (itemName) {
        url += `?item_name=${encodeURIComponent(itemName)}`;
      }

      $.ajax({
        url: url,
        method: 'GET',
        success: function (data) {
          const tbody = $('#profitTableBody');
          tbody.empty();

          if (!data.length) {
            tbody.append(`<tr><td colspan="5" class="text-center py-4">No data found</td></tr>`);
            $('#profitTableWrapper').fadeIn();
            return;
          }

          data.forEach(item => {
            tbody.append(`
              <tr>
                <td class="px-4 py-2">${item.purchase_order_number}</td>
                <td class="px-4 py-2">${item.item_name}</td>
                <td class="px-4 py-2">${item.quantity}</td>
                <td class="px-4 py-2">${item.category}</td>
                <td class="px-4 py-2">Rp ${parseFloat(item.total_profit).toLocaleString('id-ID', {minimumFractionDigits: 2})}</td>
              </tr>
            `);
          });

          $('#profitTableWrapper').fadeIn();
        },
        error: function () {
          alert('Failed to fetch data.');
        }
      });
    }
  });
</script>
<script>
  $(document).ready(function () {
    // Load categories once
    $.ajax({
      url: '/api/pembelian/categories',
      method: 'GET',
      success: function (categories) {
        const categorySelect = $('#categorySelect');
        categories.forEach(category => {
          categorySelect.append(`<option value="${category}">${category}</option>`);
        });
      }
    });

    // Handle category change
    $('#categorySelect').on('change', function () {
      const selectedCategory = $(this).val();

      // Reset item dropdown
      $('#itemSelect').empty().append('<option value="">-- Choose Item --</option>');

      // Hide item dropdown by default
      if (!selectedCategory) {
        $('#itemDropdownWrapper').addClass('hidden');
        return;
      }

      // Show item dropdown
      $('#itemDropdownWrapper').removeClass('hidden');

        $.ajax({
        url: `/api/pembelian/getitemname/${encodeURIComponent(selectedCategory)}`,
        method: 'GET',
        success: function (items) {
            items.forEach(item => {
            $('#itemSelect').append(`<option value="${item}">${item}</option>`);
            });
        }
        });



      $.ajax({
        url: `/api/pembelian/profitcategory/${selectedCategory}`,
        method: 'GET',
        success: function (data) {
          console.log('Profit Data:', data);
          // TODO: Populate your table with this `data`
        }
      });
    });
  });
</script>
    <!-- AOS Animation -->
 <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
      AOS.init({
        once: true, // animation only once
        duration: 1000, // animation duration in ms
      });
    </script>
</body>
</html>
