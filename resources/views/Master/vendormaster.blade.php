@extends('layouts.app')

@section('title', 'Vendor Master')

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

    .bg-yellow-500 {
        background: linear-gradient(135deg, var(--apple-yellow), var(--apple-orange)) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .bg-yellow-500::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .bg-yellow-500:hover::before {
        left: 100%;
    }

    .bg-yellow-500:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(255, 204, 0, 0.3);
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

    /* Enhanced form input styling */
    #columnSelect,
    #searchValue,
    #vendor_name,
    #vendor_email,
    #vendor_phone,
    #vendor_address,
    #vendor_company,
    #vendor_taxid,
    #vendor_website,
    #vendor_status,
    #edit_vendor_name,
    #edit_vendor_email,
    #edit_vendor_phone,
    #edit_vendor_address,
    #edit_vendor_company,
    #edit_vendor_taxid,
    #edit_vendor_website,
    #edit_vendor_status,
    .input,
    input[type="text"],
    input[type="email"],
    input[type="url"],
    select {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 12px !important;
        color: white !important;
        padding: 0.75rem 1rem !important;
        transition: all 0.3s ease !important;
        font-size: 14px !important;
        width: 100% !important;
        box-sizing: border-box !important;
        pointer-events: auto !important;
        user-select: text !important;
    }

    #columnSelect:focus,
    #searchValue:focus,
    #vendor_name:focus,
    #vendor_email:focus,
    #vendor_phone:focus,
    #vendor_address:focus,
    #vendor_company:focus,
    #vendor_taxid:focus,
    #vendor_website:focus,
    #vendor_status:focus,
    #edit_vendor_name:focus,
    #edit_vendor_email:focus,
    #edit_vendor_phone:focus,
    #edit_vendor_address:focus,
    #edit_vendor_company:focus,
    #edit_vendor_taxid:focus,
    #edit_vendor_website:focus,
    #edit_vendor_status:focus,
    .input:focus,
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="url"]:focus,
    select:focus {
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

    select#columnSelect option,
    select#vendor_status option,
    select#edit_vendor_status option {
        color: black !important;
        background: white !important;
        padding: 0.5rem !important;
    }

    /* Placeholder styling */
    input::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
        opacity: 1 !important;
    }

    /* Enhanced table */
    #vendor-table {
        background: rgba(28, 28, 30, 0.6) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 16px !important;
        overflow: hidden;
    }

    .table-container {
        background: rgba(28, 28, 30, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-container thead {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    .table-container th {
        color: rgba(255, 255, 255, 0.9) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        font-weight: 600;
        padding: 1rem;
        text-align: left;
    }

    .table-container td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        color: rgba(255, 255, 255, 0.8) !important;
        padding: 1rem;
    }

    .table-container tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .table-container tbody tr:hover {
        background: rgba(255, 255, 255, 0.05) !important;
        transform: translateX(4px);
    }

    .table-container tbody tr.selected {
        background: rgba(52, 199, 89, 0.2) !important;
        border-left: 4px solid var(--apple-green);
    }

    /* Enhanced modal */
    #editModal {
        backdrop-filter: blur(10px);
    }

    #editModal .bg-zinc-900 {
        background: rgba(28, 28, 30, 0.9) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        position: relative;
    }

    #editModal .bg-zinc-900::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    }

    /* Labels */
    label {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
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

    /* Status badges */
    .status-active {
        background: linear-gradient(135deg, var(--apple-green), #30D158);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-inactive {
        background: linear-gradient(135deg, var(--apple-red), var(--apple-pink));
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
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
        
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Form grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                <span class="gradient-text">🏢 Vendor</span> Master
            </h1>
            <p class="text-xl text-white/80">
                Manage and track all vendor information with advanced search capabilities
            </p>
        </div>
    </div>

    <!-- Search Section -->
    <div class="glass-container neon-glow mb-6">
        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
            🔍 <span class="gradient-text">Advanced Search</span>
        </h2>
        <div class="mb-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="columnSelect">Choose Column:</label>
                    <select id="columnSelect" class="w-full px-4 py-2 rounded bg-white/10 text-white border border-white/20">
                        <option value="">-- All Columns --</option>
                        <option value="name">Name</option>
                        <option value="email">Email</option>
                        <option value="phone">Phone</option>
                        <option value="address">Address</option>
                        <option value="company_name">Company</option>
                        <option value="tax_id">Tax ID</option>
                        <option value="website">Website</option>
                        <option value="status">Status</option>
                    </select>
                </div>
                <div>
                    <label for="searchValue">Enter Value:</label>
                    <input type="text" id="searchValue" placeholder="Type search value"
                           class="w-full px-4 py-2 rounded bg-white/10 text-white border border-white/20">
                </div>
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
            <button onclick="exportTableToExcel('vendor-table-export', 'Vendors.xlsx')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                📄 Export to Excel
            </button>
            <button id="openInsertVendorModal" class="px-4 py-2 bg-green-600 text-white rounded-md">
                ➕ Add Vendor
            </button>
            <button id="editSelectedBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                ✏️ Edit Selected
            </button>
            <button id="deleteSelectedBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                🗑️ Delete Selected
            </button>
        </div>
    </div>

    <!-- Results Section -->
    <div id="resultsWrapper" class="glass-container fade-in">
        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
            📊 <span class="gradient-text">Vendor Data</span>
        </h2>
        <div class="overflow-x-auto table-container">
            <table id="vendor-table-export" class="w-full text-sm">
                <thead class="bg-white/10 text-white uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Address</th>
                        <th class="px-4 py-3">Company</th>
                        <th class="px-4 py-3">Tax ID</th>
                        <th class="px-4 py-3">Website</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody id="vendor-table" class="divide-y divide-white/10 text-white/90">
                    <!-- Table rows rendered by JS -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Insert Modal -->
<div id="insertVendorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white dark:bg-gray-900 text-black dark:text-white p-6 rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative">
        <div class="relative z-10">
            <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                ➕ <span class="gradient-text">Add New Vendor</span>
            </h2>
            <form id="vendorForm" class="space-y-4">
                @csrf
                <div class="form-grid">
                    <div>
                        <label for="vendor_name">Vendor Name *</label>
                        <input type="text" id="vendor_name" name="name" placeholder="Vendor Name" class="input" required>
                    </div>
                    <div>
                        <label for="vendor_email">Email *</label>
                        <input type="email" id="vendor_email" name="email" placeholder="Email Address" class="input" required>
                    </div>
                    <div>
                        <label for="vendor_phone">Phone *</label>
                        <input type="text" id="vendor_phone" name="phone" placeholder="Phone Number" class="input" required>
                    </div>
                    <div>
                        <label for="vendor_address">Address *</label>
                        <input type="text" id="vendor_address" name="address" placeholder="Full Address" class="input" required>
                    </div>
                    <div>
                        <label for="vendor_company">Company Name *</label>
                        <input type="text" id="vendor_company" name="company_name" placeholder="Company Name" class="input" required>
                    </div>
                    <div>
                        <label for="vendor_taxid">Tax ID *</label>
                        <input type="text" id="vendor_taxid" name="tax_id" placeholder="Tax ID Number" class="input" required>
                    </div>
                    <div>
                        <label for="vendor_website">Website</label>
                        <input type="url" id="vendor_website" name="website" placeholder="https://example.com" class="input">
                    </div>
                    <div>
                        <label for="vendor_status">Status *</label>
                        <select id="vendor_status" name="status" class="input" required>
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-6 gap-3 pt-4 border-t border-white/10">
                    <button type="button" id="closeInsertVendorModal" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors">
                        ✅ Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-zinc-900 text-white p-6 rounded-lg w-full max-w-4xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="relative z-10">
            <h3 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                ✏️ <span class="gradient-text">Edit Vendor</span>
            </h3>
            <form id="editForm" class="space-y-4">
                <input type="hidden" id="edit_vendor_id">
                <div class="form-grid">
                    <div>
                        <label for="edit_vendor_name">Vendor Name *</label>
                        <input type="text" id="edit_vendor_name" name="name" placeholder="Vendor Name" class="input" required>
                    </div>
                    <div>
                        <label for="edit_vendor_email">Email *</label>
                        <input type="email" id="edit_vendor_email" name="email" placeholder="Email Address" class="input" required>
                    </div>
                    <div>
                        <label for="edit_vendor_phone">Phone *</label>
                        <input type="text" id="edit_vendor_phone" name="phone" placeholder="Phone Number" class="input" required>
                    </div>
                    <div>
                        <label for="edit_vendor_address">Address *</label>
                        <input type="text" id="edit_vendor_address" name="address" placeholder="Full Address" class="input" required>
                    </div>
                    <div>
                        <label for="edit_vendor_company">Company Name *</label>
                        <input type="text" id="edit_vendor_company" name="company_name" placeholder="Company Name" class="input" required>
                    </div>
                    <div>
                        <label for="edit_vendor_taxid">Tax ID *</label>
                        <input type="text" id="edit_vendor_taxid" name="tax_id" placeholder="Tax ID Number" class="input" required>
                    </div>
                    <div>
                        <label for="edit_vendor_website">Website</label>
                        <input type="url" id="edit_vendor_website" name="website" placeholder="https://example.com" class="input">
                    </div>
                    <div>
                        <label for="edit_vendor_status">Status *</label>
                        <select id="edit_vendor_status" name="status" class="input" required>
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-6 gap-3 pt-4 border-t border-white/10">
                    <button type="button" onclick="$('#editModal').addClass('hidden')" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
                        ❌ Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors">
                        📂 Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>
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
</script>
@endpush
