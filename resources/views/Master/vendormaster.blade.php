<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <title>Vendor Master</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/css/app.css')
    <style>
      select#columnSelect option {
        color: black;
      }
    </style>
</head>

<body class="pt-16 min-h-screen bg-gradient-to-br from-black via-zinc-900 to-neutral-900 text-white flex items-center justify-center p-4">

<!-- Background -->
<div class="fixed inset-0 z-0">
  <img src="{{ asset('img/88.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-30"/>
  <div class="absolute inset-0 bg-black/40"></div>
</div>

<!-- Home Button -->
<a href="/home" class="fixed top-6 left-6 z-50 bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-2 rounded-lg shadow-lg transition">
  ⬅️ Home
</a>

<main class="relative z-10 w-full max-w-7xl mx-auto">
  <div id="cardsection" class="w-full p-6 rounded-2xl bg-black/30 backdrop-blur-xl shadow-2xl border border-white/10" data-aos="fade-in">
    <h1 class="text-xl md:text-2xl font-bold mb-4 text-center">Vendor Master Data</h1>

    <!-- Filter -->
    <div class="flex flex-wrap gap-4 mb-6 items-center justify-center">
      <select id="columnSelect" class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
        <option value="">-- All Columns --</option>
        <option value="name">Name</option>
        <option value="email">Email</option>
        <option value="phone">Phone</option>
        <option value="address">Address</option>
        <option value="company_name">Company</option>
        <option value="tax_id">Tax ID</option>
        <option value="website">Website</option>
        <option value="status">Status</option>
      </select>

      <input type="text" id="searchValue" placeholder="Enter value..." class="px-4 py-2 rounded bg-white/10 text-white border border-white/20" />

      <button id="searchBtn" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">Search</button>
    </div>
<button onclick="exportTableToExcel('vendor-table', 'Vendor.xlsx')"
  class="mt-5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
  📤 Export to Excel
</button>

    <!-- Table -->
    <div class="mt-10 overflow-x-auto rounded-xl border border-white/10">
      <table class="min-w-[800px] w-full text-xs md:text-sm table-auto text-left">
        <thead class="bg-white/10 text-white whitespace-nowrap">
          <tr>
            <th class="px-3 py-2">No</th>
            <th class="px-3 py-2">Name</th>
            <th class="px-3 py-2">Email</th>
            <th class="px-3 py-2">Phone</th>
            <th class="px-3 py-2">Address</th>
            <th class="px-3 py-2">Company</th>
            <th class="px-3 py-2">Tax ID</th>
            <th class="px-3 py-2">Website</th>
            <th class="px-3 py-2">Status</th>
            <th class="px-3 py-2">Created</th>
          </tr>
        </thead>
        <tbody id="vendor-table" class="divide-y divide-white/10 text-white/90">
          <!-- Rows will be injected here -->
        </tbody>
      </table>
    </div>
  </div>
</main>



<script>
$(document).ready(function () {
  function renderVendorTable(data) {
    let rows = '';
    data.forEach((vendor, i) => {
      rows += `
        <tr class="hover:bg-white/5 transition">
          <td class="px-3 py-2 text-center">${i + 1}</td>
          <td class="px-3 py-2">${vendor.name}</td>
          <td class="px-3 py-2">${vendor.email}</td>
          <td class="px-3 py-2">${vendor.phone}</td>
          <td class="px-3 py-2">${vendor.address}</td>
          <td class="px-3 py-2">${vendor.company_name}</td>
          <td class="px-3 py-2">${vendor.tax_id}</td>
          <td class="px-3 py-2">
            <a href="${vendor.website}" class="underline text-blue-400 hover:text-blue-200" target="_blank">${vendor.website}</a>
          </td>
          <td class="px-3 py-2 capitalize">${vendor.status}</td>
          <td class="px-3 py-2">${new Date(vendor.created_at).toLocaleDateString()}</td>
        </tr>
      `;
    });
    $('#vendor-table').html(rows);
  }

  // Load all vendor data initially
  $.get('/api/vendors/search', function (data) {
    renderVendorTable(data);
  });

  // Search filter
  $('#searchBtn').on('click', function () {
    const column = $('#columnSelect').val();
    const value = $('#searchValue').val();

    // If empty, reload all
    if (!column || !value) {
      $.get('/api/vendors/search', function (data) {
        renderVendorTable(data);
      });
      return;
    }

    $.get(`/api/vendors/search?column=${column}&value=${value}`, function (data) {
      renderVendorTable(data);
    });
  });
});
</script>

<script>
function exportTableToExcel(tableId, filename = 'data.xlsx') {
  const table = document.getElementById(tableId);
  const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
  XLSX.writeFile(wb, filename);
}
</script>

<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

</body>
</html>
