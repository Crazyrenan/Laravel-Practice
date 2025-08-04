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
    <style>
      select#vendor_status option {
        color: black;
      }
    </style>
     <style>
      select#edit_vendor_status option {
        color: black;
      }
    </style>
    <style>
    #vendor-table tr.bg-green-900 {
      background-color: #14532d !important;
    }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <h2 class="mt-10 text-lg font-bold mb-4">➕ Add New Vendor</h2>
    <form id="vendorForm" class="grid md:grid-cols-3 gap-4 mb-6">
      <input type="text" id="vendor_name" placeholder="Name" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
      <input type="email" id="vendor_email" placeholder="Email" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
      <input type="text" id="vendor_phone" placeholder="Phone" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
      <input type="text" id="vendor_address" placeholder="Address" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
      <input type="text" id="vendor_company" placeholder="Company Name" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
      <input type="text" id="vendor_taxid" placeholder="Tax ID" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
      <input type="url" id="vendor_website" placeholder="Website (optional)" class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
      <select id="vendor_status" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
        <option value="">Select Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>

      <button type="submit" class="col-span-full bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white font-semibold">
        ➕ Submit Vendor
      </button>
    </form>
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
            <th class="px-3 py-2">Action</th>
          </tr>
        </thead>
        <tbody id="vendor-table" class="divide-y divide-white/10 text-white/90">
          <!-- Rows will be injected here -->
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-4 flex justify-end">
  <button id="editSelectedBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded">
    ✏️ Edit Selected Vendor
  </button>
</div>
</main>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
  <div class="bg-zinc-900 text-white p-6 rounded-lg w-full max-w-2xl shadow-xl">
    <h3 class="text-lg font-semibold mb-4">✏️ Edit Vendor</h3>
    <form id="editForm" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-">
      <input type="hidden" id="edit_vendor_id">
      <input type="text" id="edit_vendor_name" placeholder="Name" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <input type="email" id="edit_vendor_email" placeholder="Email" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <input type="text" id="edit_vendor_phone" placeholder="Phone" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <input type="text" id="edit_vendor_address" placeholder="Address" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <input type="text" id="edit_vendor_company" placeholder="Company Name" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <input type="text" id="edit_vendor_taxid" placeholder="Tax ID" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <input type="url" id="edit_vendor_website" placeholder="Website" class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <select id="edit_vendor_status" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
        <option value="">Select Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
      <div class="col-span-full flex justify-end gap-4 mt-4">
        <button type="submit" class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white">💾 Save Changes</button>
        <button type="button" onclick="$('#editModal').addClass('hidden')" class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded text-white">❌ Cancel</button>
      </div>
    </form>
  </div>
</div>



<script>
$(document).ready(function () {
  function renderVendorTable(data) {
    let rows = '';
    data.forEach((vendor, i) => {
      rows += `
        <tr class="hover:bg-white/5 transition" data-id="${vendor.id}">
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
          <td class="px-3 py-2 text-center">
          <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">🗑️ Delete</button>
          </td>
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
$('#vendorForm').on('submit', function (e) {
  e.preventDefault();

  const data = {
    name: $('#vendor_name').val(),
    email: $('#vendor_email').val(),
    phone: $('#vendor_phone').val(),
    address: $('#vendor_address').val(),
    company_name: $('#vendor_company').val(),
    tax_id: $('#vendor_taxid').val(),
    website: $('#vendor_website').val(),
    status: $('#vendor_status').val(),
  };

  $.ajax({
    type: 'POST',
    url: '/api/vendors/insert',
    data: data,
    success: function (res) {
      alert(res.message || 'Vendor added!');
      location.reload(); // optional: you can instead re-render table with JS
    },
    error: function (err) {
      alert('Insert failed: ' + err.responseJSON.message);
    }
  });
});
$(document).on('click', '.deleteBtn', function () {
  const row = $(this).closest('tr');
  const id = row.data('id');

  if (confirm('Are you sure you want to delete this vendor?')) {
    $.ajax({
      type: 'DELETE',
      url: `/api/vendors/delete/${id}`,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (res) {
        alert(res.message || 'Vendor deleted!');
        row.remove(); // Remove row from table
      },
      error: function (err) {
        alert('Delete failed: ' + (err.responseJSON?.message || 'Unknown error'));
      }
    });
  }
});
</script>

<script>
function exportTableToExcel(tableId, filename = 'data.xlsx') {
  const table = document.getElementById(tableId);
  const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
  XLSX.writeFile(wb, filename);
}


// Edit selected vendor
let selectedVendorId = null;

$(document).on('click', '#vendor-table tr', function () {
  $('#vendor-table tr').removeClass('bg-green-900'); // clear previous
  $(this).addClass('bg-green-900'); // highlight selected
  selectedVendorId = $(this).data('id');
});


// Edit button functionality
$('#editSelectedBtn').on('click', function () {
  if (!selectedVendorId) {
    alert('Please select a row first!');
    return;
  }

  const row = $(`#vendor-table tr[data-id="${selectedVendorId}"]`);
  
  $('#edit_vendor_id').val(selectedVendorId);
  $('#edit_vendor_name').val(row.find('td:nth-child(2)').text());
  $('#edit_vendor_email').val(row.find('td:nth-child(3)').text());
  $('#edit_vendor_phone').val(row.find('td:nth-child(4)').text());
  $('#edit_vendor_address').val(row.find('td:nth-child(5)').text());
  $('#edit_vendor_company').val(row.find('td:nth-child(6)').text());
  $('#edit_vendor_taxid').val(row.find('td:nth-child(7)').text());
  $('#edit_vendor_website').val(row.find('td:nth-child(8)').text());
  $('#edit_vendor_status').val(row.find('td:nth-child(9)').text().toLowerCase());

  $('#editModal').removeClass('hidden flex');
});
$('#editForm').on('submit', function (e) {
  e.preventDefault();

  const id = $('#edit_vendor_id').val();
  const data = {
    name: $('#edit_vendor_name').val(),
    email: $('#edit_vendor_email').val(),
    phone: $('#edit_vendor_phone').val(),
    address: $('#edit_vendor_address').val(),
    company_name: $('#edit_vendor_company').val(),
    tax_id: $('#edit_vendor_taxid').val(),
    website: $('#edit_vendor_website').val(),
    status: $('#edit_vendor_status').val(),
  };

  $.ajax({
    type: 'PUT',
    url: `/api/vendors/update/${id}`,
    data: data,
    success: function (res) {
      alert(res.message || 'Vendor updated!');
      $('#editModal').addClass('hidden');
      selectedVendorId = null;
      location.reload(); // <- this refreshes the table
    },
    error: function (err) {
      alert('Update failed: ' + (err.responseJSON?.message || 'Unknown error'));
    }
  });
});
// Cancel edit button
$('#cancelEdit').on('click', function () {
  $('#editModal').addClass('hidden');
  selectedVendorId = null;
});

</script>

<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

</body>
</html>
