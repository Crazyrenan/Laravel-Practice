<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Pembelian Table</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @vite('resources/css/app.css')
  <style>
    select#columnSelect option {
    color: black;
  }
   </style>
  <button onclick="window.location.href='/home'"
        class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded text-white font-semibold">
  ⬅️ Home
    </button>
</head>
<body class="bg-gray-900 text-white p-10">
<main>
  <h1 class="text-2xl font-bold mb-6 mt-10">🔍 Search Pembelian by Column</h1>       
  <div class="mb-6 space-y-4">
    <div>
      <label for="columnSelect">Choose Column:</label>
      <select id="columnSelect" class="w-full max-w-md px-4 py-2 rounded bg-white/10 text-white border border-white/20">
        <option value="">-- Select Column --</option>
      </select>
    </div>

    <div>
      <label for="searchValue">Enter Value:</label>
      <input type="text" id="searchValue" placeholder="Type search value"
             class="w-full max-w-md px-4 py-2 rounded bg-white/10 text-white border border-white/20">
    </div>

    <button id="searchBtn" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded text-white font-semibold">
      Search
    </button>


  </div>

<button onclick="exportTableToExcel('pembelianTable', 'Pembelian.xlsx')"
  class="mt-5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
  📤 Export to Excel
</button>

  <div id="resultsWrapper" class="mt-10 hidden">
    <h2 class="text-xl font-semibold mb-4">Search Results:</h2>
    <table id= "pembelianTable"class="w-full border border-white/20 bg-white/5 text-sm rounded-xl">
      <thead class="bg-white/10 text-white uppercase text-xs">
        <tr id="tableHead">
          <!-- Dynamic columns -->
        </tr>
      </thead>
      <tbody id="resultBody">
        <!-- Dynamic rows -->
      </tbody>
    </table>
  </div>
</main>
<script>
$(document).ready(function () {
  // Load all column names into dropdown
  $.get('/api/pembelian/columns', function (columns) {
    const select = $('#columnSelect');
    select.append(`<option value="">-- All Columns --</option>`); // default
    columns.forEach(col => {
      select.append(`<option value="${col}">${col}</option>`);
    });
  });

  // Function to render table
  function renderTable(data) {
    if (!data.length) {
      $('#resultsWrapper').removeClass('hidden');
      $('#tableHead').html('');
      $('#resultBody').html('<tr><td colspan="20" class="text-center py-4">No results found.</td></tr>');
      return;
    }

    const headers = Object.keys(data[0]);
    $('#tableHead').html(headers.map(h => `<th class="px-4 py-2">${h}</th>`).join(''));

    const rows = data.map(row => {
      return `<tr class="border-t border-white/10 hover:bg-white/10">
        ${headers.map(col => `<td class="px-4 py-2">${row[col]}</td>`).join('')}
      </tr>`;
    });

    $('#resultBody').html(rows.join(''));
    $('#resultsWrapper').removeClass('hidden');
  }

  // Load all data on page load
  $.get('/api/pembelian/search', function (data) {
    renderTable(data);
  });

  // Search button click
  $('#searchBtn').on('click', function () {
    const column = $('#columnSelect').val();
    const value = $('#searchValue').val();

    // If both are empty, reload all
    if (!column || !value) {
      $.get('/api/pembelian/search', function (data) {
        renderTable(data);
      });
      return;
    }

    // Only search if both column and value provided
    $.get(`/api/pembelian/search?column=${column}&value=${value}`, function (results) {
      renderTable(results);
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
