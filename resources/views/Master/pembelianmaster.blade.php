@extends('layouts.appnew')

@section('title', 'Pembelian Master')

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
        background: linear-gradient(135deg, 
            rgba(0, 122, 255, 0.1) 0%, 
            rgba(175, 82, 222, 0.1) 25%,
            rgba(255, 149, 0, 0.1) 50%,
            rgba(52, 199, 89, 0.1) 75%,
            rgba(255, 59, 48, 0.1) 100%);
        background-size: 400% 400%;
        animation: holographic 8s ease infinite;
    }

    @keyframes holographic {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .modern-bg::before {
        content: '';
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
        content: '';
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

    .orb-1 { width: 120px; height: 120px; top: 10%; right: 15%; animation-delay: 0s; }
    .orb-2 { width: 80px; height: 80px; bottom: 20%; left: 10%; animation-delay: 2s; }
    .orb-3 { width: 60px; height: 60px; top: 60%; right: 30%; animation-delay: 4s; }

    @keyframes float-orb {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        33% { transform: translateY(-20px) rotate(120deg); }
        66% { transform: translateY(10px) rotate(240deg); }
    }

    /* Header section */
    .page-header {
        background: linear-gradient(135deg, 
            rgba(0, 122, 255, 0.2) 0%, 
            rgba(175, 82, 222, 0.2) 100%);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg, 
            transparent, 
            rgba(0, 122, 255, 0.1), 
            transparent, 
            rgba(175, 82, 222, 0.1), 
            transparent);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .gradient-text {
        background: linear-gradient(135deg, #007AFF, #AF52DE, #FF9500);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Enhanced button styling */
    .bg-orange-500 {
        background: linear-gradient(135deg, var(--apple-orange), var(--apple-yellow)) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .bg-orange-500::before {
        content: '';
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

    .bg-blue-600 {
        background: linear-gradient(135deg, var(--apple-blue), var(--apple-blue-dark)) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .bg-blue-600::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .bg-blue-600:hover::before {
        left: 100%;
    }

    .bg-blue-600:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 122, 255, 0.3);
    }

    .bg-green-600 {
        background: linear-gradient(135deg, var(--apple-green), #30D158) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .bg-green-600::before {
        content: '';
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

    .bg-red-600 {
        background: linear-gradient(135deg, var(--apple-red), var(--apple-pink)) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .bg-red-600::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .bg-red-600:hover::before {
        left: 100%;
    }

    .bg-red-600:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(255, 59, 48, 0.3);
    }

    .bg-gray-500 {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05)) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .bg-gray-500:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1)) !important;
        transform: translateY(-2px);
    }

    /* FIXED: Specific form input styling - more specific selectors */
    #columnSelect,
    #searchValue,
    #vendor_id,
    #project_id,
    #request_id,
    #category_id,
    #pending_id,
    .input,
    input[type="text"],
    input[type="number"],
    input[type="date"] {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 12px !important;
        color: white !important;
        padding: 0.75rem 1rem !important;
        transition: all 0.3s ease !important;
        font-size: 14px !important;
        width: 100% !important;
        box-sizing: border-box !important;
        /* Ensure inputs are interactive */
        pointer-events: auto !important;
        user-select: text !important;
        -webkit-user-select: text !important;
        -moz-user-select: text !important;
        -ms-user-select: text !important;
    }

    #columnSelect:focus,
    #searchValue:focus,
    #vendor_id:focus,
    #project_id:focus,
    #request_id:focus,
    #category_id:focus,
    #pending_id:focus,
    .input:focus,
    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus {
        outline: none !important;
        border-color: var(--apple-blue) !important;
        background: rgba(255, 255, 255, 0.15) !important;
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1) !important;
    }

    /* Select dropdown options */
    select option {
        background: var(--apple-gray-dark) !important;
        color: white !important;
        padding: 0.5rem !important;
    }

    /* Specific fix for the original selects */
    select#columnSelect option,
    select#vendor_id option,
    select#project_id option,
    select#request_id option,
    select#category_id option,
    select#pending_id option {
        color: black !important;
        background: white !important;
        padding: 0.5rem !important;
    }

    /* Placeholder styling */
    input::placeholder,
    input::-webkit-input-placeholder,
    input::-moz-placeholder,
    input:-ms-input-placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
        opacity: 1 !important;
    }

    /* Enhanced table */
    #pembelianTable {
        background: rgba(28, 28, 30, 0.6) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 16px !important;
        overflow: hidden;
    }

    #pembelianTable thead {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    #pembelianTable th {
        color: rgba(255, 255, 255, 0.9) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        font-weight: 600;
    }

    #pembelianTable td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        color: rgba(255, 255, 255, 0.8) !important;
    }

    #pembelianTable tbody tr {
        transition: all 0.2s ease;
    }

    #pembelianTable tbody tr:hover {
        background: rgba(255, 255, 255, 0.05) !important;
        transform: translateX(4px);
    }

    /* Enhanced modal */
    #insertPembelianModal {
        backdrop-filter: blur(10px);
    }

    #insertPembelianModal .bg-white {
        background: rgba(28, 28, 30, 0.9) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        position: relative;
    }

    #insertPembelianModal .bg-white::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    }

    #insertPembelianModal .text-black {
        color: white !important;
    }

    /* Labels */
    label {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Checkbox styling */
    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--apple-blue);
        border-radius: 4px;
        pointer-events: auto !important;
    }

    /* Animation for table appearance */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Neon glow effect */
    .neon-glow {
        position: relative;
    }

    .neon-glow::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: inherit;
        padding: 2px;
        background: linear-gradient(45deg, #007AFF, #AF52DE, #FF9500, #34C759);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask-composite: exclude;
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .neon-glow:hover::after {
        opacity: 1;
    }

    /* Ensure form elements are above pseudo-elements */
    .glass-container > * {
        position: relative;
        z-index: 2;
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
    }

    /* Fix for readonly inputs */
    input[readonly] {
        background: rgba(255, 255, 255, 0.05) !important;
        cursor: not-allowed;
    }

    /* Ensure select dropdowns work properly */
    select {
        cursor: pointer !important;
        -webkit-appearance: menulist !important;
        -moz-appearance: menulist !important;
        appearance: menulist !important;
    }
</style>
@endpush

@section('content')
<div class="modern-bg"></div>
<div class="floating-orb orb-1"></div>
<div class="floating-orb orb-2"></div>
<div class="floating-orb orb-3"></div>

<main class="p-10 relative z-10">
    <!-- Header Section -->
    <div class="page-header">
        <div class="relative z-10">
            <button onclick="window.location.href='/home'" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded text-white font-semibold">
                ⬅️ Home
            </button>
            <h1 class="text-4xl font-bold mb-2 mt-4">
                <span class="gradient-text">🛒 Pembelian</span> Master
            </h1>
            <p class="text-xl text-white/80">
                Manage and track all purchase orders with advanced search capabilities
            </p>
        </div>
    </div>

    <!-- Search Section -->
    <div class="glass-container neon-glow mb-6">
        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
            🔍 <span class="gradient-text">Advanced Search</span>
        </h2>
        <div class="mb-6 space-y-4">
            <div>
                <label for="columnSelect">Choose Column:</label>
                <select id="columnSelect" class="w-full max-w-md px-4 py-2 rounded bg-white/10 text-white border border-white/20">
                    <option value="">-- Select Column --</option>
                </select>
            </div>
            <div>
                <label for="searchValue">Enter Value:</label>
                <input type="text" id="searchValue" placeholder="Type search value"
                       class="w-full max-w-md px-4 py-2 rounded bg-white/10 text-white border border-white/20">
            </div>
            <button id="searchBtn" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded text-white font-semibold">
                🔍 Search
            </button>
        </div>
    </div>

    <!-- Action Buttons Section -->
    <div class="glass-container mb-6">
        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
            ⚡ <span class="gradient-text">Quick Actions</span>
        </h2>
        <div class="flex gap-4 flex-wrap">
            <button onclick="exportTableToExcel('pembelianTable', 'Pembelian.xlsx')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                📄 Export to Excel
            </button>
            <button id="openInsertPembelianModal" class="px-4 py-2 bg-green-600 text-white rounded-md">
                ➕ Add Pembelian
            </button>
            <button id="deleteSelectedBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                🗑️ Delete Selected
            </button>
        </div>
    </div>

    <!-- Results Section -->
    <div id="resultsWrapper" class="glass-container fade-in hidden">
        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
            📊 <span class="gradient-text">Search Results</span>
        </h2>
        <div class="overflow-x-auto">
            <table id="pembelianTable" class="w-full border border-white/20 bg-white/5 text-sm rounded-xl">
                <thead class="bg-white/10 text-white uppercase text-xs">
                    <tr id="tableHead"></tr>
                </thead>
                <tbody id="resultBody"></tbody>
            </table>
        </div>
    </div>
</main>

<!-- Insert Modal -->
<div id="insertPembelianModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white dark:bg-gray-900 text-black dark:text-white p-6 rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative">
        <div class="relative z-10">
            <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                ➕ <span class="gradient-text">Insert Pembelian</span>
            </h2>
            <form id="insertPembelianForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="vendor_id">Vendor *</label>
                        <select id="vendor_id" name="vendor_id" class="input" required>
                            <option value="">Select Vendor</option>
                            @if(isset($vendors))
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="project_id">Project *</label>
                        <select id="project_id" name="project_id" class="input" required>
                            <option value="">Select Project</option>
                            @if(isset($projects))
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="request_id">Requester *</label>
                        <select id="request_id" name="requested_by" class="input" required>
                            <option value="">Select Requester</option>
                            @if(isset($requests))
                                @foreach($requests as $request)
                                    <option value="{{ $request->id }}">{{ $request->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="purchase_order_number">PO Number *</label>
                        <input type="text" id="purchase_order_number" name="purchase_order_number" placeholder="PO Number" class="input" required>
                    </div>
                    <div>
                        <label for="item_name">Item Name *</label>
                        <input type="text" id="item_name" name="item_name" placeholder="Item Name" class="input" required>
                    </div>
                    <div>
                        <label for="item_code">Item Code *</label>
                        <input type="text" id="item_code" name="item_code" placeholder="Item Code" class="input" required>
                    </div>
                    <div>
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category" class="input" required>
                            <option value="">Select Category</option>
                            <option value="Spare Part">Spare Part</option>
                            <option value="Office Supplies">Office Supplies</option>
                            <option value="Fuel">Fuel</option>
                        </select>
                    </div>
                    <div>
                        <label for="quantity">Quantity *</label>
                        <input type="number" id="quantity" name="quantity" placeholder="Quantity" class="input" required min="1" step="1">
                    </div>
                    <div>
                        <label for="unit">Unit</label>
                        <input type="text" id="unit" name="unit" placeholder="Unit (e.g., pcs, kg, liter)" class="input">
                    </div>
                    <div>
                        <label for="buy_price">Buy Price</label>
                        <input type="number" id="buy_price" name="buy_price" placeholder="Buy Price" class="input" min="0" step="0.01">
                    </div>
                    <div>
                        <label for="unit_price">Unit Price *</label>
                        <input type="number" id="unit_price" name="unit_price" placeholder="Unit Price" class="input" required min="0" step="0.01">
                    </div>
                    <div>
                        <label for="tax">Tax</label>
                        <input type="number" id="tax" name="tax" placeholder="Tax Amount" class="input" min="0" step="0.01">
                    </div>
                    <div>
                        <label for="grand_total">Grand Total</label>
                        <input type="number" id="grand_total" name="grand_total" placeholder="Grand Total" class="input" readonly style="background: rgba(255, 255, 255, 0.05) !important;">
                    </div>
                    <div>
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" id="purchase_date" name="purchase_date" class="input">
                    </div>
                    <div>
                        <label for="expected_delivery_date">Expected Delivery Date</label>
                        <input type="date" id="expected_delivery_date" name="expected_delivery_date" class="input">
                    </div>
                    <div>
                        <label for="pending_id">Status</label>
                        <select id="pending_id" name="status" class="input">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label for="remarks">Remarks</label>
                        <input type="text" id="remarks" name="remarks" placeholder="Additional remarks" class="input">
                    </div>
                </div>
                <div class="flex justify-end mt-6 gap-3 pt-4 border-t border-white/10">
                    <button type="button" id="closeInsertPembelianModal" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                        ✅ Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script src="{{ asset('js/pembelian.js') }}"></script>

@endpush
