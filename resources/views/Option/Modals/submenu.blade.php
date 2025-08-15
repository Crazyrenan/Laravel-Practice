<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<div id="submenuModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 text-white">
    <div class="bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-bold mb-4">Add / Edit Submenu</h2>
        <form id="submenuForm">
            <input type="hidden" id="submenu_id" name="submenu_id">

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Parent Menu</label>
                <select name="id_menu" id="menu_id_submenu" class="w-full p-2 rounded bg-gray-700 text-white" required>
                    <option value="">Select a Menu</option>
                    <!-- Options will be populated by JavaScript -->
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Submenu Name</label>
                <input type="text" name="nama" id="nama_submenu" class="w-full p-2 rounded bg-gray-700 text-white" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Choose Icon</label>
                <div id="iconPickerSubmenu" class="grid grid-cols-6 gap-2 bg-gray-700 p-2 rounded">
                    @php
                        $icons = [
                            'fa-solid fa-list', 'fa-solid fa-table-cells', 'fa-solid fa-pencil',
                            'fa-solid fa-trash', 'fa-solid fa-eye', 'fa-solid fa-user-plus',
                            'fa-solid fa-gear', 'fa-solid fa-magnifying-glass', 'fa-solid fa-arrow-right-from-bracket',
                            'fa-solid fa-calendar', 'fa-solid fa-plus', 'fa-solid fa-star',
                        ];
                    @endphp
                    @foreach($icons as $ic)
                        <div class="icon-wrapper-submenu cursor-pointer p-2 rounded flex items-center justify-center hover:bg-gray-600"
                            data-icon="{{ $ic }}">
                            <i class="{{ $ic }} text-white text-xl"></i>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" id="selected_icon_submenu" name="icon">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Link</label>
                <input type="text" name="link" id="link_submenu" class="w-full p-2 rounded bg-gray-700 text-white">
            </div>

            <div class="mb-4 flex items-center gap-4">
                <div>
                    <label class="font-semibold">Status</label>
                    <select name="status" id="status_submenu" class="p-2 rounded bg-gray-700 text-white">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="font-semibold">Position</label>
                    <input type="number" name="position" id="position_submenu" class="w-20 p-2 rounded bg-gray-700 text-white" required>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" class="bg-gray-600 hover:bg-gray-500 px-4 py-2 rounded-lg" onclick="closeModal('submenuModal')">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg">Save Submenu</button>
            </div>
        </form>
    </div>
</div>
<div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 text-white">
    <div class="bg-gray-800 rounded-xl shadow-lg w-full max-w-sm p-6 relative">
        <h3 id="messageModalTitle" class="text-xl font-bold mb-4">Message</h3>
        <p id="messageModalBody" class="mb-4"></p>
        <div class="flex justify-end gap-2">
            <button type="button" id="messageModalClose" class="bg-gray-600 hover:bg-gray-500 px-4 py-2 rounded-lg" onclick="closeModal('messageModal')">Close</button>
            <button type="button" id="messageModalConfirm" class="bg-red-600 hover:bg-red-500 px-4 py-2 rounded-lg hidden">Delete</button>
        </div>
    </div>
</div>
