@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Sub Menu Management</h2>

    <!-- Add Sub Menu Form -->
    <form id="subMenuForm">
        @csrf
        <div class="row">
            <!-- Parent Menu Dropdown -->
            <div class="col-md-2">
                <label>Parent Menu</label>
                <select name="id_menu" class="form-control" required>
                    <option value="">-- Select Menu --</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->nama_menu }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Icon Picker -->
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

            <!-- Name -->
            <div class="col-md-3">
                <label>Sub Menu Name</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <!-- Link -->
            <div class="col-md-3">
                <label>Link</label>
                <input type="text" name="link" class="form-control">
            </div>

            <!-- Status -->
            <div class="col-md-1">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1">Show</option>
                    <option value="0">Hide</option>
                </select>
            </div>

            <!-- Position -->
            <div class="col-md-1">
                <label>Position</label>
                <input type="number" name="position" class="form-control" min="1" required>
            </div>

            <!-- Add Button -->
            <div class="col-md-12 mt-2">
                <button type="submit" class="btn btn-primary w-100">Add</button>
            </div>
        </div>
    </form>

    <hr>

    <!-- Sub Menu Table -->
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="subMenuTable">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent Menu</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700" id="subMenuTableBody">
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

    // Load Sub Menus
    function loadSubMenus() {
        $.get("{{ route('submenus.index') }}", function(data) {
            let rows = '';
            data.forEach(sub => {
                rows += `
                    <tr>
                        <td>${sub.id}</td>
                        <td>${sub.parent_menu}</td>
                        <td><i class="${sub.icon}"></i></td>
                        <td>${sub.nama}</td>
                        <td>${sub.link ?? ''}</td>
                        <td>${sub.status == 1 ? 'Show' : 'Hide'}</td>
                        <td>${sub.position}</td>
                        <td>
                            <button class="btn btn-sm btn-danger deleteBtn" data-id="${sub.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
            $('#subMenuTable tbody').html(rows);
        });
    }

    // Add Sub Menu
    $('#subMenuForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('submenus.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function() {
                $('#subMenuForm')[0].reset();
                loadSubMenus();
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Failed to add sub menu'));
            }
        });
    });

    // Delete Sub Menu
    $(document).on('click', '.deleteBtn', function() {
        if (!confirm("Delete this sub menu?")) return;
        let id = $(this).data('id');
        $.ajax({
            url: `/submenus/${id}`,
            method: 'DELETE',
            data: {_token: "{{ csrf_token() }}"},
            success: function() {
                loadSubMenus();
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Failed to delete sub menu'));
            }
        });
    });

    // Initial load
    loadSubMenus();
</script>
@endsection
