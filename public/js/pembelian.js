function renderTable(data) {
    if (!data.length) {
        $('#resultsWrapper').removeClass('hidden').addClass('fade-in');
        $('#tableHead').html('');
        $('#resultBody').html('<tr><td colspan="20" class="text-center py-8 text-white/60">🔍 No results found.</td></tr>');
        return;
    }
    
    const headers = Object.keys(data[0]);
    $('#tableHead').html(`
        <th class="px-2 py-2 text-center">
            <input type="checkbox" id="selectAll" class="modern-checkbox">
        </th>
        ${headers.map(h => `<th class="px-4 py-2">${h}</th>`).join('')}
    `);
    
    const rows = data.map(row => {
        return `<tr class="border-t border-white/10 hover:bg-white/5 transition-all duration-200">
            <td class="px-2 py-2 text-center">
                <input type="checkbox" class="row-checkbox" value="${row.id}">
            </td>
            ${headers.map(col => `<td class="px-4 py-2">${row[col] || '-'}</td>`).join('')}
        </tr>`;
    });
    
    $('#resultBody').html(rows.join(''));
    $('#resultsWrapper').removeClass('hidden').addClass('fade-in');
    
    // Select all functionality
    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', $(this).prop('checked'));
    });
}

// Column selector + search functionality
$(function () {
    // Load columns
    $.get('/api/pembelian/columns', function (columns) {
        const select = $('#columnSelect');
        select.append(`<option value="">-- All Columns --</option>`);
        columns.forEach(col => {
            select.append(`<option value="${col}">${col}</option>`);
        });
    }).fail(function() {
        console.warn('Could not load columns, using default search');
    });
    
    // Initial load
    $.get('/api/pembelian/search', renderTable).fail(function() {
        console.error('Could not load initial data');
        $('#resultsWrapper').removeClass('hidden');
        $('#resultBody').html('<tr><td colspan="20" class="text-center py-8 text-red-400">❌ Failed to load data</td></tr>');
    });
    
    // Search functionality
    $('#searchBtn').on('click', function () {
        const column = $('#columnSelect').val();
        const value = $('#searchValue').val().trim();
        
        if (!column || !value) {
            $.get('/api/pembelian/search', renderTable);
            return;
        }
        
        $.get(`/api/pembelian/search?column=${encodeURIComponent(column)}&value=${encodeURIComponent(value)}`, renderTable)
        .fail(function() {
            alert('❌ Search failed. Please try again.');
        });
    });
    
    // Enter key search
    $('#searchValue').on('keypress', function(e) {
        if (e.which === 13) {
            $('#searchBtn').click();
        }
    });
});

// Enhanced Modal & Insert Logic
$(function () {
    // Open modal
    $('#openInsertPembelianModal').on('click', function () {
        $('#insertPembelianModal').removeClass('hidden');
        document.body.style.overflow = 'hidden';
        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        $('#purchase_date').val(today);
    });
    
    // Close modal
    $('#closeInsertPembelianModal').on('click', function () {
        closeModal();
    });
    
    // Close modal function
    function closeModal() {
        $('#insertPembelianModal').addClass('hidden');
        document.body.style.overflow = 'auto';
        $('#insertPembelianForm')[0].reset();
        $('#grand_total').val('');
        // Reset submit button
        $('#submitBtn').html('✅ Submit').prop('disabled', false);
    }
    
    // Close modal on backdrop click
    $('#insertPembelianModal').on('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Grand total calculation
    function updateGrandTotal() {
        const qty = parseFloat($('#quantity').val()) || 0;
        const unitPrice = parseFloat($('#unit_price').val()) || 0;
        const tax = parseFloat($('#tax').val()) || 0;
        
        const subtotal = unitPrice * qty;
        const total = subtotal + tax;
        
        $('#grand_total').val(total.toFixed(2));
    }
    
    // Bind calculation events
    $('#quantity, #unit_price, #tax').on('input change', updateGrandTotal);
    
    // Enhanced form submission
    $('#insertPembelianForm').on('submit', function (e) {
        e.preventDefault();
        
        // Validate required fields
        const requiredFields = ['vendor_id', 'project_id', 'requested_by', 'purchase_order_number', 'item_name', 'item_code', 'category', 'quantity', 'unit_price'];
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
        
        // Prepare form data
        const formData = new FormData(this);
        
        // Debug: Log form data
        console.log('Submitting form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        // Submit via AJAX
        $.ajax({
            url: '/api/pembelian/insert',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Success response:', response);
                alert('✅ Pembelian inserted successfully!');
                closeModal();
                // Refresh table
                $.get('/api/pembelian/search', renderTable);
            },
            error: function (xhr, status, error) {
                console.error('Error details:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                let errorMessage = '❌ Insert failed. ';
                
                if (xhr.status === 422) {
                    // Validation errors
                    try {
                        const errors = JSON.parse(xhr.responseText);
                        if (errors.errors) {
                            errorMessage += 'Validation errors: ' + Object.values(errors.errors).flat().join(', ');
                        }
                    } catch (e) {
                        errorMessage += 'Validation failed.';
                    }
                } else if (xhr.status === 500) {
                    errorMessage += 'Server error. Please check your database connection.';
                } else if (xhr.status === 404) {
                    errorMessage += 'API endpoint not found.';
                } else {
                    errorMessage += `Error ${xhr.status}: ${xhr.statusText}`;
                }
                
                alert(errorMessage);
            },
            complete: function() {
                // Reset button state
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
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

// Enhanced delete functionality
$(document).on('click', '#deleteSelectedBtn', function () {
    const selectedIds = $('.row-checkbox:checked').map(function () {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        alert('⚠️ Please select at least one row to delete.');
        return;
    }
    
    if (!confirm(`🗑️ Delete ${selectedIds.length} item(s)? This action cannot be undone.`)) {
        return;
    }
    
    // Show loading state
    const deleteBtn = $(this);
    const originalText = deleteBtn.html();
    deleteBtn.html('⏳ Deleting...').prop('disabled', true);
    
    $.ajax({
        url: '{{ url("/api/pembelian/delete") }}',
        method: 'POST',
        data: {
            ids: selectedIds,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            alert('✅ ' + (res.message || 'Items deleted successfully'));
            $.get('/api/pembelian/search', renderTable);
        },
        error: function (xhr) {
            console.error('Delete error:', xhr);
            alert('❌ Failed to delete. Please try again.');
        },
        complete: function() {
            deleteBtn.html(originalText).prop('disabled', false);
        }
    });
});

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
        $('#closeInsertPembelianModal').click();
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