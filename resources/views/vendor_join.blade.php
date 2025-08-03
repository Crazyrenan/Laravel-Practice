<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <title>Vendor Purchases</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Swiper CSS -->
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
    />

   <style>
    select#vendorSelect option {
    color: black;
  }
   </style>

  @vite('resources/css/app.css')
</head>
<script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script><!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<body class="bg-gray-900 text-white font-sans">

<!-- Background-->
<div class="fixed inset-0 z-0">
   <img src="{{ asset('img/88.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-30"/>
    <div class="absolute inset-0 bg-black/40"></div> <!-- Dark overlay -->
</div>

<!-- Top Title-->
<div class="max-w-5xl mx-auto mt-10 relative z-10" data-aos="zoom-in">
  <h2 class="text-2xl font-bold text-center mb-6">📦 Vendor Purchase Filter</h2>

  <div class="flex flex-wrap justify-center gap-4 mb-8">
    <div>
      <label for="vendorSelect" class="block text-sm mb-1">Vendor</label>
      <select id="vendorSelect" class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
        <option value="0" style="color:black">All Vendors</option>
      </select>
    </div>
    <div>
      <label for="startDate" class="block text-sm mb-1">Start Date</label>
      <input type="date" id="startDate" class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
    </div>
    <div>
      <label for="endDate" class="block text-sm mb-1">End Date</label>
      <input type="date" id="endDate" class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
    </div>
    <div class="flex items-end">
      <button id="applyFilter" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white font-semibold">Search</button>
    </div>
  </div>

  <!-- Table-->
  <div class="overflow-x-auto">
    <table class="w-full border border-white/20 bg-white/5 text-sm rounded-xl">
      <thead class="bg-white/10 text-white uppercase text-xs">
        <tr>
          <th class="px-4 py-2">Vendor</th>
          <th class="px-4 py-2">PO Number</th>
          <th class="px-4 py-2">Item Name</th>
          <th class="px-4 py-2">Item Code</th>
          <th class="px-4 py-2">Category</th>
          <th class="px-4 py-2">Quantity</th>
        </tr>
      </thead>
      <tbody id="detailTableBody" data-aos="zoom-in">
        <!-- Data goes here -->
      </tbody>
    </table>
  </div>
</div>
<div id="paginationWrapper" class="relative z-10 mt-6 flex justify-center items-center text-sm"></div>

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
$(document).ready(function () {
  let currentPage = 1;
  const perPage = 10;

  // Load vendor options
  $.get('/api/join/vendors', function (vendors) {
    vendors.forEach(v => {
      $('#vendorSelect').append(`<option value="${v.id}">${v.name}</option>`);
    });
  });

  function fetchData(page = 1) {
    const vendorId = $('#vendorSelect').val();
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    const query = [`page=${page}`, `per_page=${perPage}`];
    if (startDate) query.push(`start_date=${startDate}`);
    if (endDate) query.push(`end_date=${endDate}`);

    const url = `/api/join/detail/${vendorId}?${query.join('&')}`;

    $.get(url, function (response) {
      const tbody = $('#detailTableBody').empty();

      if (!response.data || !response.data.length) {
        tbody.append('<tr><td colspan="6" class="text-center py-4">No data found</td></tr>');
        $('#paginationWrapper').html('');
        return;
      }

      response.data.forEach(d => {
        tbody.append(`
          <tr class="border-t border-white/10 hover:bg-white/10">
            <td class="px-4 py-2">${d.vendor_name ?? 'N/A'}</td>
            <td class="px-4 py-2">${d.purchase_order_number}</td>
            <td class="px-4 py-2">${d.item_name}</td>
            <td class="px-4 py-2">${d.item_code}</td>
            <td class="px-4 py-2">${d.category}</td>
            <td class="px-4 py-2">${d.quantity}</td>
          </tr>
        `);
      });

      renderPagination(response.current_page, response.last_page);
    });
  }

  function renderPagination(current, last) {
    const wrapper = $('#paginationWrapper').empty();
    if (last <= 1) return;

    const prev = $('<button class="px-3 py-1 bg-white/10 hover:bg-white/20 rounded mr-2">Prev</button>');
    const next = $('<button class="px-3 py-1 bg-white/10 hover:bg-white/20 rounded">Next</button>');

    if (current > 1) {
      prev.on('click', () => {
        currentPage--;
        fetchData(currentPage);
      });
    } else {
      prev.prop('disabled', true).addClass('opacity-30');
    }

    if (current < last) {
      next.on('click', () => {
        currentPage++;
        fetchData(currentPage);
      });
    } else {
      next.prop('disabled', true).addClass('opacity-30');
    }

    wrapper.append(prev).append(`<span class="mx-2">Page ${current} of ${last}</span>`).append(next);
  }

  $('#applyFilter').on('click', function () {
    currentPage = 1;
    fetchData(currentPage);
  });

  // Auto-load data on page load
  $('#applyFilter').click();
});
</script>
    <!-- AOS Animation -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
      AOS.init({
        once: false, // animation only once
        duration: 800, // animation duration in ms
      });
</script>
</body>
</html>
