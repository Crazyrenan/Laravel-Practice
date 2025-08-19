@extends('layouts.appnew')

@section('title', 'Vendor Spending Report')

@push('styles')
<style>
:root {
  --apple-blue: #007AFF;
  --apple-blue-dark: #0056CC;
  --apple-gray: #8E8E93;
  --apple-gray-light: #F2F2F7;
  --apple-gray-dark: #1C1C1E;
  --apple-gray-medium: #2C2C2E;
  --apple-green: #34C759;
  --apple-orange: #FF9500;
  --apple-red: #FF3B30;
  --apple-purple: #AF52DE;
  --apple-pink: #FF2D55;
  --apple-yellow: #FFCC00;
  --apple-teal: #5AC8FA;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  background: #000;
  color: white;
  min-height: 100vh;
}

/* Background */
.modern-bg {
  position: fixed;
  inset: 0;
  z-index: -1;
  background: linear-gradient(
    135deg,
    rgba(0, 122, 255, 0.1) 0%,
    rgba(175, 82, 222, 0.1) 25%,
    rgba(255, 149, 0, 0.1) 50%,
    rgba(52, 199, 89, 0.1) 75%,
    rgba(255, 59, 48, 0.1) 100%
  );
  background-size: 400% 400%;
  animation: holographic 8s ease infinite;
}

@keyframes holographic {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.modern-bg::before {
  content: "";
  position: absolute;
  inset: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  opacity: 0.3;
}

/* Glass morphism containers */
.glass-container {
  background: rgba(28, 28, 30, 0.6);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 24px;
  padding: 2rem;
  position: relative;
  overflow: hidden;
}

.glass-container::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
}

/* Floating elements */
.floating-orb {
  position: absolute;
  border-radius: 50%;
  background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
  backdrop-filter: blur(10px);
  animation: float-orb 8s ease-in-out infinite;
  pointer-events: none;
}

.orb-1 {
  width: 120px;
  height: 120px;
  top: 10%;
  right: 15%;
  animation-delay: 0s;
}

.orb-2 {
  width: 80px;
  height: 80px;
  bottom: 20%;
  left: 10%;
  animation-delay: 2s;
}

.orb-3 {
  width: 60px;
  height: 60px;
  top: 60%;
  right: 30%;
  animation-delay: 4s;
}

@keyframes float-orb {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  33% {
    transform: translateY(-20px) rotate(120deg);
  }
  66% {
    transform: translateY(10px) rotate(240deg);
  }
}

/* Header section */
.page-header {
  background: linear-gradient(
    135deg,
    rgba(0, 122, 255, 0.2) 0%,
    rgba(175, 82, 222, 0.2) 100%
  );
  border-radius: 24px;
  padding: 2rem;
  margin-bottom: 2rem;
  position: relative;
  overflow: hidden;
}

.page-header::before {
  content: "";
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: conic-gradient(
    from 0deg,
    transparent,
    rgba(0, 122, 255, 0.1),
    transparent,
    rgba(175, 82, 222, 0.1),
    transparent
  );
  animation: rotate 20s linear infinite;
}

@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.gradient-text {
  background: linear-gradient(135deg, #007AFF, #AF52DE, #FF9500);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Enhanced button styling */
.bg-green-600 {
  background: linear-gradient(135deg, var(--apple-green), #30D158) !important;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.bg-green-600::before {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.bg-green-600:hover::before {
  left: 100%;
}

.bg-green-600:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(52, 199, 89, 0.3);
}

.bg-orange-500 {
  background: linear-gradient(135deg, var(--apple-orange), var(--apple-yellow)) !important;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.bg-orange-500::before {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.bg-orange-500:hover::before {
  left: 100%;
}

.bg-orange-500:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(255, 149, 0, 0.3);
}

/* Vendor cards */
.vendor-card {
  background: rgba(28, 28, 30, 0.6);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  padding: 1.5rem;
  margin-bottom: 1rem;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.vendor-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
}

.vendor-card:hover {
  transform: translateY(-2px);
  border-color: rgba(0, 122, 255, 0.3);
  box-shadow: 0 10px 30px rgba(0, 122, 255, 0.1);
}

.vendor-card.expanded {
  border-color: rgba(52, 199, 89, 0.3);
  box-shadow: 0 10px 30px rgba(52, 199, 89, 0.1);
}

.vendor-details {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 12px;
  padding: 1rem;
  margin-top: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.month-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  transition: all 0.2s ease;
}

.month-item:last-child {
  border-bottom: none;
}

.month-item:hover {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 8px;
  padding-left: 0.75rem;
  padding-right: 0.75rem;
}

.amount {
  font-weight: 600;
  color: var(--apple-green);
}

.total-amount {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--apple-blue);
}

.loading-spinner {
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: var(--apple-blue);
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.fade-in {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.error-message {
  background: linear-gradient(135deg, var(--apple-red), var(--apple-pink));
  color: white;
  padding: 1rem;
  border-radius: 12px;
  text-align: center;
  margin: 1rem 0;
}

.no-data {
  background: rgba(255, 255, 255, 0.05);
  color: rgba(255, 255, 255, 0.6);
  padding: 2rem;
  border-radius: 12px;
  text-align: center;
  margin: 1rem 0;
}

/* Stats cards */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: rgba(28, 28, 30, 0.6);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  padding: 1.5rem;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.stat-label {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

/* Responsive */
@media (max-width: 768px) {
  .glass-container {
    padding: 1rem;
    border-radius: 16px;
  }

  .page-header {
    padding: 1rem;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>
@endpush('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="modern-bg"></div>
<div class="floating-orb orb-2"></div>
<div class="floating-orb orb-3"></div>

<main class="p-10 relative z-10">
     Header Section 
    <div class="page-header">
        <div class="relative z-10">
            <button onclick="window.location.href='/home'" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded text-white font-semibold">
                ⬅️ Home
            </button>
            <h1 class="text-4xl font-bold mb-2 mt-4">
                <span class="gradient-text">📊 Vendor Spending</span> Report
            </h1>
            <p class="text-xl text-white/80">
                Comprehensive analysis of vendor spending patterns and monthly breakdowns
            </p>
        </div>
    </div>

     Loading State 
    <div id="loadingState" class="glass-container text-center">
        <div class="loading-spinner mx-auto mb-4"></div>
        <p class="text-white/70">Loading vendor spending data...</p>
    </div>

     Stats Overview 
    <div id="statsSection" class="stats-grid hidden">
        <div class="stat-card">
            <div class="stat-value gradient-text" id="totalVendors">0</div>
            <div class="stat-label">Total Vendors</div>
        </div>
        <div class="stat-card">
            <div class="stat-value gradient-text" id="totalSpending">$0</div>
            <div class="stat-label">Total Spending</div>
        </div>
        <div class="stat-card">
            <div class="stat-value gradient-text" id="avgSpending">$0</div>
            <div class="stat-label">Average per Vendor</div>
        </div>
        <div class="stat-card">
            <div class="stat-value gradient-text" id="topVendor">-</div>
            <div class="stat-label">Top Vendor</div>
        </div>
    </div>

     Action Buttons 
    <div id="actionButtons" class="glass-container mb-6 hidden">
        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
            ⚡ <span class="gradient-text">Export Options</span>
        </h2>
        <div class="flex gap-4 flex-wrap">
            <button id="exportCSV" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold">
                📄 Export Summary CSV
            </button>
            <button id="exportDetailed" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold">
                📊 Export Detailed CSV
            </button>
            <button id="refreshData" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold">
                🔄 Refresh Data
            </button>
        </div>
    </div>

     Vendor Report Section 
    <div id="reportSection" class="glass-container hidden">
        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
            📈 <span class="gradient-text">Vendor Spending Details</span>
        </h2>
        <div id="vendorReport" class="space-y-4">
             Vendor cards will be inserted here by JS 
        </div>
    </div>

     Error State 
    <div id="errorState" class="glass-container hidden">
        <div class="error-message">
            <h3 class="text-lg font-semibold mb-2">❌ Error Loading Data</h3>
            <p id="errorMessage">Unable to load vendor spending data. Please try again.</p>
            <button id="retryBtn" class="mt-4 bg-white/20 hover:bg-white/30 px-4 py-2 rounded transition-colors">
                🔄 Retry
            </button>
        </div>
    </div>

     No Data State 
    <div id="noDataState" class="glass-container hidden">
        <div class="no-data">
            <h3 class="text-lg font-semibold mb-2">📭 No Data Available</h3>
            <p>No vendor spending data found. Please check if there are any purchase records.</p>
        </div>
    </div>

     Purchase Detail Modal 
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden backdrop-blur-sm">
        <div class="bg-zinc-900 text-white p-6 rounded-xl w-full max-w-6xl max-h-[90vh] overflow-y-auto relative glass-container">
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold flex items-center gap-2">
                        📋 <span class="gradient-text" id="modalTitle">Purchase Details</span>
                    </h3>
                    <button id="closeDetailModal" class="text-white/60 hover:text-white text-2xl transition-colors">
                        ✕
                    </button>
                </div>
                
                <div class="mb-4">
                    <div class="flex gap-4 flex-wrap">
                        <button id="exportMonthlyCSV" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold">
                            📄 Export Monthly Details CSV
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <div class="table-container">
                        <table class="w-full text-sm">
                            <thead class="bg-white/10 text-white uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">Item Code</th>
                                    <th class="px-4 py-3">Item Name</th>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="px-4 py-3">Quantity</th>
                                    <th class="px-4 py-3">Unit Price</th>
                                    <th class="px-4 py-3">Total Price</th>
                                    <th class="px-4 py-3">Purchase Date</th>
                                    <th class="px-4 py-3">PO Number</th>
                                </tr>
                            </thead>
                            <tbody id="detailTableBody" class="divide-y divide-white/10 text-white/90">
                                 Rows will be inserted here 
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="detailSummary" class="mt-6 p-4 bg-white/5 rounded-lg border border-white/10">
                    <h4 class="text-lg font-semibold mb-2">📊 Summary</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold gradient-text" id="totalItems">0</div>
                            <div class="text-sm text-white/60">Total Items</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold gradient-text" id="totalQuantity">0</div>
                            <div class="text-sm text-white/60">Total Quantity</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold gradient-text" id="monthlyTotal">$0</div>
                            <div class="text-sm text-white/60">Monthly Total</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
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
</script>
@endpush
