@extends('layouts.appnew')

@section('content')
<div class="min-h-screen bg-gray-900 text-white px-6 py-8">
    <div class="max-w-6xl mx-auto">

        <h1 class="text-3xl font-bold mb-6">Menus & Submenus</h1>

        <div class="flex gap-4 mb-6">
            <button 
                class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded"
                onclick="openModal('menuModal')">
                Add Menu
            </button>
            <button 
                class="bg-green-600 hover:bg-green-500 px-4 py-2 rounded"
                onclick="openModal('submenuModal')">
                Add Submenu
            </button>
        </div>

        <div class="bg-gray-800 rounded-xl shadow-md p-4 mb-8">
            <h2 class="text-2xl font-bold mb-4">Menus</h2>
            <table class="min-w-full table-auto">
                <thead class="bg-gray-700 text-left">
                    <tr>
                        <th class="px-4 py-2">Icon</th>
                        <th class="px-4 py-2">Menu Name</th>
                        <th class="px-4 py-2">Link</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Submenu</th>
                        <th class="px-4 py-2">Position</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="menusTableBody">
                    </tbody>
            </table>
        </div>

        <div class="bg-gray-800 rounded-xl shadow-md p-4">
            <h2 class="text-2xl font-bold mb-4">Submenus</h2>
            <table class="min-w-full table-auto">
                <thead class="bg-gray-700 text-left">
                    <tr>
                        <th class="px-4 py-2">Menu Parent</th>
                        <th class="px-4 py-2">Icon</th>
                        <th class="px-4 py-2">Submenu Name</th>
                        <th class="px-4 py-2">Link</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Position</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="submenusTableBody">
                    </tbody>
            </table>
        </div>
    </div>
</div>

@include('Option.Modals.menu')       
@include('Option.Modals.submenu')

<script src="{{ asset('js/menus_submenus.js') }}"></script>
@endsection
