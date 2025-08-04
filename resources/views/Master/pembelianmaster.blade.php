{{-- resources/views/Master/pembelianmaster.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pembelian Master</title>
  @vite('resources/css/app.css')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
  <style>
    select#columnSelect option { color: black; }
    .input {
      @apply w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 focus:outline-none focus:ring focus:border-blue-500;
    }
  </style>
   <style>
      select#vendor_id option {
        color: black;
      }
    </style>
      <style>
      select#request_id option {
        color: black;
      }
    </style>
    <style>
      select#project_id option {
        color: black;
      }
    </style>
    <style>
      select#category_id option {
        color: black;
      }
    </style>
    <style>
      select#pending_id option {
        color: black;
      }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-900 text-white p-10">
<main>
  <button onclick="window.location.href='/home'"
    class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded text-white font-semibold">
    ⬅️ Home
  </button>

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

  <div class="flex gap-4 mt-5 mb-4">
    <button onclick="exportTableToExcel('pembelianTable', 'Pembelian.xlsx')"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
      📤 Export to Excel
    </button>

    <button id="openInsertPembelianModal"
      class="px-4 py-2 bg-green-600 text-white rounded-md">
      + Add Pembelian
    </button>

    <button id="deleteSelectedBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
  🗑️ Delete Selected
    </button>

  </div>

  <div id="resultsWrapper" class="mt-10 hidden">
    <h2 class="text-xl font-semibold mb-4">Search Results:</h2>
    <table id="pembelianTable" class="w-full border border-white/20 bg-white/5 text-sm rounded-xl">
      <thead class="bg-white/10 text-white uppercase text-xs">
        <tr id="tableHead"></tr>
      </thead>
      <tbody id="resultBody"></tbody>
    </table>
  </div>
</main>

<!-- Insert Modal -->
<div id="insertPembelianModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
  <div class="bg-white dark:bg-gray-900 text-black dark:text-white p-6 rounded-xl w-full max-w-4xl relative">
    <h2 class="text-lg font-semibold mb-4">Insert Pembelian</h2>
    <form id="insertPembelianForm" class="space-y-4">
      @csrf
      <div class="grid grid-cols-2 gap-4">
        <select id="vendor_id" name="vendor_id" class="input" required>
          <option value="">Select Vendor</option>
          @foreach($vendors as $vendor)
            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
          @endforeach
        </select>

        <select id="project_id" name="project_id" class="input" required>
          <option value="">Select Project</option>
          @foreach($projects as $project)
            <option value="{{ $project->id }}">{{ $project->name }}</option>
          @endforeach
        </select>

        <select id="request_id" name="requested_by" class="input" required>
          <option value="">Select Requester</option>
          @foreach($requests as $request)
            <option value="{{ $request->id }}">{{ $request->name }}</option>
          @endforeach
        </select>

        <input type="text" name="purchase_order_number" placeholder="PO Number" class="input" required>
        <input type="text" name="item_name" placeholder="Item Name" class="input" required>
        <input type="text" name="item_code" placeholder="Item Code" class="input" required>
        <select id="category_id" name="category" class="input" required>
        <option value="">Select Category</option>
        <option value="Spare Part">Spare Part</option>
        <option value="Office Supplies">Office Supplies</option>
        <option value="Fuel">Fuel</option>
        </select>
        <input type="number" name="quantity" placeholder="Quantity" class="input" required id="quantity">
        <input type="text" name="unit" placeholder="Unit" class="input">
        <input type="number" name="buy_price" placeholder="Buy Price" class="input">
        <input type="number" name="unit_price" placeholder="Unit Price" class="input" required id="unit_price">
        <input type="number" name="tax" placeholder="Tax" class="input" id="tax">
        <input type="number" name="grand_total" placeholder="Grand Total" class="input bg-gray-100" readonly id="grand_total">
        <input type="date" name="purchase_date" class="input">
        <input type="date" name="expected_delivery_date" class="input">
        <select id="pending_id" name="status" class="input">
          <option value="pending">Pending</option>
          <option value="completed">Completed</option>
        </select>
        <input type="text" name="remarks" placeholder="Remarks" class="input">
      </div>

      <div class="flex justify-end mt-4 gap-2">
        <button type="button" id="closeInsertPembelianModal" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Shared table render function
  function renderTable(data) {
    if (!data.length) {
      $('#resultsWrapper').removeClass('hidden');
      $('#tableHead').html('');
      $('#resultBody').html('<tr><td colspan="20" class="text-center py-4">No results found.</td></tr>');
      return;
    }

    const headers = Object.keys(data[0]);
    $('#tableHead').html(`
    <th class="px-2 py-2 text-center">✓</th>
    ${headers.map(h => `<th class="px-4 py-2">${h}</th>`).join('')}
  ` );
    const rows = data.map(row => {
      return `<tr class="border-t border-white/10 hover:bg-white/10">
    <td class="px-2 py-2 text-center">
      <input type="checkbox" class="row-checkbox" value="${row.id}">
    </td>
    ${headers.map(col => `<td class="px-4 py-2">${row[col]}</td>`).join('')}
  </tr>`;
  });

    $('#resultBody').html(rows.join(''));
    $('#resultsWrapper').removeClass('hidden');
  }

  // Column selector + search
  $(function () {
    $.get('/api/pembelian/columns', function (columns) {
      const select = $('#columnSelect');
      select.append(`<option value="">-- All Columns --</option>`);
      columns.forEach(col => {
        select.append(`<option value="${col}">${col}</option>`);
      });
    });

    $.get('/api/pembelian/search', renderTable);

    $('#searchBtn').on('click', function () {
      const column = $('#columnSelect').val();
      const value = $('#searchValue').val();
      if (!column || !value) {
        $.get('/api/pembelian/search', renderTable);
        return;
      }
      $.get(`/api/pembelian/search?column=${column}&value=${value}`, renderTable);
    });
  });

  // Modal & insert logic
  $(function () {
    $('#openInsertPembelianModal').on('click', function () {
      $('#insertPembelianModal').removeClass('hidden');
    });

    $('#closeInsertPembelianModal').on('click', function () {
      $('#insertPembelianModal').addClass('hidden');
      $('#insertPembelianForm')[0].reset();
      $('#grand_total').val('');
    });

    function updateGrandTotal() {
      const qty = parseFloat($('#quantity').val()) || 0;
      const unitPrice = parseFloat($('#unit_price').val()) || 0;
      const tax = parseFloat($('#tax').val()) || 0;
      const total = (unitPrice * qty) + tax;
      $('#grand_total').val(total.toFixed(2));
    }

    $('#quantity, #unit_price, #tax').on('input', updateGrandTotal);

    $('#insertPembelianForm').on('submit', function (e) {
      e.preventDefault();
      const formData = $(this).serialize();
      $.post('/api/pembelian/insert', formData, function () {
        alert('Pembelian inserted!');
        $('#insertPembelianModal').addClass('hidden');
        $('#insertPembelianForm')[0].reset();
        $('#grand_total').val('');
        $.get('/api/pembelian/search', renderTable);
      }).fail(function (err) {
        alert('Insert failed.');
        console.error(err);
      });
    });
  });

  function exportTableToExcel(tableId, filename = 'data.xlsx') {
    const table = document.getElementById(tableId);
    const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    XLSX.writeFile(wb, filename);
  }
</script>

<script>
  $(document).on('click', '#deleteSelectedBtn', function () {
  const selectedIds = $('.row-checkbox:checked').map(function () {
    return $(this).val();
  }).get();

  if (selectedIds.length === 0) {
    alert('Please select at least one row to delete.');
    return;
  }

  if (!confirm(`Delete ${selectedIds.length} item(s)? This action cannot be undone.`)) {
    return;
  }

  $.ajax({
    url: '{{ url("/pembelian/delete") }}',
    method: 'POST',
    data: {
      ids: selectedIds,
      _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function (res) {
      alert(res.message);
      $.get('/api/pembelian/search', renderTable); // refresh
    },
    error: function (err) {
      alert('Failed to delete.');
      console.error(err);
    }
  });
});
</script>
</body>
</html>
