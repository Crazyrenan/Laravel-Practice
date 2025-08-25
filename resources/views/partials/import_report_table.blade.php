@if(isset($results) && isset($header))
<style>
    /* Tambahkan style untuk SweetAlert2 jika perlu */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-failed {
        background-color: #fca5a5;
        color: #7f1d1d;
    }
    .status-success {
        background-color: #a7f3d0;
        color: #065f46;
    }
</style>

<div class="overflow-x-auto text-sm text-left text-gray-400">
   <p class="text-gray-400 mb-4">{{ $message ?? 'Laporan Pembaruan CSV' }}</p>
    <table class="w-full">
        <thead class="bg-gray-700 text-xs text-gray-300 uppercase">
            <tr>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2">Detail Error</th>
                @foreach($header as $column)
                    <th class="px-3 py-2">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-gray-800 divide-y divide-gray-700">
            @foreach($results as $result)
                <tr class="hover:bg-gray-700">
                    <td class="px-3 py-2 whitespace-nowrap">
                        <span class="status-badge {{ $result['status'] === 'failed' ? 'status-failed' : 'status-success' }}">
                            {{ $result['status'] === 'failed' ? 'Gagal' : 'Berhasil' }}
                        </span>
                    </td>
                    <td class="px-3 py-2">
                        @if($result['status'] === 'failed')
                            <ul class="text-red-300 space-y-1">
                                @foreach($result['errors'] as $error)
                                    <li><i class="fas fa-exclamation-triangle text-red-500 mr-1"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-green-300">Data valid</span>
                        @endif
                    </td>
                    @foreach($header as $column)
                        <td class="px-3 py-2 whitespace-nowrap">{{ $result['data'][$column] ?? '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif