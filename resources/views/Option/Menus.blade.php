@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Menu Management</h2>

    <!-- Add Menu Form -->
    <form id="menuForm">
        @csrf
        <div class="row">
            <div class="col-md-2">
                <label for="icon">Icon</label>
                <input type="text" id="icon" name="icon" class="form-control" placeholder="Click icon below" readonly>
                <div class="icon-list mt-2 p-2 border" style="max-height: 150px; overflow-y: auto;">
                    @php
                        $icons = [
                            'fa-solid fa-home', 'fa-solid fa-user', 'fa-solid fa-cog',
                            'fa-solid fa-chart-bar', 'fa-solid fa-file', 'fa-solid fa-folder',
                            'fa-solid fa-envelope', 'fa-solid fa-shopping-cart', 'fa-solid fa-lock',
                            'fa-solid fa-right-from-bracket', 'fa-solid fa-search', 'fa-solid fa-plus',
                        ];
                    @endphp
                    @foreach($icons as $ic)
                        <i class="{{ $ic }} icon-choice"
                           style="font-size: 18px; margin: 5px; cursor: pointer;"
                           data-icon="{{ $ic }}"></i>
                    @endforeach
                </div>
            </div>

            <div class="col-md-3">
                <label>Menu Name</label>
                <input type="text" name="nama_menu" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label>Link</label>
                <input type="text" name="link" class="form-control">
            </div>

            <div class="col-md-1">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1">Show</option>
                    <option value="0">Hide</option>
                </select>
            </div>

            <div class="col-md-1">
                <label>Sub Menu</label>
                <select name="sub_menu" class="form-control">
                    <option value="1">True</option>
                    <option value="0">False</option>
                </select>
            </div>

            <div class="col-md-1">
                <label>Position</label>
                <input type="number" name="position" class="form-control" min="1" required>
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Add</button>
            </div>
        </div>
    </form>

    <hr>

    <!-- Menu Table -->
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="menuTable">
    <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu Name</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Menu</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700" id="menuTableBody">
        <!-- rows inserted here by JS -->
    </tbody>
    </table>

</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Icon Picker Click
    document.querySelectorAll('.icon-choice').forEach(el => {
        el.addEventListener('click', function() {
            document.getElementById('icon').value = this.getAttribute('data-icon');
        });
    });

    // Load Menus JSON from /menus/data
    function loadMenus() {
        $.get("{{ route('menus.index') }}", function(data) {
            let rows = '';
            data.forEach(menu => {
                rows += `
                    <tr>
                        <td>${menu.id}</td>
                        <td><i class="${menu.icon}"></i></td>
                        <td>${menu.nama_menu}</td>
                        <td>${menu.link ?? ''}</td>
                        <td>${menu.status == 1 ? 'Show' : 'Hide'}</td>
                        <td>${menu.sub_menu == 1 ? 'True' : 'False'}</td>
                        <td>${menu.position}</td>
                        <td>
                            <button class="btn btn-sm btn-danger deleteBtn" data-id="${menu.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
            $('#menuTable tbody').html(rows);
        });
    }

    // Add Menu POST to /menus
    $('#menuForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('menus.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function() {
                $('#menuForm')[0].reset();
                loadMenus();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message || 'Failed to add menu');
            }
        });
    });

    // Delete Menu DELETE to /menus/{id}
    $(document).on('click', '.deleteBtn', function() {
        if (!confirm("Delete this menu?")) return;
        let id = $(this).data('id');
        $.ajax({
            url: `/menus/${id}`,
            method: 'DELETE',
            data: {_token: "{{ csrf_token() }}"},
            success: function() {
                loadMenus();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message || 'Failed to delete menu');
            }
        });
    });

    // Initial load
    loadMenus();
</script>
@endsection
