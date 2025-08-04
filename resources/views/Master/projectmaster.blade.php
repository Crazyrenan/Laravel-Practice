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
      select#project_status option {
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
    <h1 class="text-xl md:text-2xl font-bold mb-4 text-center">Project Master Data</h1>

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

    <button onclick="exportTableToExcel('project-table', 'Project.xlsx')"
  class="mt-5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
  📤 Export to Excel
    </button>

<h2 class="mt-10 text-lg font-bold mb-4">➕ Add New Project</h2>
<form id="projectForm" class="grid md:grid-cols-3 gap-4 mb-6">
  <input type="text" id="project_name" placeholder="Project Name" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
  <input type="text" id="project_description" placeholder="Description" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
  <select id="project_status" required class="px-4 py-2 rounded bg-white/10 text-white border border-white/20">
    <option value="">Select Status</option>
    <option value="active">Active</option>
    <option value="inactive">Inactive</option>
  </select>

  <button type="submit" class="col-span-full bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white font-semibold">
    ➕ Submit Project
  </button>
</form>

    <!-- Table -->
    <div class="mt-10 overflow-x-auto rounded-xl border border-white/10">
      <table class="min-w-[800px] w-full text-xs md:text-sm table-auto text-left">
        <thead class="bg-white/10 text-white whitespace-nowrap">
          <tr>
            <th class="px-3 py-2">No</th>
            <th class="px-3 py-2">Name</th>
            <th class="px-3 py-2">Description</th>
            <th class="px-3 py-2">Status</th>
            <th class="px-3 py-2">Created at</th>
            <th class="px-3 py-2">Action</th>
          </tr>
        </thead>
        <tbody id="project-table" class="divide-y divide-white/10 text-white/90">
          <!-- Rows will be injected here -->
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-4 fixed bottom-10 right-10 z-50">
  <button id="editSelectedBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded shadow-lg">
    ✏️ Edit Selected Project
  </button>
</div>

</main>
<!-- Edit Modal -->
<div id="editModal" class="hidden fixed mt-10 inset-0 z-50 flex items-center justify-center">
  <div class="bg-zinc-900 text-white p-6 rounded-lg w-full max-w-2xl shadow-xl">
    <h3 class="text-lg font-semibold mb-4">✏️ Edit Project</h3>
    <form id="editForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <input type="hidden" id="edit_project_id">
      <input type="text" id="edit_project_name" placeholder="Project Name" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <input type="text" id="edit_project_description" placeholder="Description" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
      <select id="edit_project_status" required class="px-4 py-2 rounded bg-white/10 border border-white/20">
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
  function renderProjectTable(data) {
    let rows = '';
    data.forEach((project, i) => {
     rows += `
      <tr data-id="${project.id}" class="hover:bg-white/5 transition">
        <td class="px-3 py-2 text-center">${i + 1}</td>
        <td class="px-3 py-2">${project.name}</td>
        <td class="px-3 py-2">${project.description}</td>
        <td class="px-3 py-2 capitalize">${project.status}</td>
        <td class="px-3 py-2">${new Date(project.created_at).toLocaleDateString()}</td>
        <td class="px-3 py-2 text-center">
            <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">🗑️ Delete</button>
        </td>
      </tr>
    `;
    });
    $('#project-table').html(rows);
  }

  // Load all project data initially
  $.get('/api/projects/search', function (data) {
    renderProjectTable(data);
  });

  // Search filter
  $('#searchBtn').on('click', function () {
    const column = $('#columnSelect').val();
    const value = $('#searchValue').val();

    // If empty, reload all
    if (!column || !value) {
      $.get('/api/projects/search', function (data) {
        renderProjectTable(data);
      });
      return;
    }

    $.get(`/api/projects/search?column=${column}&value=${value}`, function (data) {
      renderProjectTable(data);
    });
  });
});
</script>
<script>
let selectedProjectId = null;

$(document).on('click', '#project-table tr', function () {
  $('#project-table tr').removeClass('bg-green-900');
  $(this).addClass('bg-green-900');
  selectedProjectId = $(this).data('id');
});

$('#editSelectedBtn').on('click', function () {
  if (!selectedProjectId) {
    alert('Please select a row first!');
    return;
  }

  const row = $(`#project-table tr[data-id="${selectedProjectId}"]`);
  $('#edit_project_id').val(selectedProjectId);
  $('#edit_project_name').val(row.find('td:nth-child(2)').text());
  $('#edit_project_description').val(row.find('td:nth-child(3)').text());
  $('#edit_project_status').val(row.find('td:nth-child(4)').text().toLowerCase());
  $('#editModal').removeClass('hidden flex');
});

$('#editForm').on('submit', function (e) {
  e.preventDefault();

  const id = $('#edit_project_id').val();
  const data = {
    name: $('#edit_project_name').val(),
    description: $('#edit_project_description').val(),
    status: $('#edit_project_status').val(),
  };

  $.ajax({
    type: 'PUT',
    url: `/api/projects/update/${id}`,
    data: data,
    success: function (res) {
      alert(res.message || 'Project updated!');
      $('#editModal').addClass('hidden');
      selectedProjectId = null;
      location.reload();
    },
    error: function (err) {
      alert('Update failed: ' + (err.responseJSON?.message || 'Unknown error'));
    }
  });
});

// Delete button functionality
$(document).on('click', '.deleteBtn', function () {
  const row = $(this).closest('tr');
  const id = row.data('id');

  if (confirm('Are you sure you want to delete this project?')) {
    $.ajax({
      type: 'DELETE',
      url: `/api/projects/delete/${id}`,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (res) {
        alert(res.message || 'Project deleted!');
        row.remove();
      },
      error: function (err) {
        alert('Delete failed: ' + (err.responseJSON?.message || 'Unknown error'));
      }
    });
  }
});


</script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>
$('#projectForm').on('submit', function (e) {
  e.preventDefault();

  const data = {
    name: $('#project_name').val(),
    description: $('#project_description').val(),
    status: $('#project_status').val(),
  };

  $.ajax({
    type: 'POST',
    url: '/api/projects/insert',
    data: data,
    success: function (res) {
      alert(res.message || 'Project added!');
      location.reload(); // or refresh your table dynamically
    },
    error: function (err) {
      alert('Insert failed: ' + err.responseJSON.message);
    }
  });
});
</script>

<!-- Excel-->
<script>
function exportTableToExcel(tableId, filename = 'data.xlsx') {
  const table = document.getElementById(tableId);
  const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
  XLSX.writeFile(wb, filename);
}
</script>

</body>
</html>
