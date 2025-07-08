<div class="mt-6">
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse border border-gray-300">
            <thead>
            <tr class="bg-gray-100 text-sm text-gray-700">
                <th class="border border-gray-300 px-4 py-2">Amount</th>
                <th class="border border-gray-300 px-4 py-2">Balance After</th>
                <th class="border border-gray-300 px-4 py-2">Request Status</th>
                <th class="border border-gray-300 px-4 py-2">Type</th>
                <th class="border border-gray-300 px-4 py-2">Description</th>
                <th class="border border-gray-300 px-4 py-2">Voucher</th>
                <th class="border border-gray-300 px-4 py-2">Created At</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($histories as $history)
                <tr class="text-center text-sm">
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($history->delta, 0) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($history->balance, 0) }}</td>
                    <td class="border px-4 py-2">
                        {{ $history->request?->status ? ucfirst($history->request->status) : '-' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ ucfirst($history->type) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $history->description }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $history->voucher ? $history->voucher->code : '-' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">No data available.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
