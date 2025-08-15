document.addEventListener("DOMContentLoaded", function() {
    let menusData = [];
    let submenusData = [];
    
    const addMenuBtn = document.getElementById('openMenuModalBtn');
    const addSubmenuBtn = document.getElementById('openSubmenuModalBtn');
    if (addMenuBtn) addMenuBtn.disabled = true;
    if (addSubmenuBtn) addSubmenuBtn.disabled = true;


    window.openMessageModal = function(title, body, confirmCallback = null) {
        document.getElementById('messageModalTitle').innerText = title;
        document.getElementById('messageModalBody').innerText = body;
        const confirmBtn = document.getElementById('messageModalConfirm');
        const closeBtn = document.getElementById('messageModalClose');

        if (confirmCallback) {
            confirmBtn.classList.remove('hidden');
            closeBtn.classList.add('hidden');
            confirmBtn.onclick = () => {
                confirmCallback();
                closeModal('messageModal');
            };
        } else {
            confirmBtn.classList.add('hidden');
            closeBtn.classList.remove('hidden');
        }
        document.getElementById('messageModal').classList.remove('hidden');
    }


    window.openModal = function(modalId, isEdit = false) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            if (!isEdit) {
                const form = modal.querySelector('form');
                if (form) form.reset();
                // Reset icon selection for the main menu modal
                if (modalId === 'menuModal') {
                    const iconPicker = document.getElementById('iconPicker');
                    iconPicker.querySelectorAll('.icon-wrapper').forEach(w => w.classList.remove('bg-gray-600', 'ring-2', 'ring-blue-400'));
                    const selectedIconInput = document.getElementById('selected_icon');
                    if (selectedIconInput) selectedIconInput.value = '';
                }
            }
        }
        if (modalId === 'submenuModal') {
            // Check if menusData is populated before attempting to populate the dropdown
            if (menusData.length > 0) {
                populateMenuDropdown();
            } else {
                console.error("Menus data is not yet available to populate the dropdown.");
            }
        }
    }

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            const form = modal.querySelector('form');
            if (form) form.reset();
        }
    }


    function populateMenuDropdown() {
        const select = document.getElementById('menu_id_submenu');
        select.innerHTML = '<option value="">Select a Menu</option>';
        const subMenusEnabled = menusData.filter(menu => menu.sub_menu == 1);
        subMenusEnabled.forEach(menu => {
            const option = document.createElement('option');
            option.value = menu.id;
            option.textContent = menu.nama_menu;
            select.appendChild(option);
        });
    }

    
    function fetchAndRenderTables() {
        return fetch('/api/menus-submenus/data')
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                menusData = data.menus;
                submenusData = data.submenus;
                renderMenusTable(menusData);
                renderSubmenusTable(submenusData);
            })
            .catch(error => console.error('Error fetching data:', error));
    }


    function renderMenusTable(menus) {
        const tableBody = document.getElementById('menusTableBody');
        if (!tableBody) return; 
        tableBody.innerHTML = '';
        menus.forEach(menu => {
            const row = document.createElement('tr');
            row.classList.add('border-b', 'border-gray-700', 'hover:bg-gray-700');
            row.innerHTML = `
                <td class="px-4 py-2"><i class="${menu.icon} mr-2"></i></td>
                <td class="px-4 py-2">${menu.nama_menu}</td>
                <td class="px-4 py-2">${menu.link || '-'}</td>
                <td class="px-4 py-2">${menu.status ? 'Active' : 'Inactive'}</td>
                <td class="px-4 py-2">${menu.sub_menu ? 'Yes' : 'No'}</td>
                <td class="px-4 py-2">${menu.position}</td>
                <td class="px-4 py-2 text-center">
                    <button class="editMenuBtn bg-yellow-500 hover:bg-yellow-400 text-white px-3 py-1 rounded" data-id="${menu.id}">Edit</button>
                    <button class="deleteMenuBtn bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded" data-id="${menu.id}">Delete</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Renders the submenus table
    function renderSubmenusTable(submenus) {
        const tableBody = document.getElementById('submenusTableBody');
        if (!tableBody) return; 
        tableBody.innerHTML = '';
        submenus.forEach(submenu => {
            const menuParent = menusData.find(m => m.id == submenu.id_menu);
            const parentName = menuParent ? menuParent.nama_menu : 'N/A';
            const row = document.createElement('tr');
            row.classList.add('border-b', 'border-gray-700', 'hover:bg-gray-700');
            row.innerHTML = `
                <td class="px-4 py-2">${parentName}</td>
                <td class="px-4 py-2"><i class="${submenu.icon} mr-2"></i></td>
                <td class="px-4 py-2">${submenu.nama}</td>
                <td class="px-4 py-2">${submenu.link || '-'}</td>
                <td class="px-4 py-2">${submenu.status ? 'Active' : 'Inactive'}</td>
                <td class="px-4 py-2">${submenu.position}</td>
                <td class="px-4 py-2 text-center">
                    <button class="editSubmenuBtn bg-yellow-500 hover:bg-yellow-400 text-white px-3 py-1 rounded" data-id="${submenu.id}">Edit</button>
                    <button class="deleteSubmenuBtn bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded" data-id="${submenu.id}">Delete</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }
   
    fetchAndRenderTables().then(() => {
      if (addMenuBtn) addMenuBtn.disabled = false;
      if (addSubmenuBtn) addSubmenuBtn.disabled = false;
    });


    const iconPicker = document.getElementById('iconPicker');
    const selectedIconInput = document.getElementById('selected_icon');
    if (iconPicker) {
        iconPicker.querySelectorAll('.icon-wrapper').forEach(wrapper => {
            wrapper.addEventListener('click', function() {
                iconPicker.querySelectorAll('.icon-wrapper').forEach(w => w.classList.remove('bg-gray-600', 'ring-2', 'ring-blue-400'));
                this.classList.add('bg-gray-600', 'ring-2', 'ring-blue-400');
                selectedIconInput.value = this.dataset.icon;
            });
        });
    }

    const menuForm = document.getElementById('menuForm');
    if (menuForm) {
        menuForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('menu_id').value;
            const position = parseInt(document.getElementById('position').value);

            // Check if position is already taken for new menus
            if (!id) {
                const isPositionTaken = menusData.some(menu => menu.position === position);
                if (isPositionTaken) {
                    openMessageModal('Error', 'This position is already taken by another menu. Please choose a different position.');
                    return;
                }
            } else {
                // For editing, check if position is taken by another menu (not the current one)
                const isPositionTaken = menusData.some(menu => menu.position === position && menu.id !== parseInt(id));
                if (isPositionTaken) {
                    openMessageModal('Error', 'This position is already taken by another menu. Please choose a different position.');
                    return;
                }
            }

            const url = id ? `/api/menus-submenus/menu/update/${id}` : `/api/menus-submenus/menu/store`;
            const formData = new FormData(menuForm);
            
            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: formData
            })
            .then(res => {
                if (!res.ok) {
                    return res.text().then(text => { throw new Error(text) });
                }
                return res.json();
            })
            .then(res => {
                closeModal('menuModal');
                fetchAndRenderTables();
            })
            .catch(error => console.error('Error submitting menu form:', error));
        });
    }

    // Event listener for the submenu form
    const submenuForm = document.getElementById('submenuForm');
    if (submenuForm) {
        // Corrected icon selection logic for the submenu modal
        const iconPickerSubmenu = document.getElementById('iconPickerSubmenu');
        const selectedIconInputSubmenu = document.getElementById('selected_icon_submenu');
        if (iconPickerSubmenu) {
            iconPickerSubmenu.querySelectorAll('.icon-wrapper-submenu').forEach(wrapper => {
                wrapper.addEventListener('click', function() {
                    iconPickerSubmenu.querySelectorAll('.icon-wrapper-submenu').forEach(w => w.classList.remove('bg-gray-600', 'ring-2', 'ring-blue-400'));
                    this.classList.add('bg-gray-600', 'ring-2', 'ring-blue-400');
                    selectedIconInputSubmenu.value = this.dataset.icon;
                });
            });
        }
        
        submenuForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('submenu_id').value;
            const parentId = parseInt(document.getElementById('menu_id_submenu').value);
            const position = parseInt(document.getElementById('position_submenu').value);

            // Filter submenus for the specific parent menu
            const submenusForParent = submenusData.filter(s => s.id_menu === parentId);

            // Check if position is already taken for new submenus within the parent
            if (!id) {
                const isPositionTaken = submenusForParent.some(submenu => submenu.position === position);
                if (isPositionTaken) {
                    openMessageModal('Error', 'This position is already taken by another submenu in this parent menu. Please choose a different position.');
                    return;
                }
            } else {
                // For editing, check if position is taken by another submenu (not the current one)
                const isPositionTaken = submenusForParent.some(submenu => submenu.position === position && submenu.id !== parseInt(id));
                if (isPositionTaken) {
                    openMessageModal('Error', 'This position is already taken by another submenu in this parent menu. Please choose a different position.');
                    return;
                }
            }

            const url = id ? `/api/menus-submenus/submenu/update/${id}` : `/api/menus-submenus/submenu/store`;
            const formData = new FormData(submenuForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json' 
                    },
                    body: formData
                });
                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Server-side error:', errorData);
                    throw new Error(errorData.message || 'Server-side validation failed.');
                }
                const result = await response.json();
                console.log('Success:', result);
                openMessageModal('Success', 'Submenu saved successfully!');
                closeModal('submenuModal');
                fetchAndRenderTables();
            } catch (error) {
                console.error('Error submitting submenu form:', error);
                openMessageModal('Error', error.message || 'A network error occurred. Please try again.');
            }
        });
    }

    // Event listener for all click events on the document
    document.addEventListener('click', function(e) {
        const target = e.target;
        
        // Handle menu deletion
        const deleteMenuBtn = target.closest('.deleteMenuBtn');
        if (deleteMenuBtn) {
            const id = deleteMenuBtn.dataset.id;
            openMessageModal('Delete Menu', 'Are you sure you want to delete this menu?', () => {
                fetch(`/api/menus-submenus/menu/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                }).then(res => {
                    if (!res.ok) {
                        return res.json().then(errorData => { throw new Error(errorData.message) });
                    }
                    return res.json();
                }).then(() => {
                    fetchAndRenderTables();
                    openMessageModal('Success', 'Menu deleted successfully!');
                }).catch(error => {
                    console.error('Error deleting menu:', error);
                    openMessageModal('Error', error.message || 'An error occurred while deleting the menu.');
                });
            });
        }

        // Handle submenu deletion
        const deleteSubmenuBtn = target.closest('.deleteSubmenuBtn');
        if (deleteSubmenuBtn) {
            const id = deleteSubmenuBtn.dataset.id;
            openMessageModal('Delete Submenu', 'Are you sure you want to delete this submenu?', () => {
                fetch(`/api/menus-submenus/submenu/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                }).then(res => {
                    if (!res.ok) {
                        return res.json().then(errorData => { throw new Error(errorData.message) });
                    }
                    return res.json();
                }).then(() => {
                    fetchAndRenderTables();
                    openMessageModal('Success', 'Submenu deleted successfully!');
                }).catch(error => {
                    console.error('Error deleting submenu:', error);
                    openMessageModal('Error', error.message || 'An error occurred while deleting the submenu.');
                });
            });
        }

        // Handle menu editing
        const editMenuBtn = target.closest('.editMenuBtn');
        if (editMenuBtn) {
            const id = editMenuBtn.dataset.id;
            const menu = menusData.find(m => m.id == id);
            if (menu) {
                openModal('menuModal', true);
                document.getElementById('menu_id').value = menu.id;
                document.getElementById('nama_menu').value = menu.nama_menu;
                document.getElementById('link').value = menu.link || '';
                document.getElementById('status').value = menu.status;
                document.getElementById('sub_menu').value = menu.sub_menu;
                document.getElementById('position').value = menu.position;
                
                const iconToSelect = menu.icon || '';
                const selectedIconInput = document.getElementById('selected_icon');
                selectedIconInput.value = iconToSelect;
                const iconPicker = document.getElementById('iconPicker');
                iconPicker.querySelectorAll('.icon-wrapper').forEach(w => {
                    w.classList.toggle('bg-gray-600', w.dataset.icon === iconToSelect);
                    w.classList.toggle('ring-2', w.dataset.icon === iconToSelect);
                    w.classList.toggle('ring-blue-400', w.dataset.icon === iconToSelect);
                });
            }
        }

        // Handle submenu editing
        const editSubmenuBtn = target.closest('.editSubmenuBtn');
        if (editSubmenuBtn) {
            const id = editSubmenuBtn.dataset.id;
            const submenu = submenusData.find(s => s.id == id);
            if (submenu) {
                openModal('submenuModal', true);
                document.getElementById('submenu_id').value = submenu.id;
                document.getElementById('nama_submenu').value = submenu.nama;
                
                // This line is key: it sets the correct value from the fetched data
                document.getElementById('menu_id_submenu').value = submenu.id_menu;

                document.getElementById('link_submenu').value = submenu.link || '';
                document.getElementById('status_submenu').value = submenu.status;
                document.getElementById('position_submenu').value = submenu.position;
                
                const iconToSelectSubmenu = submenu.icon || '';
                const selectedIconInputSubmenu = document.getElementById('selected_icon_submenu');
                selectedIconInputSubmenu.value = iconToSelectSubmenu;
                const iconPickerSubmenu = document.getElementById('iconPickerSubmenu');
                if (iconPickerSubmenu) {
                    iconPickerSubmenu.querySelectorAll('.icon-wrapper-submenu').forEach(w => {
                        w.classList.toggle('bg-gray-600', w.dataset.icon === iconToSelectSubmenu);
                        w.classList.toggle('ring-2', w.dataset.icon === iconToSelectSubmenu);
                        w.classList.toggle('ring-blue-400', w.dataset.icon === iconToSelectSubmenu);
                    });
                }
            }
        }
    });
});
