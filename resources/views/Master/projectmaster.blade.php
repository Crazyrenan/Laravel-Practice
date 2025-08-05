@extends('layouts.app')

@section('title', 'Project Master')

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

    .bg-purple-600 {
        background: linear-gradient(135deg, var(--apple-purple), var(--apple-pink)) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .bg-purple-600::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .bg-purple-600:hover::before {
        left: 100%;
    }

    .bg-purple-600:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(175, 82, 222, 0.3);
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
    #project_name,
    #project_description,
    #project_status,
    #project_start_date,
    #project_end_date,
    #project_budget,
    #project_manager,
    #edit_project_name,
    #edit_project_description,
    #edit_project_status,
    #edit_project_start_date,
    #edit_project_end_date,
    #edit_project_budget,
    #edit_project_manager,
    .input,
    input[type="text"],
    input[type="date"],
    input[type="number"],
    textarea,
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

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    #columnSelect:focus,
    #searchValue:focus,
    #project_name:focus,
    #project_description:focus,
    #project_status:focus,
    #project_start_date:focus,
    #project_end_date:focus,
    #project_budget:focus,
    #project_manager:focus,
    #edit_project_name:focus,
    #edit_project_description:focus,
    #edit_project_status:focus,
    #edit_project_start_date:focus,
    #edit_project_end_date:focus,
    #edit_project_budget:focus,
    #edit_project_manager:focus,
    .input:focus,
    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="number"]:focus,
    textarea:focus,
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
    select#project_status option,
    select#edit_project_status option {
        color: black !important;
        background: white !important;
        padding: 0.5rem !important;
    }

    /* Placeholder styling */
    input::placeholder,
    textarea::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
        opacity: 1 !important;
    }

    /* Enhanced table */
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
    #insertProjectModal,
    #editModal {
        backdrop-filter: blur(10px);
    }

    #insertProjectModal .bg-white,
    #editModal .bg-zinc-900 {
        background: rgba(28, 28, 30, 0.9) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        position: relative;
    }

    #insertProjectModal .bg-white::before,
    #editModal .bg-zinc-900::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    }

    #insertProjectModal .text-black {
        color: white !important;
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

    .status-completed {
        background: linear-gradient(135deg, var(--apple-blue), var(--apple-teal));
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-on-hold {
        background: linear-gradient(135deg, var(--apple-yellow), var(--apple-orange));
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Progress bar */
    .progress-bar {
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--apple-green), var(--apple-teal));
        border-radius: 4px;
        transition: width 0.3s ease;
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
                <span class="gradient-text">📋 Project</span> Master
            </h1>
            <p class="text-xl text-white/80">
                Manage and track all project information with advanced search capabilities
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
                        <option value="description">Description</option>
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
            <button onclick="exportTableToExcel('project-table-export', 'Projects.xlsx')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                📄 Export to Excel
            </button>
            <button id="openInsertProjectModal" class="px-4 py-2 bg-green-600 text-white rounded-md">
                ➕ Add Project
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
            📊 <span class="gradient-text">Project Data</span>
        </h2>
        <div class="overflow-x-auto table-container">
            <table id="project-table-export" class="w-full text-sm">
                <thead class="bg-white/10 text-white uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Progress</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody id="project-table" class="divide-y divide-white/10 text-white/90">
                    <!-- Table rows rendered by JS -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Insert Modal -->
<div id="insertProjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white dark:bg-gray-900 text-black dark:text-white p-6 rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative">
        <div class="relative z-10">
            <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                ➕ <span class="gradient-text">Add New Project</span>
            </h2>
            <form id="projectForm" class="space-y-4">
                @csrf
                <div class="form-grid">
                    <div>
                        <label for="project_name">Project Name *</label>
                        <input type="text" id="project_name" name="name" placeholder="Project Name" class="input" required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="project_description">Description *</label>
                        <textarea id="project_description" name="description" placeholder="Project Description" class="input" required></textarea>
                    </div>
                    <div>
                        <label for="project_status">Status *</label>
                        <select id="project_status" name="status" class="input" required>
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="completed">Completed</option>
                            <option value="on-hold">On Hold</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-6 gap-3 pt-4 border-t border-white/10">
                    <button type="button" id="closeInsertProjectModal" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
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
                ✏️ <span class="gradient-text">Edit Project</span>
            </h3>
            <form id="editForm" class="space-y-4">
                <input type="hidden" id="edit_project_id">
                <div class="form-grid">
                    <div>
                        <label for="edit_project_name">Project Name *</label>
                        <input type="text" id="edit_project_name" name="name" placeholder="Project Name" class="input" required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="edit_project_description">Description *</label>
                        <textarea id="edit_project_description" name="description" placeholder="Project Description" class="input" required></textarea>
                    </div>
                    <div>
                        <label for="edit_project_status">Status *</label>
                        <select id="edit_project_status" name="status" class="input" required>
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="completed">Completed</option>
                            <option value="on-hold">On Hold</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-6 gap-3 pt-4 border-t border-white/10">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
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
let selectedProjectId = null;

// Enhanced table render function
function renderProjectTable(data) {
    if (!data.length) {
        $('#project-table').html('<tr><td colspan="10" class="text-center py-8 text-white/60">🔍 No projects found.</td></tr>');
        return;
    }
    
    let rows = '';
    data.forEach((project, i) => {
        const statusClass = getStatusClass(project.status);
        const progress = project.progress || Math.floor(Math.random() * 100); // Mock progress if not available
        const budget = project.budget ? `$${parseFloat(project.budget).toLocaleString()}` : '-';
        const startDate = project.start_date ? new Date(project.start_date).toLocaleDateString() : '-';
        const endDate = project.end_date ? new Date(project.end_date).toLocaleDateString() : '-';
        const createdAt = new Date(project.created_at).toLocaleDateString();
            
        rows += `
            <tr class="hover:bg-white/5 transition-all duration-200 cursor-pointer" data-id="${project.id}">
                <td class="px-4 py-3 text-center">${i + 1}</td>
                <td class="px-4 py-3 font-semibold">${project.name}</td>
                <td class="px-4 py-3 max-w-xs truncate" title="${project.description}">${project.description}</td>
                <td class="px-4 py-3">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: ${progress}%"></div>
                    </div>
                    <span class="text-xs text-white/60 mt-1">${progress}%</span>
                </td>
                <td class="px-4 py-3">
                    <span class="${statusClass}">${project.status}</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors" data-id="${project.id}">
                        🗑️ Delete
                    </button>
                </td>
            </tr>
        `;
    });
    $('#project-table').html(rows);
}

function getStatusClass(status) {
    switch(status) {
        case 'active': return 'status-active';
        case 'inactive': return 'status-inactive';
        case 'completed': return 'status-completed';
        case 'on-hold': return 'status-on-hold';
        default: return 'status-inactive';
    }
}

// Load initial data
$(document).ready(function () {
    $.get('/api/projects/search', function (data) {
        renderProjectTable(data);
    }).fail(function() {
        console.error('Could not load initial project data');
        $('#project-table').html('<tr><td colspan="10" class="text-center py-8 text-red-400">❌ Failed to load data</td></tr>');
    });
});

// Search functionality
$('#searchBtn').on('click', function () {
    const column = $('#columnSelect').val();
    const value = $('#searchValue').val().trim();
    
    if (!column || !value) {
        $.get('/api/projects/search', function (data) {
            renderProjectTable(data);
        });
        return;
    }
    
    $.get(`/api/projects/search?column=${encodeURIComponent(column)}&value=${encodeURIComponent(value)}`, function (data) {
        renderProjectTable(data);
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
$(document).on('click', '#project-table tr[data-id]', function () {
    $('#project-table tr').removeClass('selected');
    $(this).addClass('selected');
    selectedProjectId = $(this).data('id');
});

// Insert Modal Management
$('#openInsertProjectModal').on('click', function () {
    $('#insertProjectModal').removeClass('hidden');
    document.body.style.overflow = 'hidden';
    // Set default start date to today
    const today = new Date().toISOString().split('T')[0];
    $('#project_start_date').val(today);
});

$('#closeInsertProjectModal').on('click', function () {
    closeInsertModal();
});

function closeInsertModal() {
    $('#insertProjectModal').addClass('hidden');
    document.body.style.overflow = 'auto';
    $('#projectForm')[0].reset();
    $('#submitBtn').html('✅ Submit').prop('disabled', false);
}

function closeEditModal() {
    $('#editModal').addClass('hidden');
    document.body.style.overflow = 'auto';
    selectedProjectId = null;
}

// Close modal on backdrop click
$('#insertProjectModal').on('click', function(e) {
    if (e.target === this) {
        closeInsertModal();
    }
});

$('#editModal').on('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Insert project form submission
$('#projectForm').on('submit', function (e) {
    e.preventDefault();
    
    // Validate required fields
    const requiredFields = ['name', 'description', 'status'];
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
        name: $('#project_name').val(),
        description: $('#project_description').val(),
        manager: $('#project_manager').val(),
        budget: $('#project_budget').val(),
        start_date: $('#project_start_date').val(),
        end_date: $('#project_end_date').val(),
        status: $('#project_status').val(),
    };
    
    $.ajax({
        type: 'POST',
        url: '/api/projects/insert',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            alert('✅ ' + (res.message || 'Project added successfully!'));
            closeInsertModal();
            // Refresh table
            $.get('/api/projects/search', function (data) {
                renderProjectTable(data);
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

// Edit functionality
$('#editSelectedBtn').on('click', function () {
    if (!selectedProjectId) {
        alert('⚠️ Please select a project row first!');
        return;
    }
    
    const row = $(`#project-table tr[data-id="${selectedProjectId}"]`);
    
    $('#edit_project_id').val(selectedProjectId);
    $('#edit_project_name').val(row.find('td:nth-child(2)').text());
    $('#edit_project_description').val(row.find('td:nth-child(3)').attr('title') || row.find('td:nth-child(3)').text());
    $('#edit_project_manager').val(row.find('td:nth-child(4)').text() === '-' ? '' : row.find('td:nth-child(4)').text());
    
    // Handle budget
    const budgetText = row.find('td:nth-child(5)').text();
    if (budgetText !== '-') {
        $('#edit_project_budget').val(budgetText.replace('$', '').replace(/,/g, ''));
    }
    
    $('#edit_project_status').val(row.find('td:nth-child(9) span').text().toLowerCase());
    $('#editModal').removeClass('hidden');
    document.body.style.overflow = 'hidden';
});

// Edit form submission
$('#editForm').on('submit', function (e) {
    e.preventDefault();
    
    const id = $('#edit_project_id').val();
    const data = {
        name: $('#edit_project_name').val(),
        description: $('#edit_project_description').val(),
        status: $('#edit_project_status').val(),
    };
    
    $.ajax({
        type: 'PUT',
        url: `/api/projects/update/${id}`,
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            alert('✅ ' + (res.message || 'Project updated successfully!'));
            closeEditModal();
            // Refresh table
            $.get('/api/projects/search', function (data) {
                renderProjectTable(data);
            });
        },
        error: function (xhr) {
            console.error('Update error:', xhr);
            alert('❌ Update failed: ' + (xhr.responseJSON?.message || 'Unknown error'));
        }
    });
});

// Delete functionality
$(document).on('click', '.deleteBtn', function (e) {
    e.stopPropagation(); // Prevent row selection
    
    const id = $(this).data('id');
    const row = $(this).closest('tr');
    
    if (confirm('🗑️ Are you sure you want to delete this project? This action cannot be undone.')) {
        $.ajax({
            type: 'DELETE',
            url: `/api/projects/delete/${id}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                alert('✅ ' + (res.message || 'Project deleted successfully!'));
                row.fadeOut(300, function() {
                    $(this).remove();
                });
                if (selectedProjectId == id) {
                    selectedProjectId = null;
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
        $('#closeInsertProjectModal').click();
        closeEditModal();
    }
});

// Form validation styling
$(document).on('input change', 'input[required], select[required], textarea[required]', function() {
    const element = $(this);
    if (element.val() && element.val().trim() !== '') {
        element.css('border-color', 'rgba(52, 199, 89, 0.5)'); // Green for valid
    } else {
        element.css('border-color', 'rgba(255, 255, 255, 0.2)'); // Default
    }
});

// Date validation
$('#project_start_date, #edit_project_start_date').on('change', function() {
    const startDate = $(this).val();
    const endDateField = $(this).attr('id').includes('edit') ? '#edit_project_end_date' : '#project_end_date';
    const endDate = $(endDateField).val();
    
    if (startDate && endDate && startDate > endDate) {
        alert('⚠️ Start date cannot be later than end date');
        $(endDateField).focus();
    }
});

$('#project_end_date, #edit_project_end_date').on('change', function() {
    const endDate = $(this).val();
    const startDateField = $(this).attr('id').includes('edit') ? '#edit_project_start_date' : '#project_start_date';
    const startDate = $(startDateField).val();
    
    if (startDate && endDate && endDate < startDate) {
        alert('⚠️ End date cannot be earlier than start date');
        $(this).focus();
    }
});
</script>
@endpush
