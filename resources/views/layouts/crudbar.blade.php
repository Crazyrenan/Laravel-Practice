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
        <!-- Static Dashboard Link -->
        <div class="px-4 text-xs text-gray-400 uppercase tracking-wide">Overview</div>
        <a href="/home" class="nav-item px-4 py-2 flex items-center gap-2 hover:bg-white/10 transition">
            <svg class="nav-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            Dashboard
        </a>

        @php
            $sortedMenus = collect($menus)->sortBy('position');
        @endphp

        @foreach ($sortedMenus as $menu)
            @if ($menu->sub_menu == 1)
                {{-- This is a dropdown menu with submenus --}}
                <div class="dropdown-section">
                    <div class="dropdown-header px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-white/10 transition" onclick="toggleDropdown('{{ $menu->id }}')">
                        <div class="flex items-center gap-2">
                            <i class="{{ $menu->icon }} nav-icon w-5 h-5"></i>
                            <span class="font-semibold text-sm">{{ $menu->nama_menu }}</span>
                        </div>
                        <svg class="dropdown-icon w-4 h-4 transition-transform" id="{{ $menu->id }}Icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </div>
                    <div class="dropdown-content hidden flex flex-col text-sm" id="{{ $menu->id }}Content">
                       @php
                            // Filter and sort submenus for the current parent menu
                            $filteredSubmenus = collect($submenus)->where('id_menu', $menu->id)->filter(fn($submenu) => $submenu->status == 1)->sortBy('position');
                        @endphp
                        @foreach ($filteredSubmenus as $submenu)
                            <a href="{{ $submenu->link }}" class="dropdown-item px-6 py-2 flex items-center gap-2 hover:bg-white/10 transition">
                                <i class="{{ $submenu->icon }} nav-icon w-4 h-4"></i>
                                <span>{{ $submenu->nama }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- This is a single menu item --}}
                <a href="{{ route($menu->link) }}" class="nav-item px-4 py-2 flex items-center gap-2 hover:bg-white/10 transition">
                    <i class="{{ $menu->icon }} nav-icon w-5 h-5"></i>
                    <span>{{ $menu->nama_menu }}</span>
                </a>
            @endif
        @endforeach
    </div>

    <!-- Footer -->
    <div class="mt-6 border-t border-white/10 pt-4">
        <a href="/menus-submenus" class="nav-item px-4 py-2 flex items-center gap-2 hover:bg-white/10 transition">
            <svg class="nav-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 00-2.286.99v1.23a2 2 0 01-.767 1.605c-1.287 1.348-2.072 2.115-2.072 3.864 0 1.25.378 2.222 1.131 2.872.766.666 1.83 1.002 3.033 1.002s2.267-.336 3.033-1.002c.753-.65 1.131-1.622 1.131-2.872 0-1.749-.785-2.516-2.072-3.864a2 2 0 01-.767-1.605V4.16c-.38-.24-1.246-.66-2.98 0z" clip-rule="evenodd"/>
            </svg>
            Settings
        </a>
        <a href="/logout" class="nav-item px-4 py-2 flex items-center gap-2 text-red-500 hover:bg-red-500/10 transition">
            <svg class="nav-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h8a1 1 0 001-1V4a1 1 0 00-1-1H3zm13.293 7.293a1 1 0 000 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L18 11.586V7a1 1 0 10-2 0v4.586l-1.293-1.293a1 1 0 00-1.414 0z" clip-rule="evenodd"/>
            </svg>
            Logout
        </a>
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
