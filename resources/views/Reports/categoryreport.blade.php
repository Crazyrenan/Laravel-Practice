@extends('layouts.appnew')

@section('content')
 <div class="bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Item Monthly Report</h1>
                <p class="text-gray-300">Overview of quantity by category each month. Click on an row to view detailed breakdown</p>
            </div>
            <div class="flex space-x-3">
                <button id="refresh-btn" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
                  <button id="exportCSV" class="bg-green-600 hover:bg-white/20 border border-white/20 px-5 py-2 rounded-lg text-white transition-all duration-300">
                   Export to CSV
                  </button>
            </div>
        </div>
    </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-gray-800 p-4 rounded-lg shadow">
          <p class="text-gray-400 text-sm">Total Quantity</p>
          <h2 id="total-quantity" class="text-2xl font-bold text-white">0</h2>
      </div>
      <div class="bg-gray-800 p-4 rounded-lg shadow">
          <p class="text-gray-400 text-sm">Top Category</p>
          <h2 id="top-category" class="text-2xl font-bold text-white">-</h2>
      </div>
      <div class="bg-gray-800 p-4 rounded-lg shadow">
          <p class="text-gray-400 text-sm">Number of Categories</p>
          <h2 id="category-count" class="text-2xl font-bold text-white">0</h2>
      </div>
  </div>


    <div class="bg-white/10 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl overflow-hidden p-6" data-aos="fade-up" data-aos-delay="100">
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto text-sm text-left text-white">
          <thead class="uppercase text-gray-400 border-b border-white/20">
            <tr>
              <th class="px-4 py-3">Month</th>
              <th class="px-4 py-3">Category</th>
              <th class="px-4 py-3">Quantity</th>
            </tr>
          </thead>
          <tbody id="reportTableBody" class="divide-y divide-white/10">
            <!-- AJAX content goes here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Pop Up -->
  <div id="itemDetailsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-gray-800/95 backdrop-blur-xl border border-white/20 rounded-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
      <div class="flex items-center justify-between p-6 border-b border-white/10">
        <div>
          <h3 class="text-2xl font-bold text-white">Item Details</h3>
          <p class="text-gray-300 mt-1" id="modalSubtitle">Loading...</p>
        </div>
        <button id="closeModal" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
          <svg class="h-6 w-6 text-gray-400 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
        <!-- Export -->
        <div class="mb-4 flex justify-end">
          <button id="exportModalExcel" class="bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2 rounded-lg text-white text-sm transition-all duration-300">
            Export to Excel
          </button>
        </div>
        <div id="modalContent">
          <div class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-400"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="{{ asset('js/report/categoryreport.js') }}"></script>

<style>
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  #modalContent tbody tr {
    animation: fadeInUp 0.4s ease-out forwards;
  }
</style>
@endpush
