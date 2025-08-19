@extends('layouts.appnew')

@section('title', 'Purchase Order Status Report')

@section('content')

<style>
@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(10px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation-name: fadeInUp;
  animation-duration: 0.5s;
  animation-fill-mode: forwards;
  animation-timing-function: ease;
  opacity: 0; /* start hidden */
}
</style>

<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="bg-gray-800 rounded-lg shadow-sm p-6 mb-6 fade-in-up" style="animation-delay: 0ms;">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Purchase Order Status Report</h1>
                <p class="text-gray-300">Track and monitor your purchase orders with detailed status information</p>
            </div>
            <div class="flex space-x-3">
                <button id="refresh-btn" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
                <button id="export-csv-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export CSV
                </button>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-gray-800 rounded-lg shadow-sm p-6 mb-6 fade-in-up" style="animation-delay: 100ms;">
        <h2 class="text-lg font-semibold text-white mb-4">Filters</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Month Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Month</label>
                <input type="month" id="filter-month" class="w-full border border-gray-600 rounded-lg px-3 py-2 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Project Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Project</label>
                <select id="filter-project" class="w-full border border-gray-600 rounded-lg px-3 py-2 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Projects</option>
                    @isset($projects)
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                <select id="filter-status" class="w-full border border-gray-600 rounded-lg px-3 py-2 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            {{-- Apply Button --}}
            <div class="flex items-end">
                <button id="filter-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filters
                </button>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-800 rounded-lg shadow-sm p-6 fade-in-up" style="animation-delay: 200ms;">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-900 text-blue-400">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-300">Total Orders</p>
                    <p class="text-2xl font-bold text-white" id="total-orders">0</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800 rounded-lg shadow-sm p-6 fade-in-up" style="animation-delay: 300ms;">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-900 text-yellow-400">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-300">Pending</p>
                    <p class="text-2xl font-bold text-white" id="pending-orders">0</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800 rounded-lg shadow-sm p-6 fade-in-up" style="animation-delay: 400ms;">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-900 text-green-400">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-300">Delivered</p>
                    <p class="text-2xl font-bold text-white" id="delivered-orders">0</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800 rounded-lg shadow-sm p-6 fade-in-up" style="animation-delay: 500ms;">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-900 text-red-400">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-300">Cancelled</p>
                    <p class="text-2xl font-bold text-white" id="cancelled-orders">0</p>
                </div>
            </div>
        </div>
    </div>
    {{-- Table --}}
     <div class="bg-gray-800 rounded-lg shadow-sm overflow-hidden relative fade-in-up" style="animation-delay: 600ms;">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-lg font-semibold text-white">Purchase Orders</h2>
        </div>
        
        {{-- Loading Overlay --}}
        <div id="loading-overlay" class="hidden absolute inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-10">
            <div class="text-center">
                <div class="spinner border-blue-400 mb-2"></div>
                <p class="text-gray-300">Loading data...</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">PO Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Buy Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Total Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Grand Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Purchase Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Expected Delivery</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="report-table" class="bg-gray-800 divide-y divide-gray-700">
                    <tr>
                        <td colspan="12" class="px-6 py-8 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-4 text-gray-600"></i>
                            <p>No data available</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Error Message --}}
    <div id="error-message" class="hidden mt-4 bg-red-900 border border-red-700 rounded-lg p-4">
        <div class="flex">
            <i class="fas fa-exclamation-triangle text-red-400 mr-3 mt-1"></i>
            <div>
                <h3 class="text-sm font-medium text-red-200">Error Loading Data</h3>
                <p class="text-sm text-red-300 mt-1" id="error-text"></p>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function () {
            let currentData = [];

            function showLoading() {
                $('#loading-overlay').removeClass('hidden');
                $('.bg-white').addClass('loading');
            }

            function hideLoading() {
                $('#loading-overlay').addClass('hidden');
                $('.bg-white').removeClass('loading');
            }

            function showError(message) {
                $('#error-message').removeClass('hidden');
                $('#error-text').text(message);
            }

            function hideError() {
                $('#error-message').addClass('hidden');
            }

            function updateSummaryCards(data) {
                const total = data.length;
                const pending = data.filter(item => item.status === 'Pending').length;
                const delivered = data.filter(item => item.status === 'Delivered').length;
                const cancelled = data.filter(item => item.status === 'Cancelled').length;

                $('#total-orders').text(total);
                $('#pending-orders').text(pending);
                $('#delivered-orders').text(delivered);
                $('#cancelled-orders').text(cancelled);
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(amount);
            }

            function formatDate(dateString) {
                if (!dateString) return '-';
                return new Date(dateString).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            function getStatusBadge(status) {
                const statusClass = `status-${status.toLowerCase()}`;
                return `<span class="${statusClass}">${status}</span>`;
            }

            function loadReport() {
                showLoading();
                hideError();

                let month = $('#filter-month').val();
                let project = $('#filter-project').val();
                let status = $('#filter-status').val();

                $.ajax({
                    url: "{{ url('/api/expected-delivery-date') }}",
                    method: "GET",
                    data: {
                        month: month,
                        project: project,
                        status: status
                    },
                    success: function (data) {
                        currentData = data;
                        updateSummaryCards(data);
                        
                        let rows = '';
                        if (data.length > 0) {
                            data.forEach(function (row, index) {
                                const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                                rows += `
                                    <tr class="${rowClass} hover:bg-blue-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${row.purchase_order_number || '-'}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.name || '-'}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.item_name || '-'}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.quantity || '-'}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.unit || '-'}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatCurrency(row.buy_price || 0)}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatCurrency(row.total_price || 0)}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${formatCurrency(row.grand_total || 0)}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatDate(row.purchase_date)}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatDate(row.expected_delivery_date)}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${getStatusBadge(row.status || 'Unknown')}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            rows = `
                                <tr>
                                    <td colspan="12" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-search text-4xl mb-4 text-gray-300"></i>
                                        <p>No purchase orders found matching your criteria</p>
                                    </td>
                                </tr>
                            `;
                        }
                        $('#report-table').html(rows);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading report:', error);
                        showError('Failed to load purchase order data. Please try again.');
                        $('#report-table').html(`
                            <tr>
                                <td colspan="12" class="px-6 py-8 text-center text-red-500">
                                    <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                                    <p>Error loading data</p>
                                </td>
                            </tr>
                        `);
                    },
                    complete: function() {
                        hideLoading();
                    }
                });
            }

            function exportToCSV() {
                if (currentData.length === 0) {
                    alert('No data to export');
                    return;
                }

                const headers = [
                    'PO Number', 'Project', 'Item', 'Quantity', 'Unit', 
                    'Buy Price', 'Total Price', 'Tax', 'Grand Total', 
                    'Purchase Date', 'Expected Delivery', 'Status'
                ];

                let csvContent = headers.join(',') + '\n';

                currentData.forEach(row => {
                    const csvRow = [
                        `"${row.purchase_order_number || ''}"`,
                        `"${row.name || ''}"`,
                        `"${row.item_name || ''}"`,
                        row.quantity || 0,
                        `"${row.unit || ''}"`,
                        row.buy_price || 0,
                        row.total_price || 0,
                        row.tax || 0,
                        row.grand_total || 0,
                        `"${row.purchase_date || ''}"`,
                        `"${row.expected_delivery_date || ''}"`,
                        `"${row.status || ''}"`
                    ];
                    csvContent += csvRow.join(',') + '\n';
                });

                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', `purchase_order_report_${new Date().toISOString().split('T')[0]}.csv`);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            // Event listeners
            $('#filter-btn').click(loadReport);
            $('#refresh-btn').click(loadReport);
            $('#export-csv-btn').click(exportToCSV);

            // Load data on page load
            loadReport();

            // Auto-refresh every 5 minutes
            setInterval(loadReport, 300000);
        });
    </script>

</body>
</html>