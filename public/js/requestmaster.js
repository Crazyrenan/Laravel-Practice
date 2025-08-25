let selectedRequestId = null;

// Enhanced table render function
function renderRequestTable(data) {
    if (!data.length) {
        $('#request-table').html('<tr><td colspan="7" class="text-center py-8 text-white/60">🔍 No requests found.</td></tr>');
        return;
    }
    
    let rows = '';
    data.forEach((request, i) => {
        const statusClass = request.status === 'active' ? 'status-active' : 'status-inactive';
        const deptClass = `dept-${request.department.toLowerCase()}`;
        
        rows += `
            <tr class="hover:bg-white/5 transition-all duration-200 cursor-pointer" data-id="${request.id}">
                <td class="px-4 py-3 text-center">${i + 1}</td>
                <td class="px-4 py-3">${request.name}</td>
                <td class="px-4 py-3">${request.email}</td>
                <td class="px-4 py-3">${request.phone}</td>
                <td class="px-4 py-3">
                    <span class="dept-badge ${deptClass}">${request.department}</span>
                </td>
                <td class="px-4 py-3">
                    <span class="${statusClass}">${request.status}</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors" data-id="${request.id}">
                        🗑️ Delete
                    </button>
                </td>
            </tr>
        `;
    });
    $('#request-table').html(rows);
}

// Load initial data
$(document).ready(function () {
    $.get('/api/requests/search', function (data) {
        renderRequestTable(data);
    }).fail(function() {
        console.error('Could not load initial request data');
        $('#request-table').html('<tr><td colspan="7" class="text-center py-8 text-red-400">❌ Failed to load data</td></tr>');
    });
});

// Search functionality
$('#searchBtn').on('click', function () {
    const column = $('#columnSelect').val();
    const value = $('#searchValue').val().trim();
    
    if (!column || !value) {
        $.get('/api/requests/search', function (data) {
            renderRequestTable(data);
        });
        return;
    }
    
    $.get(`/api/requests/search?column=${encodeURIComponent(column)}&value=${encodeURIComponent(value)}`, function (data) {
        renderRequestTable(data);
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
$(document).on('click', '#request-table tr[data-id]', function () {
    $('#request-table tr').removeClass('selected');
    $(this).addClass('selected');
    selectedRequestId = $(this).data('id');
});

// Insert Modal Management
$('#openInsertRequestModal').on('click', function () {
    $('#insertRequestModal').removeClass('hidden');
    document.body.style.overflow = 'hidden';
});

$('#closeInsertRequestModal').on('click', function () {
    closeInsertModal();
});

function closeInsertModal() {
    $('#insertRequestModal').addClass('hidden');
    document.body.style.overflow = 'auto';
    $('#requestForm')[0].reset();
    $('#submitBtn').html('✅ Submit').prop('disabled', false);
}

function closeEditModal() {
    $('#editModal').addClass('hidden');
    document.body.style.overflow = 'auto';
    selectedRequestId = null;
}

// Close modal on backdrop click
$('#insertRequestModal').on('click', function(e) {
    if (e.target === this) {
        closeInsertModal();
    }
});

$('#editModal').on('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Insert request form submission
$('#requestForm').on('submit', function (e) {
    e.preventDefault();
    
    // Validate required fields
    const requiredFields = ['name', 'email', 'phone', 'department', 'status'];
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
        name: $('#request_name').val(),
        email: $('#request_email').val(),
        phone: $('#request_phone').val(),
        department: $('#request_department').val(),
        status: $('#request_status').val(),
    };
    
    $.ajax({
        type: 'POST',
        url: '/api/request/insert',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            alert('✅ ' + (res.message || 'Request added successfully!'));
            closeInsertModal();
            // Refresh table
            $.get('/api/request/search', function (data) {
                renderRequestTable(data);
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
    if (!selectedRequestId) {
        alert('⚠️ Please select a request row first!');
        return;
    }
    
    const row = $(`#request-table tr[data-id="${selectedRequestId}"]`);
    
    $('#edit_request_id').val(selectedRequestId);
    $('#edit_request_name').val(row.find('td:nth-child(2)').text());
    $('#edit_request_email').val(row.find('td:nth-child(3)').text());
    $('#edit_request_phone').val(row.find('td:nth-child(4)').text());
    $('#edit_request_department').val(row.find('td:nth-child(5) span').text());
    $('#edit_request_status').val(row.find('td:nth-child(6) span').text().toLowerCase());
    
    $('#editModal').removeClass('hidden');
    document.body.style.overflow = 'hidden';
});

// Edit form submission
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
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            alert('✅ ' + (res.message || 'Request updated successfully!'));
            closeEditModal();
            // Refresh table
            $.get('/api/requests/search', function (data) {
                renderRequestTable(data);
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
    
    if (confirm('🗑️ Are you sure you want to delete this request? This action cannot be undone.')) {
        $.ajax({
            type: 'DELETE',
            url: `/api/requests/delete/${id}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                alert('✅ ' + (res.message || 'Request deleted successfully!'));
                row.fadeOut(300, function() {
                    $(this).remove();
                });
                if (selectedRequestId == id) {
                    selectedRequestId = null;
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
        $('#closeInsertRequestModal').click();
        closeEditModal();
    }
});

// Form validation styling
$(document).on('input change', 'input[required], select[required]', function() {
    const element = $(this);
    if (element.val() && element.val().trim() !== '') {
        element.css('border-color', 'rgba(52, 199, 89, 0.5)'); // Green for valid
    } else {
        element.css('border-color', 'rgba(255, 255, 255, 0.2)'); // Default
    }
});
