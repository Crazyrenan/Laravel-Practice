<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <title>Request Master</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/css/app.css')
    <style>
        select#columnSelect option,
        select#request_status option,
        select#request_department option {
            color: black;
        }
    </style>
</head>

<body class="pt-16 min-h-screen bg-gradient-to-br from-black via-zinc-900 to-neutral-900 text-white flex items-center justify-center p-4">

<!-- Background -->
<div class="fixed inset-0 z-0">
    <img src="{{ asset('img/88.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-30" />
    <div class="absolute inset-0 bg-black/40"></div>
</div>

<!-- Home Button -->
<a href="/home" class="fixed top-6 left-6 z-50 bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-2 rounded-lg shadow-lg transition">
  ⬅️ Home
</a>

<main class="relative z-10 w-full max-w-7xl mx-auto">
    <div class="w-full p-6 rounded-2xl bg-black/30 backdrop-blur-xl shadow-2xl border border-white/10" data-aos="fade-in">
        <h1 class="text-xl md:text-2xl font-bold mb-4 text-center">Request Master Data</h1>

        <!-- Filter -->
        <div class="flex flex-wrap gap-4 mb-6 items-center justify-center">
            <select id="columnSelect" class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
                <option value="">-- All Columns --</option>
                <option value="name">Name</option>
                <option value="email">Email</option>
                <option value="phone">Phone</option>
                <option value="department">Department</option>
                <option value="status">Status</option>
            </select>
            <input type="text" id="searchValue" placeholder="Enter value..." class="px-4 py-2 rounded bg-white/10 text-white border border-white/20" />
            <button id="searchBtn" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">Search</button>
        </div>

        <!-- Export -->
        <button onclick="exportTableToExcel('request-table', 'Request.xlsx')"
            class="mb-8 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            📤 Export to Excel
        </button>

        <!-- Add Form -->
        <h2 class="text-lg font-bold mb-4">➕ Add New Request</h2>
        <form id="requestForm" class="grid md:grid-cols-3 gap-4 mb-6">
            <input type="text" id="request_name" placeholder="Name" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
            <input type="email" id="request_email" placeholder="Email" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
            <input type="text" id="request_phone" placeholder="Phone" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
            <select id="request_department" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
                <option value="">Select Department</option>
                <option value="IT">IT</option>
                <option value="HR">HR</option>
                <option value="Finance">Finance</option>
                <option value="Operations">Operations</option>
                <option value="Marketing">Marketing</option>
            </select>
            <select id="request_status" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <button type="submit" class="col-span-full bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white font-semibold">
                ➕ Submit Request
            </button>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-white/10">
            <table class="min-w-[800px] w-full text-xs md:text-sm table-auto text-left">
                <thead class="bg-white/10 text-white whitespace-nowrap">
                    <tr>
                        <th class="px-3 py-2">No</th>
                        <th class="px-3 py-2">Name</th>
                        <th class="px-3 py-2">Email</th>
                        <th class="px-3 py-2">Phone</th>
                        <th class="px-3 py-2">Department</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Action</th>
                    </tr>
                </thead>
                <tbody id="request-table" class="divide-y divide-white/10 text-white/90">
                    <!-- Rows will be injected -->
                </tbody>
            </table>
        </div>
    </div>
<div class="mt-4 fixed bottom-10 right-10 z-50">
  <button id="editSelectedBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded shadow-lg">
    ✏️ Edit Selected Request
  </button>
</div>
</main>
<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
  <div class="bg-zinc-900 text-white p-6 rounded-lg w-full max-w-2xl shadow-xl">
    <h3 class="text-lg font-semibold mb-4">✏️ Edit Request</h3>
    <form id="editForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <input type="hidden" id="edit_request_id">
      <input type="text" id="edit_request_name" placeholder="Name" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <input type="email" id="edit_request_email" placeholder="Email" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <input type="text" id="edit_request_phone" placeholder="Phone" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <input type="text" id="edit_request_department" placeholder="Department" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <select id="edit_request_status" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
        <option value="">Select Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
      <div class="col-span-full flex justify-end gap-4 mt-4">
        <button type="button" onclick="$('#editModal').addClass('hidden')" class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded text-white">❌ Cancel</button>
        <button type="submit" class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white">💾 Save Changes</button>
      </div>
    </form>
  </div>
</div>


<!-- Script -->
<script>
$(document).ready(function () {
    function renderRequestTable(data) {
        let rows = '';
        data.forEach((r, i) => {
            rows += `
                <tr class="hover:bg-white/5 transition">
                    <td class="px-3 py-2 text-center">${i + 1}</td>
                    <td class="px-3 py-2">${r.name}</td>
                    <td class="px-3 py-2">${r.email}</td>
                    <td class="px-3 py-2">${r.phone}</td>
                    <td class="px-3 py-2">${r.department}</td>
                    <td class="px-3 py-2 capitalize">${r.status}</td>
                    <td class="px-3 py-2">${new Date(r.created_at).toLocaleDateString()}</td>
                </tr>`;
        });
        $('#request-table').html(rows);
    }

    // Load all
    $.get('/api/requests/search', function (data) {
        renderRequestTable(data);
    });

    // Search
    $('#searchBtn').on('click', function () {
        const col = $('#columnSelect').val();
        const val = $('#searchValue').val();

        if (!col || !val) {
            $.get('/api/requests/search', renderRequestTable);
        } else {
            $.get(`/api/requests/search?column=${col}&value=${val}`, renderRequestTable);
        }
    });

    // Submit form
    $('#requestForm').on('submit', function (e) {
        e.preventDefault();

        const payload = {
            name: $('#request_name').val(),
            email: $('#request_email').val(),
            phone: $('#request_phone').val(),
            department: $('#request_department').val(),
            status: $('#request_status').val(),
        };

        $.post('/api/request/insert', payload)
        .done(res => {
            alert(res.message || 'Request added!');
            location.reload();
        })
        .fail(err => {
            alert('Insert failed: ' + err.responseJSON.message);
        });
    });
});
</script>

<script>
    let selectedRequestId = null;

function renderRequestTable(data) {
  let rows = '';
  data.forEach((request, i) => {
    rows += `
      <tr class="hover:bg-white/5 transition" data-id="${request.id}">
        <td class="px-3 py-2 text-center">${i + 1}</td>
        <td class="px-3 py-2">${request.name}</td>
        <td class="px-3 py-2">${request.email}</td>
        <td class="px-3 py-2">${request.phone}</td>
        <td class="px-3 py-2">${request.department}</td>
        <td class="px-3 py-2 capitalize">${request.status}</td>
        <td class="px-3 py-2 text-center">
          <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">🗑️ Delete</button>
        </td>
      </tr>
    `;
  });
  $('#request-table').html(rows);
}

$(document).ready(function () {
  // Load all
  $.get('/api/requests/search', function (data) {
    renderRequestTable(data);
  });

  // Search
  $('#searchBtn').on('click', function () {
    const column = $('#columnSelect').val();
    const value = $('#searchValue').val();

    if (!column || !value) {
      $.get('/api/requests/search', renderRequestTable);
      return;
    }

    $.get(`/api/requests/search?column=${column}&value=${value}`, renderRequestTable);
  });

  // Insert
  $('#requestForm').on('submit', function (e) {
    e.preventDefault();
    const data = {
      name: $('#request_name').val(),
      email: $('#request_email').val(),
      phone: $('#request_phone').val(),
      department: $('#request_department').val(),
      status: $('#request_status').val(),
    };

    $.post('/api/requests/insert', data)
      .done(res => {
        alert(res.message || 'Request added!');
        location.reload();
      })
      .fail(err => alert('Insert failed: ' + err.responseJSON?.message));
  });

  // Row click
  $(document).on('click', '#request-table tr', function () {
    $('#request-table tr').removeClass('bg-green-900');
    $(this).addClass('bg-green-900');
    selectedRequestId = $(this).data('id');
  });

  // Edit modal
  $('#editSelectedBtn').on('click', function () {
    if (!selectedRequestId) return alert('Please select a row first!');

    const row = $(`#request-table tr[data-id="${selectedRequestId}"]`);
    $('#edit_request_id').val(selectedRequestId);
    $('#edit_request_name').val(row.find('td:nth-child(2)').text());
    $('#edit_request_email').val(row.find('td:nth-child(3)').text());
    $('#edit_request_phone').val(row.find('td:nth-child(4)').text());
    $('#edit_request_department').val(row.find('td:nth-child(5)').text());
    $('#edit_request_status').val(row.find('td:nth-child(6)').text().toLowerCase());
    $('#editModal').removeClass('hidden flex');
  });

  // Edit submit
  $('#editForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#edit_request_id').val();
    const data = {
      name: $('#edit_request_name').val(),
      email: $('#edit_request_email').val(),
      phone: $('#edit_request_phone').val(),
      department: $('#edit_request_department').val(),
      status: $('#edit_request_status').val(),
    };

    $.ajax({
      type: 'PUT',
      url: `/api/requests/update/${id}`,
      data: data,
      success: res => {
        alert(res.message || 'Request updated!');
        $('#editModal').addClass('hidden');
        selectedRequestId = null;
        location.reload();
      },
      error: err => alert('Update failed: ' + (err.responseJSON?.message || 'Unknown error'))
    });
  });

  // Delete
  $(document).on('click', '.deleteBtn', function () {
    const row = $(this).closest('tr');
    const id = row.data('id');

    if (confirm('Are you sure you want to delete this request?')) {
      $.ajax({
        type: 'DELETE',
        url: `/api/requests/delete/${id}`,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: res => {
          alert(res.message || 'Request deleted!');
          row.remove();
        },
        error: err => alert('Delete failed: ' + (err.responseJSON?.message || 'Unknown error'))
      });
    }
  });
});
</script>
<script>
function exportTableToExcel(tableId, filename = 'Request.xlsx') {
  const table = document.getElementById(tableId);
  const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
  XLSX.writeFile(wb, filename);
}
</script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

</body>
</html>
