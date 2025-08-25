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
<script src="{{ asset('js/report/spendingreport.js') }}"></script>

@endpush
