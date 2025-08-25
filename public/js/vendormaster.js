let selectedVendorId = null;

// Enhanced table render function
function renderVendorTable(data) {
    if (!data.length) {
        $('#vendor-table').html('<tr><td colspan="10" class="text-center py-8 text-white/60">🔍 No vendors found.</td></tr>');
        return;
    }
    
    let rows = '';
    data.forEach((vendor, i) => {
        const statusClass = vendor.status === 'active' ? 'status-active' : 'status-inactive';
        const websiteLink = vendor.website ? 
            `<a href="${vendor.website}" class="text-blue-400 hover:text-blue-200 underline" target="_blank">${vendor.website}</a>` : 
            '-';
            
        rows += `
            <tr class="hover:bg-white/5 transition-all duration-200 cursor-pointer" data-id="${vendor.id}">
                <td class="px-4 py-3 text-center">${i + 1}</td>
                <td class="px-4 py-3">${vendor.name}</td>
                <td class="px-4 py-3">${vendor.email}</td>
                <td class="px-4 py-3">${vendor.phone}</td>
                <td class="px-4 py-3">${vendor.address}</td>
                <td class="px-4 py-3">${vendor.company_name}</td>
                <td class="px-4 py-3">${vendor.tax_id}</td>
                <td class="px-4 py-3">${websiteLink}</td>
                <td class="px-4 py-3">
                    <span class="${statusClass}">${vendor.status}</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors" data-id="${vendor.id}">
                        🗑️ Delete
                    </button>
                </td>
            </tr>
        `;
    });
    $('#vendor-table').html(rows);
}

// Load initial data
$(document).ready(function () {
    $.get('/api/vendors/search', function (data) {
        renderVendorTable(data);
    }).fail(function() {
        console.error('Could not load initial vendor data');
        $('#vendor-table').html('<tr><td colspan="10" class="text-center py-8 text-red-400">❌ Failed to load data</td></tr>');
    });
});

// Search functionality
$('#searchBtn').on('click', function () {
    const column = $('#columnSelect').val();
    const value = $('#searchValue').val().trim();
    
    if (!column || !value) {
        $.get('/api/vendors/search', function (data) {
            renderVendorTable(data);
        });
        return;
    }
    
    $.get(`/api/vendors/search?column=${encodeURIComponent(column)}&value=${encodeURIComponent(value)}`, function (data) {
        renderVendorTable(data);
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
$(document).on('click', '#vendor-table tr[data-id]', function () {
    $('#vendor-table tr').removeClass('selected');
    $(this).addClass('selected');
    selectedVendorId = $(this).data('id');
});

// Insert Modal Management
$('#openInsertVendorModal').on('click', function () {
    $('#insertVendorModal').removeClass('hidden');
    document.body.style.overflow = 'hidden';
});

$('#closeInsertVendorModal').on('click', function () {
    closeInsertModal();
});

function closeInsertModal() {
    $('#insertVendorModal').addClass('hidden');
    document.body.style.overflow = 'auto';
    $('#vendorForm')[0].reset();
    $('#submitBtn').html('✅ Submit').prop('disabled', false);
}

// Close modal on backdrop click
$('#insertVendorModal').on('click', function(e) {
    if (e.target === this) {
        closeInsertModal();
    }
});

// Insert vendor form submission
$('#vendorForm').on('submit', function (e) {
    e.preventDefault();
    
    // Validate required fields
    const requiredFields = ['name', 'email', 'phone', 'address', 'company_name', 'tax_id', 'status'];
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
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            alert('✅ ' + (res.message || 'Vendor added successfully!'));
            closeInsertModal();
            // Refresh table
            $.get('/api/vendors/search', function (data) {
                renderVendorTable(data);
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

// Edit function
$('#editSelectedBtn').on('click', function () {
    if (!selectedVendorId) {
        alert('⚠️ Please select a vendor row first!');
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
    
    // Handle website link
    const websiteCell = row.find('td:nth-child(8)');
    const websiteLink = websiteCell.find('a');
    if (websiteLink.length) {
        $('#edit_vendor_website').val(websiteLink.attr('href'));
    } else {
        $('#edit_vendor_website').val('');
    }
    
    $('#edit_vendor_status').val(row.find('td:nth-child(9) span').text().toLowerCase());
    $('#editModal').removeClass('hidden');
    document.body.style.overflow = 'hidden';
});

// Edit form submission
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
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            alert('✅ ' + (res.message || 'Vendor updated successfully!'));
            $('#editModal').addClass('hidden');
            document.body.style.overflow = 'auto';
            selectedVendorId = null;
            // Refresh table
            $.get('/api/vendors/search', function (data) {
                renderVendorTable(data);
            });
        },
        error: function (xhr) {
            console.error('Update error:', xhr);
            alert('❌ Update failed: ' + (xhr.responseJSON?.message || 'Unknown error'));
        }
    });
});

// Delete 
$(document).on('click', '.deleteBtn', function (e) {
    e.stopPropagation(); // Prevent row selection
    
    const id = $(this).data('id');
    const row = $(this).closest('tr');
    
    if (confirm('🗑️ Are you sure you want to delete this vendor? This action cannot be undone.')) {
        $.ajax({
            type: 'DELETE',
            url: `/api/vendors/delete/${id}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                alert('✅ ' + (res.message || 'Vendor deleted successfully!'));
                row.fadeOut(300, function() {
                    $(this).remove();
                });
                if (selectedVendorId == id) {
                    selectedVendorId = null;
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

// Parallax effect 
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


document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.target.id === 'searchValue') {
        e.preventDefault();
        $('#searchBtn').click();
    }
    if (e.key === 'Escape') {
        $('#closeInsertVendorModal').click();
        $('#editModal').addClass('hidden');
        document.body.style.overflow = 'auto';
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
