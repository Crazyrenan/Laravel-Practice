<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<div id="menuModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-bold mb-4">Add / Edit Menu</h2>
        <form id="menuForm">
            <input type="hidden" id="menu_id" name="menu_id">

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Menu Name</label>
                <input type="text" name="nama_menu" id="nama_menu" class="w-full p-2 rounded bg-gray-700 text-white" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Choose Icon</label>
                <div id="iconPicker" class="grid grid-cols-6 gap-2 bg-gray-700 p-2 rounded">
                    @php
                        $icons = [
                            'fa-solid fa-home', 'fa-solid fa-user', 'fa-solid fa-cog',
                            'fa-solid fa-chart-bar', 'fa-solid fa-file', 'fa-solid fa-folder',
                            'fa-solid fa-envelope', 'fa-solid fa-shopping-cart', 'fa-solid fa-lock',
                            'fa-solid fa-right-from-bracket', 'fa-solid fa-search', 'fa-solid fa-plus',
                        ];
                    @endphp
                    @foreach($icons as $ic)
                        <div class="icon-wrapper cursor-pointer p-2 rounded flex items-center justify-center hover:bg-gray-600"
                            data-icon="{{ $ic }}">
                            <i class="{{ $ic }} text-white text-xl"></i>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" id="selected_icon" name="icon">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Link</label>
                <input type="text" name="link" id="link" class="w-full p-2 rounded bg-gray-700 text-white">
            </div>

            <div class="mb-4 flex items-center gap-4">
                <div>
                    <label class="font-semibold">Status</label>
                    <select name="status" id="status" class="p-2 rounded bg-gray-700 text-white">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="font-semibold">Submenu</label>
                    <select name="sub_menu" id="sub_menu" class="p-2 rounded bg-gray-700 text-white">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Position</label>
                <input type="number" name="position" id="position" class="w-20 p-2 rounded bg-gray-700 text-white" required>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" class="bg-gray-600 hover:bg-gray-500 px-4 py-2 rounded" onclick="closeModal('menuModal')">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded">Save Menu</button>
            </div>
        </form>
    </div>
</div>
<script>
    // Icon picker logic
    const iconPicker = document.getElementById('iconPicker');
    const selectedIconInput = document.getElementById('selected_icon');

    iconPicker.querySelectorAll('.icon-wrapper').forEach(wrapper => {
        wrapper.addEventListener('click', function() {
            // Remove previous selection
            iconPicker.querySelectorAll('.icon-wrapper').forEach(w => w.classList.remove('bg-gray-600', 'ring-2', 'ring-blue-400'));
            
            // Highlight current selection
            this.classList.add('bg-gray-600', 'ring-2', 'ring-blue-400');

            // Set hidden input
            selectedIconInput.value = this.dataset.icon;
        });
    });
</script>