let selectedProjectId = null;

// Enhanced table render function
function renderProjectTable(data) {
    if (!data.length) {
        $('#project-table').html('<tr><td colspan="10" class="text-center py-8 text-white/60">🔍 No projects found.</td></tr>');
        return;
    }
    
    let rows = '';
    data.forEach((project, i) => {
        const statusClass = getStatusClass(project.status);
        const progress = project.progress || Math.floor(Math.random() * 100); // Mock progress if not available
        const budget = project.budget ? `$${parseFloat(project.budget).toLocaleString()}` : '-';
        const startDate = project.start_date ? new Date(project.start_date).toLocaleDateString() : '-';
        const endDate = project.end_date ? new Date(project.end_date).toLocaleDateString() : '-';
        const createdAt = new Date(project.created_at).toLocaleDateString();
            
        rows += `
            <tr class="hover:bg-white/5 transition-all duration-200 cursor-pointer" data-id="${project.id}">
                <td class="px-4 py-3 text-center">${i + 1}</td>
                <td class="px-4 py-3 font-semibold">${project.name}</td>
                <td class="px-4 py-3 max-w-xs truncate" title="${project.description}">${project.description}</td>
                <td class="px-4 py-3">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: ${progress}%"></div>
                    </div>
                    <span class="text-xs text-white/60 mt-1">${progress}%</span>
                </td>
                <td class="px-4 py-3">
                    <span class="${statusClass}">${project.status}</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors" data-id="${project.id}">
                        🗑️ Delete
                    </button>
                </td>
            </tr>
        `;
    });
    $('#project-table').html(rows);
}

function getStatusClass(status) {
    switch(status) {
        case 'active': return 'status-active';
        case 'inactive': return 'status-inactive';
        case 'completed': return 'status-completed';
        case 'on-hold': return 'status-on-hold';
        default: return 'status-inactive';
    }
}

// Load initial data
$(document).ready(function () {
    $.get('/api/projects/search', function (data) {
        renderProjectTable(data);
    }).fail(function() {
        console.error('Could not load initial project data');
        $('#project-table').html('<tr><td colspan="10" class="text-center py-8 text-red-400">❌ Failed to load data</td></tr>');
    });
});

// Search functionality
$('#searchBtn').on('click', function () {
    const column = $('#columnSelect').val();
    const value = $('#searchValue').val().trim();
    
    if (!column || !value) {
        $.get('/api/projects/search', function (data) {
            renderProjectTable(data);
        });
        return;
    }
    
    $.get(`/api/projects/search?column=${encodeURIComponent(column)}&value=${encodeURIComponent(value)}`, function (data) {
        renderProjectTable(data);
    }).fail(function() {
        alert('❌ Search failed. Please try again.');
    });
});

// Enter key search
$('#searchValue').on('keypress', function(e) {
    if (e.which === 13) {
        $('#searchBtn').click();
    }
});

// Row selection
$(document).on('click', '#project-table tr[data-id]', function () {
    $('#project-table tr').removeClass('selected');
    $(this).addClass('selected');
    selectedProjectId = $(this).data('id');
});

// Insert Modal Management
$('#openInsertProjectModal').on('click', function () {
    $('#insertProjectModal').removeClass('hidden');
    document.body.style.overflow = 'hidden';
    // Set default start date to today
    const today = new Date().toISOString().split('T')[0];
    $('#project_start_date').val(today);
});

$('#closeInsertProjectModal').on('click', function () {
    closeInsertModal();
});

function closeInsertModal() {
    $('#insertProjectModal').addClass('hidden');
    document.body.style.overflow = 'auto';
    $('#projectForm')[0].reset();
    $('#submitBtn').html('✅ Submit').prop('disabled', false);
}

function closeEditModal() {
    $('#editModal').addClass('hidden');
    document.body.style.overflow = 'auto';
    selectedProjectId = null;
}

// Close modal on backdrop click
$('#insertProjectModal').on('click', function(e) {
    if (e.target === this) {
        closeInsertModal();
    }
});

$('#editModal').on('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Insert project form submission
$('#projectForm').on('submit', function (e) {
    e.preventDefault();
    
    // Validate required fields
    const requiredFields = ['name', 'description', 'status'];
    let isValid = true;
    let firstInvalidField = null;
    
    requiredFields.forEach(field => {
        const element = $(`[name="${field}"]`);
        const value = element.val();
        
        if (!value || value.trim() === '') {
            isValid = false;
            element.css('border-color', '#FF3B30');
            if (!firstInvalidField) {
                firstInvalidField = element;
            }
        } else {
            element.css('border-color', 'rgba(255, 255, 255, 0.2)');
        }
    });
    
    if (!isValid) {
        alert('⚠️ Please fill in all required fields (marked with *)');
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
        return;
    }
    
    // Show loading state
    const submitBtn = $('#submitBtn');
    const originalText = submitBtn.html();
    submitBtn.html('⏳ Submitting...').prop('disabled', true);
    
    const data = {
        name: $('#project_name').val(),
        description: $('#project_description').val(),
        manager: $('#project_manager').val(),
        budget: $('#project_budget').val(),
        start_date: $('#project_start_date').val(),
        end_date: $('#project_end_date').val(),
        status: $('#project_status').val(),
    };
    
    $.ajax({
        type: 'POST',
        url: '/api/projects/insert',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            alert('✅ ' + (res.message || 'Project added successfully!'));
            closeInsertModal();
            // Refresh table
            $.get('/api/projects/search', function (data) {
                renderProjectTable(data);
            });
        },
        error: function (xhr) {
            console.error('Insert error:', xhr);
            let errorMessage = '❌ Insert failed. ';
            
            if (xhr.status === 422) {
                try {
                    const errors = JSON.parse(xhr.responseText);
                    if (errors.errors) {
                        errorMessage += 'Validation errors: ' + Object.values(errors.errors).flat().join(', ');
                    }
                } catch (e) {
                    errorMessage += 'Validation failed.';
                }
            } else {
                errorMessage += xhr.responseJSON?.message || 'Unknown error';
            }
            
            alert(errorMessage);
        },
        complete: function() {
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});

// Edit functionality
$('#editSelectedBtn').on('click', function () {
    if (!selectedProjectId) {
        alert('⚠️ Please select a project row first!');
        return;
    }
    
    const row = $(`#project-table tr[data-id="${selectedProjectId}"]`);
    
    $('#edit_project_id').val(selectedProjectId);
    $('#edit_project_name').val(row.find('td:nth-child(2)').text());
    $('#edit_project_description').val(row.find('td:nth-child(3)').attr('title') || row.find('td:nth-child(3)').text());
    $('#edit_project_manager').val(row.find('td:nth-child(4)').text() === '-' ? '' : row.find('td:nth-child(4)').text());
    
    // Handle budget
    const budgetText = row.find('td:nth-child(5)').text();
    if (budgetText !== '-') {
        $('#edit_project_budget').val(budgetText.replace('$', '').replace(/,/g, ''));
    }
    
    $('#edit_project_status').val(row.find('td:nth-child(9) span').text().toLowerCase());
    $('#editModal').removeClass('hidden');
    document.body.style.overflow = 'hidden';
});

// Edit form submission
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
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            alert('✅ ' + (res.message || 'Project updated successfully!'));
            closeEditModal();
            // Refresh table
            $.get('/api/projects/search', function (data) {
                renderProjectTable(data);
            });
        },
        error: function (xhr) {
            console.error('Update error:', xhr);
            alert('❌ Update failed: ' + (xhr.responseJSON?.message || 'Unknown error'));
        }
    });
});

// Delete functionality
$(document).on('click', '.deleteBtn', function (e) {
    e.stopPropagation(); // Prevent row selection
    
    const id = $(this).data('id');
    const row = $(this).closest('tr');
    
    if (confirm('🗑️ Are you sure you want to delete this project? This action cannot be undone.')) {
        $.ajax({
            type: 'DELETE',
            url: `/api/projects/delete/${id}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                alert('✅ ' + (res.message || 'Project deleted successfully!'));
                row.fadeOut(300, function() {
                    $(this).remove();
                });
                if (selectedProjectId == id) {
                    selectedProjectId = null;
                }
            },
            error: function (xhr) {
                console.error('Delete error:', xhr);
                alert('❌ Delete failed: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    }
});

// Export function
function exportTableToExcel(tableId, filename = 'data.xlsx') {
    const table = document.getElementById(tableId);
    if (!table) {
        alert('❌ No table found to export');
        return;
    }
    
    try {
        const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
        XLSX.writeFile(wb, filename);
        alert('✅ Export successful!');
    } catch (error) {
        console.error('Export error:', error);
        alert('❌ Export failed');
    }
}

// Parallax effect for floating orbs
document.addEventListener('mousemove', function(e) {
    const orbs = document.querySelectorAll('.floating-orb');
    const x = e.clientX / window.innerWidth;
    const y = e.clientY / window.innerHeight;
    
    orbs.forEach((orb, index) => {
        const speed = (index + 1) * 0.5;
        const xPos = (x - 0.5) * speed * 20;
        const yPos = (y - 0.5) * speed * 20;
        orb.style.transform = `translate(${xPos}px, ${yPos}px)`;
    });
});

// Enhanced keyboard support
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.target.id === 'searchValue') {
        e.preventDefault();
        $('#searchBtn').click();
    }
    if (e.key === 'Escape') {
        $('#closeInsertProjectModal').click();
        closeEditModal();
    }
});

// Form validation styling
$(document).on('input change', 'input[required], select[required], textarea[required]', function() {
    const element = $(this);
    if (element.val() && element.val().trim() !== '') {
        element.css('border-color', 'rgba(52, 199, 89, 0.5)'); // Green for valid
    } else {
        element.css('border-color', 'rgba(255, 255, 255, 0.2)'); // Default
    }
});

// Date validation
$('#project_start_date, #edit_project_start_date').on('change', function() {
    const startDate = $(this).val();
    const endDateField = $(this).attr('id').includes('edit') ? '#edit_project_end_date' : '#project_end_date';
    const endDate = $(endDateField).val();
    
    if (startDate && endDate && startDate > endDate) {
        alert('⚠️ Start date cannot be later than end date');
        $(endDateField).focus();
    }
});

$('#project_end_date, #edit_project_end_date').on('change', function() {
    const endDate = $(this).val();
    const startDateField = $(this).attr('id').includes('edit') ? '#edit_project_start_date' : '#project_start_date';
    const startDate = $(startDateField).val();
    
    if (startDate && endDate && endDate < startDate) {
        alert('⚠️ End date cannot be earlier than start date');
        $(this).focus();
    }
});