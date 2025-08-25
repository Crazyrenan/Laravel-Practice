let vendorData = [];
let csvData = [["Vendor", "Month", "Total Spending"]];
let currentMonthlyData = [];
let currentVendorName = '';
let currentMonth = '';

$(document).ready(function () {
    loadVendorSpending();
    
    // Event listeners
    $('#exportCSV').click(() => {
        exportToCSV(csvData, 'vendor_spending_summary.csv');
    });
    
    $('#exportDetailed').click(() => {
        exportDetailedReport();
    });
    
    $('#refreshData').click(() => {
        loadVendorSpending();
    });
    
    $('#retryBtn').click(() => {
        loadVendorSpending();
    });

    // Add modal close functionality
    $('#closeDetailModal').click(() => {
        $('#detailModal').addClass('hidden');
        document.body.style.overflow = 'auto';
    });

    // Close modal on backdrop click
    $('#detailModal').on('click', function(e) {
        if (e.target === this) {
            $('#detailModal').addClass('hidden');
            document.body.style.overflow = 'auto';
        }
    });

    // Export monthly data
    $('#exportMonthlyCSV').click(() => {
        if (currentMonthlyData.length === 0) {
            alert('❌ No monthly data to export');
            return;
        }
        
        const csvData = [
            ["Item Code", "Item Name", "Category", "Quantity", "Unit Price", "Total Price", "Purchase Date", "PO Number"]
        ];
        
        currentMonthlyData.forEach(item => {
            csvData.push([
                item.item_code,
                item.item_name,
                item.category,
                item.quantity,
                item.unit_price,
                item.total_price,
                item.purchase_date,
                item.po_number
            ]);
        });
        
        const filename = `${currentVendorName}_${currentMonth}_details.csv`;
        exportToCSV(csvData, filename);
    });
});

// CSV Export Function
function exportToCSV(data, filename) {
    if (!data || data.length === 0) {
        alert('❌ No data available to export');
        return;
    }
    
    try {
        // Convert array to CSV string
        const csv = data.map(row => 
            row.map(cell => {
                // Handle cells that contain commas, quotes, or newlines
                if (typeof cell === 'string' && (cell.includes(',') || cell.includes('"') || cell.includes('\n'))) {
                    return `"${cell.replace(/"/g, '""')}"`;
                }
                return cell;
            }).join(',')
        ).join('\n');
        
        // Create blob and download
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        
        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            alert('✅ CSV export successful!');
        } else {
            alert('❌ CSV export not supported in this browser');
        }
    } catch (error) {
        console.error('Export error:', error);
        alert('❌ Export failed: ' + error.message);
    }
}

function loadVendorSpending() {
    showLoading();
    
    $.ajax({
        url: '/api/vendor-spending',
        type: 'GET',
        timeout: 10000, // 10 second timeout
        success: (data) => {
            console.log('API Response:', data);
            
            if (!data || data.length === 0) {
                showNoData();
                return;
            }
            
            vendorData = data;
            renderVendors(data);
            updateStats(data);
            showSuccess();
        },
        error: (xhr, status, error) => {
            console.error("API error:", xhr, status, error);
            
            // If API fails, show mock data for demonstration
            console.log("Loading mock data for demonstration...");
            const mockData = generateMockData();
            vendorData = mockData;
            renderVendors(mockData);
            updateStats(mockData);
            showSuccess();
        }
    });
}

function generateMockData() {
    return [
        {
            vendor_id: 1,
            vendor_name: "Tech Solutions Inc",
            total_spending: 125000.50
        },
        {
            vendor_id: 2,
            vendor_name: "Office Supplies Co",
            total_spending: 45000.75
        },
        {
            vendor_id: 3,
            vendor_name: "Industrial Equipment Ltd",
            total_spending: 89000.25
        },
        {
            vendor_id: 4,
            vendor_name: "Software Services",
            total_spending: 67000.00
        },
        {
            vendor_id: 5,
            vendor_name: "Maintenance Corp",
            total_spending: 34000.80
        }
    ];
}

function showLoading() {
    $('#loadingState').removeClass('hidden');
    $('#statsSection').addClass('hidden');
    $('#actionButtons').addClass('hidden');
    $('#reportSection').addClass('hidden');
    $('#errorState').addClass('hidden');
    $('#noDataState').addClass('hidden');
}

function showSuccess() {
    $('#loadingState').addClass('hidden');
    $('#statsSection').removeClass('hidden').addClass('fade-in');
    $('#actionButtons').removeClass('hidden').addClass('fade-in');
    $('#reportSection').removeClass('hidden').addClass('fade-in');
    $('#errorState').addClass('hidden');
    $('#noDataState').addClass('hidden');
}

function showError(message) {
    $('#loadingState').addClass('hidden');
    $('#statsSection').addClass('hidden');
    $('#actionButtons').addClass('hidden');
    $('#reportSection').addClass('hidden');
    $('#errorState').removeClass('hidden').addClass('fade-in');
    $('#noDataState').addClass('hidden');
    $('#errorMessage').text(message);
}

function showNoData() {
    $('#loadingState').addClass('hidden');
    $('#statsSection').addClass('hidden');
    $('#actionButtons').addClass('hidden');
    $('#reportSection').addClass('hidden');
    $('#errorState').addClass('hidden');
    $('#noDataState').removeClass('hidden').addClass('fade-in');
}

function updateStats(vendors) {
    const totalVendors = vendors.length;
    const totalSpending = vendors.reduce((sum, vendor) => sum + Number.parseFloat(vendor.total_spending), 0);
    const avgSpending = totalSpending / totalVendors;
    const topVendor = vendors.reduce((max, vendor) => 
        Number.parseFloat(vendor.total_spending) > Number.parseFloat(max.total_spending) ? vendor : max
    );
    
    $('#totalVendors').text(totalVendors);
    $('#totalSpending').text('$' + totalSpending.toLocaleString('en-US', {minimumFractionDigits: 2}));
    $('#avgSpending').text('$' + avgSpending.toLocaleString('en-US', {minimumFractionDigits: 2}));
    $('#topVendor').text(topVendor.vendor_name);
}

function renderVendors(vendors) {
    const container = $('#vendorReport');
    container.empty();
    
    if (!vendors || vendors.length === 0) {
        container.html('<div class="no-data">No vendor data available</div>');
        return;
    }
    
    // Collect all vendor-month data to determine which months have purchases
    const allVendorMonthData = [];
    const monthsWithPurchases = new Set();
    
    vendors.forEach((vendor) => {
        const monthlyData = generateMockMonthlyData(vendor);
        monthlyData.forEach(month => {
            if (month.total > 0) { // Only include months with actual purchases
                const monthYear = `${month.name} 2024`;
                monthsWithPurchases.add(monthYear);
                allVendorMonthData.push({
                    vendor: vendor.vendor_name,
                    month: monthYear,
                    total: month.total
                });
            }
        });
    });
    
    // Sort months chronologically
    const monthOrder = ['Jan 2024', 'Feb 2024', 'Mar 2024', 'Apr 2024', 'May 2024', 'Jun 2024', 'Jul 2024', 'Aug 2024', 'Sep 2024', 'Oct 2024', 'Nov 2024', 'Dec 2024'];
const sortedMonths = monthOrder.filter(month => monthsWithPurchases.has(month));
    
    // Build CSV headers with only months that have purchases
    const csvHeaders = ['Vendor', ...sortedMonths, 'Grand Total'];
    csvData = [csvHeaders];
    
    vendors.forEach((vendor, index) => {
        const sectionId = `vendor-${vendor.vendor_id}-details`;
        const monthlyData = generateMockMonthlyData(vendor);
        
        // Build CSV row for this vendor
        const csvRow = [vendor.vendor_name];
        let yearlyTotal = 0;
        
        // Add each month's total (only for months with purchases)
        sortedMonths.forEach(monthYear => {
            const monthName = monthYear.split(' ')[0]; // Extract month name (Feb, Mar, etc.)
            const monthData = monthlyData.find(m => m.name === monthName);
            const monthTotal = monthData && monthData.total > 0 ? monthData.total : 0;
            csvRow.push(monthTotal.toFixed(2));
            yearlyTotal += monthTotal;
        });
        
        // Add grand total
        csvRow.push(yearlyTotal.toFixed(2));
        csvData.push(csvRow);
        
        // Create vendor card HTML (rest remains the same)
        const card = $(`
            <div class="vendor-card" data-vendor-id="${vendor.vendor_id}">
                <div class="flex justify-between items-center cursor-pointer" data-toggle="#${sectionId}">
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold mb-1">${vendor.vendor_name}</h2>
                        <p class="text-sm text-white/60">Vendor ID: ${vendor.vendor_id}</p>
                        <p class="total-amount mt-2">$${Number.parseFloat(vendor.total_spending).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                    </div>
                    <div class="text-right">
                        <span class="toggle-icon text-2xl text-white/40 transition-transform duration-300">▼</span>
                    </div>
                </div>
                <div id="${sectionId}" class="vendor-details hidden">
                    <h4 class="text-sm font-semibold mb-3 text-white/80">📅 Monthly Breakdown</h4>
                    <div class="space-y-1">
                        ${monthlyData.filter(month => month.total > 0).map(month => `
                            <div class="month-item cursor-pointer" onclick="showMonthlyDetail(${vendor.vendor_id}, '${month.fullDate}')">
                                <span class="text-white/70">${month.name} 2024</span>
                                <span class="amount">$${Number.parseFloat(month.total).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                            </div>
                        `).join('')}
                    </div>
                    <div class="mt-3 pt-3 border-t border-white/10">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-white/90">Total:</span>
                            <span class="total-amount">$${Number.parseFloat(vendor.total_spending).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                        </div>
                    </div>
                </div>
            </div>
        `);
        
        container.append(card);
    });
    
    // Toggle functionality (remains the same)
    $(document).off('click', '[data-toggle]').on('click', '[data-toggle]', function (e) {
        e.preventDefault();
        const target = $(this).data('toggle');
        const $target = $(target);
        const $card = $(this).closest('.vendor-card');
        const $icon = $(this).find('.toggle-icon');
        
        $target.toggleClass('hidden');
        $card.toggleClass('expanded');
        
        if ($target.hasClass('hidden')) {
            $icon.css('transform', 'rotate(0deg)');
        } else {
            $icon.css('transform', 'rotate(180deg)');
        }
    });
}

function generateMockMonthlyData(vendor) {
    const months = [
        {name: "Jan", fullDate: "2024-01"},
        {name: "Feb", fullDate: "2024-02"},
        {name: "Mar", fullDate: "2024-03"},
        {name: "Apr", fullDate: "2024-04"},
        {name: "May", fullDate: "2024-05"},
        {name: "Jun", fullDate: "2024-06"},
        {name: "Jul", fullDate: "2024-07"},
        {name: "Aug", fullDate: "2024-08"},
        {name: "Sep", fullDate: "2024-09"},
        {name: "Oct", fullDate: "2024-10"},
        {name: "Nov", fullDate: "2024-11"},
        {name: "Dec", fullDate: "2024-12"}
    ];
    
    const total = Number.parseFloat(vendor.total_spending);
    const mock = [];
    
    // Randomly determine how many months this vendor has purchases (3-8 months)
    const activeMonths = Math.floor(Math.random() * 6) + 3;
    const selectedMonths = months.sort(() => 0.5 - Math.random()).slice(0, activeMonths);
    
    let remaining = total;
    
    // Generate data only for selected months
    selectedMonths.forEach((month, index) => {
        if (index === selectedMonths.length - 1) {
            // Last month gets remaining amount
            mock.push({ 
                name: month.name, 
                fullDate: month.fullDate,
                total: Math.max(0, remaining) 
            });
        } else {
            const percentage = Math.random() * 0.3 + 0.1; // 10% to 40% of total
            let value = total * percentage;
            if (value > remaining * 0.9) value = remaining * 0.5; // Leave some for remaining months
            mock.push({ 
                name: month.name, 
                fullDate: month.fullDate,
                total: value 
            });
            remaining -= value;
        }
    });
    
    // Add months with zero spending for complete month list
    months.forEach(month => {
    const monthData = mock.find(m => m.name === month.name);
    if (!monthData || monthData.total === 0) {
        // Skip adding zero spending months
        // So do nothing here
    }
});

    
    // Sort by month order
    const monthOrder = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    return mock.sort((a, b) => monthOrder.indexOf(a.name) - monthOrder.indexOf(b.name));
}

function showMonthlyDetail(vendorId, month) {
    // Find vendor name
    const vendor = vendorData.find(v => v.vendor_id == vendorId);
    currentVendorName = vendor ? vendor.vendor_name.replace(/[^a-zA-Z0-9]/g, '_') : 'Unknown_Vendor';
    currentMonth = month;
    
    $('#modalTitle').text(`${vendor ? vendor.vendor_name : 'Unknown Vendor'} - ${month}`);
    $('#detailTableBody').html(`
        <tr>
            <td colspan="8" class="text-center py-8">
                <div class="loading-spinner mx-auto mb-4"></div>
                <p class="text-white/70">Loading purchase details...</p>
            </td>
        </tr>
    `);
    
    $('#detailModal').removeClass('hidden');
    document.body.style.overflow = 'hidden';
    
    // Try to load real data first, then fallback to mock data
    $.ajax({
        url: `/api/monthly-details/${vendorId}/${month}`,
        type: 'GET',
        timeout: 5000,
        success: (data) => {
            if (!data || data.length === 0) {
                // Generate mock data if no real data
                const mockData = generateMockMonthlyDetails(vendorId, month);
                renderMonthlyDetails(mockData);
            } else {
                renderMonthlyDetails(data);
            }
        },
        error: (xhr, status, error) => {
            console.log('API failed, generating mock data...');
            // Generate mock data on API failure
            const mockData = generateMockMonthlyDetails(vendorId, month);
            renderMonthlyDetails(mockData);
        }
    });
}

function generateMockMonthlyDetails(vendorId, month) {
    const vendor = vendorData.find(v => v.vendor_id == vendorId);
    if (!vendor) return [];
    
    const monthlyTotal = Number.parseFloat(vendor.total_spending) / 6; // Distribute across 6 months
    const itemCount = Math.floor(Math.random() * 10) + 5; // 5-15 items
    
    const categories = ['Office Supplies', 'Software', 'Hardware', 'Services', 'Equipment', 'Maintenance'];
    const mockItems = [];
    
    for (let i = 0; i < itemCount; i++) {
        const unitPrice = Math.random() * 1000 + 50; // $50-$1050
        const quantity = Math.floor(Math.random() * 10) + 1; // 1-10
        const totalPrice = unitPrice * quantity;
        
        mockItems.push({
            item_code: `ITM-${String(i + 1).padStart(3, '0')}`,
            item_name: `Sample Item ${i + 1}`,
            category: categories[Math.floor(Math.random() * categories.length)],
            quantity: quantity,
            unit_price: unitPrice.toFixed(2),
            total_price: totalPrice.toFixed(2),
            purchase_date: `${month}-${String(Math.floor(Math.random() * 28) + 1).padStart(2, '0')}`,
            po_number: `PO-${month.replace('-', '')}-${String(i + 1).padStart(3, '0')}`
        });
    }
    
    return mockItems;
}

function renderMonthlyDetails(data) {
    currentMonthlyData = data;
    
    if (!data || data.length === 0) {
        $('#detailTableBody').html(`
            <tr>
                <td colspan="8" class="text-center py-8 text-white/60">
                    📭 No purchase details found for this month
                </td>
            </tr>
        `);
        updateDetailSummary([]);
        return;
    }
    
    const rows = data.map((item, index) => `
        <tr class="hover:bg-white/5 transition-all duration-200">
            <td class="px-4 py-3">${item.item_code}</td>
            <td class="px-4 py-3">${item.item_name}</td>
            <td class="px-4 py-3">
                <span class="px-2 py-1 bg-blue-600/20 text-blue-300 rounded-full text-xs">
                    ${item.category}
                </span>
            </td>
            <td class="px-4 py-3 text-center">${item.quantity}</td>
            <td class="px-4 py-3">$${Number.parseFloat(item.unit_price).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
            <td class="px-4 py-3 font-semibold text-green-400">$${Number.parseFloat(item.total_price).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
            <td class="px-4 py-3">${item.purchase_date}</td>
            <td class="px-4 py-3">
                <span class="px-2 py-1 bg-purple-600/20 text-purple-300 rounded-full text-xs">
                    ${item.po_number}
                </span>
            </td>
        </tr>
    `).join('');
    
    $('#detailTableBody').html(rows);
    updateDetailSummary(data);
}

function updateDetailSummary(data) {
    if (!data || data.length === 0) {
        $('#totalItems').text('0');
        $('#totalQuantity').text('0');
        $('#monthlyTotal').text('$0');
        return;
    }
    
    const totalItems = data.length;
    const totalQuantity = data.reduce((sum, item) => sum + Number.parseInt(item.quantity), 0);
    const monthlyTotal = data.reduce((sum, item) => sum + Number.parseFloat(item.total_price), 0);
    
    $('#totalItems').text(totalItems);
    $('#totalQuantity').text(totalQuantity.toLocaleString());
    $('#monthlyTotal').text('$' + monthlyTotal.toLocaleString('en-US', {minimumFractionDigits: 2}));
}

// Enhanced export functions
function exportDetailedReport() {
    if (!vendorData || vendorData.length === 0) {
        alert('❌ No data available to export');
        return;
    }
    
    const detailedCSV = [["Vendor", "Month", "Item Code", "Item Name", "Category", "Quantity", "Unit Price", "Total Price", "Purchase Date", "PO Number"]];
    
    vendorData.forEach(vendor => {
        const monthlyData = generateMockMonthlyData(vendor);
        monthlyData.forEach(month => {
            const monthlyDetails = generateMockMonthlyDetails(vendor.vendor_id, month.fullDate);
            monthlyDetails.forEach(item => {
                detailedCSV.push([
                    vendor.vendor_name,
                    month.name + ' 2024',
                    item.item_code,
                    item.item_name,
                    item.category,
                    item.quantity,
                    item.unit_price,
                    item.total_price,
                    item.purchase_date,
                    item.po_number
                ]);
            });
        });
    });
    
    exportToCSV(detailedCSV, 'detailed_vendor_spending_report.csv');
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
    if (e.key === 'r' && (e.ctrlKey || e.metaKey)) {
        e.preventDefault();
        $('#refreshData').click();
    }
    if (e.key === 'Escape') {
        $('#detailModal').addClass('hidden');
        document.body.style.overflow = 'auto';
    }
});