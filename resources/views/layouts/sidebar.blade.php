
<!-- Sidebar -->
<div class="sidebar fixed h-full z-50 w-64 text-white text-sm leading-snug" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;" id="sidebar">
    <!-- Sidebar Header -->
    <div class="p-6 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                <span class="text-white font-bold text-lg">📊</span>
            </div>
            <div>
                <h1 class="text-white font-semibold text-base tracking-tight">Dashboard</h1>
                <p class="text-gray-400 text-xs">Analytics Hub</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto py-4 space-y-2">
        <!-- Overview -->
        <div class="px-4 text-xs text-gray-400 uppercase tracking-wide">Overview</div>
        <a href="/home" class="nav-item px-4 py-2 flex items-center gap-2 hover:bg-white/10 transition">
            <svg class="nav-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            Dashboard
        </a>

        <!-- Dropdowns -->
        @php
            $sections = [
                'masterData' => ['title' => 'Master Data', 'items' => [
                    ['label' => 'Vendors', 'href' => '/vendormaster', 'icon' => 'M9 6a3...', 'badge' => 12],
                    ['label' => 'Projects', 'href' => '/projectmaster', 'icon' => 'M2 6a2...'],
                    ['label' => 'Requests', 'href' => '/requestmaster', 'icon' => 'M4 4a2...', 'badge' => 3],
                    ['label' => 'Purchases', 'href' => '/pembelianmaster', 'icon' => 'M10 2a4...'],
                ]],
                 'reportData' => ['title' => 'Report Data', 'items' => [
                    ['label' => 'Purchasing Report', 'href' => '/reported', 'icon' => 'M9 6a3...', 'badge' => 12],
                    ['label' => 'Projects', 'href' => '/projectmaster', 'icon' => 'M2 6a2...'],
                    ['label' => 'Requests', 'href' => '/requestmaster', 'icon' => 'M4 4a2...', 'badge' => 3],
                    ['label' => 'Purchases', 'href' => '/pembelianmaster', 'icon' => 'M10 2a4...'],
                ]],
                'builderQueries' => ['title' => 'Builder Queries', 'items' => [
                    ['label' => 'Vendor Query', 'href' => '/vendor', 'icon' => 'M9 6a3...'],
                    ['label' => 'Status Query', 'href' => '/status', 'icon' => 'M3 3a1...'],
                    ['label' => 'Order Query', 'href' => '/order', 'icon' => 'M10 2a4...'],
                    ['label' => 'Product Avg', 'href' => '/product', 'icon' => 'M3 4a1...']
                ]],
                'normalQueries' => ['title' => 'Normal Queries', 'items' => [
                    ['label' => 'Vendor Data', 'href' => '/vendor2', 'icon' => 'M9 6a3...'],
                    ['label' => 'Status Data', 'href' => '/status2', 'icon' => 'M3 3a1...'],
                    ['label' => 'Order Data', 'href' => '/order2', 'icon' => 'M10 2a4...'],
                    ['label' => 'Product Data', 'href' => '/product2', 'icon' => 'M3 4a1...']
                ]],
                'analytics' => ['title' => 'Analytics', 'items' => [
                    ['label' => 'Vendor by Month', 'href' => '/vendor_chart', 'icon' => 'M3 3a1...'],
                    ['label' => 'Profit Analysis', 'href' => '/profit', 'icon' => 'M4 4a2...'],
                    ['label' => 'Profit by Category', 'href' => '/profitcategory', 'icon' => 'M3 4a1...']
                ]],
                'advanced' => ['title' => 'Advanced', 'items' => [
                    ['label' => 'Vendor Details', 'href' => '/vendorjoin', 'icon' => 'M9 6a3...']
                ]]
            ];
        @endphp

        @foreach ($sections as $id => $section)
            <div class="dropdown-section">
                <div class="dropdown-header px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-white/10 transition" onclick="toggleDropdown('{{ $id }}')">
                    <div class="flex items-center gap-2">
                        <svg class="nav-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="{{ $section['items'][0]['icon'] ?? '' }}" />
                        </svg>
                        <span class="font-semibold text-sm">{{ $section['title'] }}</span>
                    </div>
                    <svg class="dropdown-icon w-4 h-4 transition-transform" id="{{ $id }}Icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div class="dropdown-content hidden flex flex-col text-sm" id="{{ $id }}Content">
                    @foreach ($section['items'] as $item)
                        <a href="{{ $item['href'] }}" class="dropdown-item px-6 py-2 flex items-center gap-2 hover:bg-white/10 transition">
                            <svg class="nav-icon w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="{{ $item['icon'] }}" />
                            </svg>
                            <span class="flex items-center justify-between w-full">
                                <span>{{ $item['label'] }}</span>
                                @if (isset($item['badge']))
                                    <span class="badge ml-auto text-xs bg-purple-600 px-2 py-0.5 rounded-full">{{ $item['badge'] }}</span>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Footer -->
        <div class="mt-6 border-t border-white/10 pt-4">
            <a href="/settings" class="nav-item px-4 py-2 flex items-center gap-2 hover:bg-white/10 transition">
                <svg class="nav-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532..."/>
                </svg>
                Settings
            </a>
            <a href="/logout" class="nav-item px-4 py-2 flex items-center gap-2 text-red-500 hover:bg-red-500/10 transition">
                <svg class="nav-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1..."/>
                </svg>
                Logout
            </a>
        </div>
    </div>
</div>

<!-- Toggle Dropdown Script -->
<script>
function toggleDropdown(id) {
    const content = document.getElementById(`${id}Content`);
    const icon = document.getElementById(`${id}Icon`);
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>
