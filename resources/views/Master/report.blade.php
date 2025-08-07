@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 text-white">
    <h1 class="text-2xl font-bold mb-4">Purchase Report</h1>

    <!-- Filter Section -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <select id="vendorFilter" class="p-2 rounded bg-gray-800 text-white w-full">
            <option value="">All Vendors</option>
            @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
            @endforeach
        </select>

        <select id="projectFilter" class="p-2 rounded bg-gray-800 text-white w-full">
            <option value="">All Projects</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>

        <select id="categoryFilter" class="p-2 rounded bg-gray-800 text-white w-full">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>

        <select id="requestedByFilter" class="p-2 rounded bg-gray-800 text-white w-full">
            <option value="">All Requesters</option>
            @foreach($requests as $requested)
                <option value="{{ $requested->id }}">{{ $requested->name }}</option>
            @endforeach
        </select>

        <select id="monthFilter" class="p-2 rounded bg-gray-800 text-white w-full">
            <option value="">All Months</option>
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
            @endfor
        </select>
    </div>
    <button id="exportCsvBtn" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
    Export CSV
    </button>


    <!-- Table Section -->
    <div class="mt-3 overflow-auto bg-gray-900 rounded shadow">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-gray-700 text-gray-300">
                <tr>
                    <th class="p-2">Order #</th>
                    <th class="p-2">Item</th>
                    <th class="p-2">Category</th>
                    <th class="p-2">Qty</th>
                    <th class="p-2">Unit</th>
                    <th class="p-2">Price</th>
                    <th class="p-2">Total</th>
                    <th class="p-2">Vendor</th>
                    <th class="p-2">Project</th>
                    <th class="p-2">Requested By</th>
                    <th class="p-2">Purchase Date</th>
                </tr>
            </thead>
            <tbody id="reportTable" class="text-white">
                <tr>
                    <td colspan="11" class="p-4 text-center">Loading data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- JS Script -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function fetchReport() {
        const vendor_id = document.getElementById('vendorFilter').value;
        const project_id = document.getElementById('projectFilter').value;
        const category = document.getElementById('categoryFilter').value;
        const requested_by = document.getElementById('requestedByFilter').value;
        const month = document.getElementById('monthFilter').value;

        fetch(`{{ url('/api/report/data') }}?vendor_id=${vendor_id}&project_id=${project_id}&category=${category}&requested_by=${requested_by}&month=${month}`)
            .then(response => response.json())
            .then(data => {
                const table = document.getElementById('reportTable');
                table.innerHTML = '';

                if (data.length === 0) {
                    table.innerHTML = `<tr><td colspan="11" class="p-4 text-center">No data found</td></tr>`;
                    return;
                }

                data.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="p-2">${row.purchase_order_number}</td>
                        <td class="p-2">${row.item_name}</td>
                        <td class="p-2">${row.category}</td>
                        <td class="p-2">${row.quantity}</td>
                        <td class="p-2">${row.unit}</td>
                        <td class="p-2">Rp ${parseFloat(row.unit_price).toLocaleString()}</td>
                        <td class="p-2">Rp ${parseFloat(row.total_price).toLocaleString()}</td>
                        <td class="p-2">${row.vendor_name || '-'}</td>
                        <td class="p-2">${row.project_name || '-'}</td>
                        <td class="p-2">${row.request_name || '-'}</td>
                        <td class="p-2">${new Date(row.purchase_date).toLocaleDateString()}</td>
                    `;
                    table.appendChild(tr);
                });
            })
            .catch(err => {
                console.error(err);
                document.getElementById('reportTable').innerHTML = `<tr><td colspan="11" class="p-4 text-center text-red-400">Failed to load data.</td></tr>`;
            });
    }

    // Trigger fetch on filter change
    document.querySelectorAll('#vendorFilter, #projectFilter, #categoryFilter, #requestedByFilter, #monthFilter').forEach(select => {
        select.addEventListener('change', fetchReport);
    });

    // Initial load
    fetchReport();
});
</script>
<script>
    function downloadCSV(csv, filename) {
        const csvFile = new Blob([csv], { type: "text/csv" });
        const downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }

    function exportTableToCSV(filename) {
        const rows = document.querySelectorAll("#reportTable tr");
        let csv = [];

        rows.forEach(row => {
            const cols = row.querySelectorAll("td, th");
            const rowData = Array.from(cols).map(col => `"${col.innerText.replace(/"/g, '""')}"`);
            csv.push(rowData.join(","));
        });

        downloadCSV(csv.join("\n"), filename);
    }

    document.getElementById("exportCsvBtn").addEventListener("click", () => {
        exportTableToCSV("filtered_report.csv");
    });
</script>
@endpush
@endsection
