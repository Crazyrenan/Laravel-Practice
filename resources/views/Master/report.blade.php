@extends('layouts.appnew')

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
                <h1 class="text-3xl font-bold text-white mb-2">Purchase Order Report</h1>
                <p class="text-gray-300">MultiSelective Reporting</p>
            </div>
            <div class="flex space-x-3">
                <button id="refresh-btn" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
                <button id="exportCsvBtn" class="px-4 py-2 bg-green-600 hover:bg-white/20 border border-white/20 rounded-lg text-sm font-medium transition duration-300">
                ⬇ Export to CSV
                </button>
            </div>
        </div>
    </div>


        <!-- Filter Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 bg-white/5 p-4 rounded-lg border border-white/10 backdrop-blur-md shadow-md" style="animation-delay: 0ms;">
            <select id="vendorFilter" class="p-2 bg-gray-900 border border-white/10 rounded text-white">
                <option value="">All Vendors</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                @endforeach
            </select>

            <select id="projectFilter" class="p-2 bg-gray-900 border border-white/10 rounded text-white">
                <option value="">All Projects</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>

            <select id="categoryFilter" class="p-2 bg-gray-900 border border-white/10 rounded text-white">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>

            <select id="requestedByFilter" class="p-2 bg-gray-900 border border-white/10 rounded text-white">
                <option value="">All Requesters</option>
                @foreach($requests as $requested)
                    <option value="{{ $requested->id }}">{{ $requested->name }}</option>
                @endforeach
            </select>

            <select id="monthFilter" class="p-2 bg-gray-900 border border-white/10 rounded text-white">
                <option value="">All Months</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                @endfor
            </select>
        </div>

        <div class="bg-white/10 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl overflow-hidden p-6" data-aos="fade-up" data-aos-delay="100">
            <table class="min-w-full table-auto text-sm text-left text-white" id="reportTable">
                <thead class="uppercase text-gray-400 border-b border-white/20">
                    <tr>
                        <th class="px-4 py-3">Order #</th>
                        <th class="px-4 py-3">Item</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Unit</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Vendor</th>
                        <th class="px-4 py-3">Project</th>
                        <th class="px-4 py-3">Requested By</th>
                        <th class="px-4 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-white">
                    <tr><td colspan="11" class="p-4 text-center">Loading data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#reportTable tbody');

    function fetchReport() {
        const vendor_id = document.getElementById('vendorFilter').value;
        const project_id = document.getElementById('projectFilter').value;
        const category = document.getElementById('categoryFilter').value;
        const requested_by = document.getElementById('requestedByFilter').value;
        const month = document.getElementById('monthFilter').value;

        tableBody.innerHTML = `<tr><td colspan="11" class="p-4 text-center text-gray-300">Loading data...</td></tr>`;

        fetch(`{{ url('/api/report/data') }}?vendor_id=${vendor_id}&project_id=${project_id}&category=${category}&requested_by=${requested_by}&month=${month}`)
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="11" class="p-4 text-center text-gray-400">No data found</td></tr>`;
                    return;
                }

                data.forEach((row, i) => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-white/5 transition duration-300';

                    tr.innerHTML = `
                        <td class="px-4 py-2">${row.purchase_order_number}</td>
                        <td class="px-4 py-2">${row.item_name}</td>
                        <td class="px-4 py-2">${row.category}</td>
                        <td class="px-4 py-2">${row.quantity}</td>
                        <td class="px-4 py-2">${row.unit}</td>
                        <td class="px-4 py-2">Rp ${parseFloat(row.unit_price).toLocaleString()}</td>
                        <td class="px-4 py-2">Rp ${parseFloat(row.total_price).toLocaleString()}</td>
                        <td class="px-4 py-2">${row.vendor_name || '-'}</td>
                        <td class="px-4 py-2">${row.project_name || '-'}</td>
                        <td class="px-4 py-2">${row.request_name || '-'}</td>
                        <td class="px-4 py-2">${new Date(row.purchase_date).toLocaleDateString()}</td>
                    `;

                    tableBody.appendChild(tr);
                });
            })
            .catch(err => {
                console.error(err);
                tableBody.innerHTML = `<tr><td colspan="11" class="p-4 text-center text-red-400">Failed to load data.</td></tr>`;
            });
    }

    // Event listeners
    document.querySelectorAll('#vendorFilter, #projectFilter, #categoryFilter, #requestedByFilter, #monthFilter')
        .forEach(el => el.addEventListener('change', fetchReport));

    fetchReport();

    // CSV Export
    document.getElementById("exportCsvBtn").addEventListener("click", function () {
        const rows = [["Order #", "Item", "Category", "Qty", "Unit", "Price", "Total", "Vendor", "Project", "Requested By", "Date"]];
        document.querySelectorAll("#reportTable tbody tr").forEach(row => {
            const cols = row.querySelectorAll("td");
            const rowData = Array.from(cols).map(col => `"${col.innerText.replace(/"/g, '""')}"`);
            rows.push(rowData);
        });

        const csv = rows.map(r => r.join(',')).join('\n');
        const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('href', url);
        a.setAttribute('download', 'purchase_report.csv');
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
});
</script>
@endpush
