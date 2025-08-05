@extends('layouts.app')
@section('title', 'Report Master')
@section('content')

<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Master Report</h2>

    {{-- SheetJS Script --}}
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

    {{-- Export Buttons --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <button onclick="exportTableToExcel('vendorReportTable', 'report_vendor')" class="bg-green-600 text-white px-4 py-2 rounded shadow">Export Vendor Report</button>
        <button onclick="exportTableToExcel('projectReportTable', 'report_project')" class="bg-blue-600 text-white px-4 py-2 rounded shadow">Export Project Report</button>
        <button onclick="exportTableToExcel('monthReportTable', 'report_month')" class="bg-purple-600 text-white px-4 py-2 rounded shadow">Export Month Report</button>
        <button onclick="exportTableToExcel('categoryReportTable', 'report_category')" class="bg-yellow-600 text-white px-4 py-2 rounded shadow">Export Category Report</button>
        <button onclick="exportTableToExcel('requestReportTable', 'report_request')" class="bg-red-600 text-white px-4 py-2 rounded shadow">Export Request Report</button>
    </div>

    {{-- VENDOR REPORT --}}
    <div class="mb-10">
        <h3 class="text-lg font-semibold mb-2">Vendor Report</h3>
        <table id="vendorReportTable" class="min-w-full text-sm border">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="border px-4 py-2">Vendor</th>
                    <th class="border px-4 py-2">Total Orders</th>
                    <th class="border px-4 py-2">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendorData as $row)
                <tr>
                    <td class="border px-4 py-2">{{ $row->vendor_name }}</td>
                    <td class="border px-4 py-2">{{ $row->total_orders }}</td>
                    <td class="border px-4 py-2">{{ number_format($row->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PROJECT REPORT --}}
    <div class="mb-10">
        <h3 class="text-lg font-semibold mb-2">Project Report</h3>
        <table id="projectReportTable" class="min-w-full text-sm border">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="border px-4 py-2">Project</th>
                    <th class="border px-4 py-2">Total Orders</th>
                    <th class="border px-4 py-2">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projectData as $row)
                <tr>
                    <td class="border px-4 py-2">{{ $row->project_name }}</td>
                    <td class="border px-4 py-2">{{ $row->total_orders }}</td>
                    <td class="border px-4 py-2">{{ number_format($row->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MONTH REPORT --}}
    <div class="mb-10">
        <h3 class="text-lg font-semibold mb-2">Month Report</h3>
        <table id="monthReportTable" class="min-w-full text-sm border">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="border px-4 py-2">Month</th>
                    <th class="border px-4 py-2">Total Orders</th>
                    <th class="border px-4 py-2">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthData as $row)
                <tr>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::create()->month($row->month)->format('F') }}</td>
                    <td class="border px-4 py-2">{{ $row->total_orders }}</td>
                    <td class="border px-4 py-2">{{ number_format($row->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- CATEGORY REPORT --}}
    <div class="mb-10">
        <h3 class="text-lg font-semibold mb-2">Category Report</h3>
        <table id="categoryReportTable" class="min-w-full text-sm border">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="border px-4 py-2">Category</th>
                    <th class="border px-4 py-2">Total Orders</th>
                    <th class="border px-4 py-2">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryData as $row)
                <tr>
                    <td class="border px-4 py-2">{{ $row->category }}</td>
                    <td class="border px-4 py-2">{{ $row->total_orders }}</td>
                    <td class="border px-4 py-2">{{ number_format($row->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- REQUEST REPORT --}}
    <div class="mb-10">
        <h3 class="text-lg font-semibold mb-2">Request Report</h3>
        <table id="requestReportTable" class="min-w-full text-sm border">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="border px-4 py-2">Request</th>
                    <th class="border px-4 py-2">Total Orders</th>
                    <th class="border px-4 py-2">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requestData as $row)
                <tr>
                    <td class="border px-4 py-2">{{ $row->request_name }}</td>
                    <td class="border px-4 py-2">{{ $row->total_orders }}</td>
                    <td class="border px-4 py-2">{{ number_format($row->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- SheetJS export function --}}
<script>
    function exportTableToExcel(tableID, filename = '') {
        const table = document.getElementById(tableID);
        const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
        XLSX.writeFile(wb, filename + '.xlsx');
    }
</script>

@endsection
