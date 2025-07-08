<div style="text-align: center" class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
    <div class="bg-blue-50 p-5 rounded-xl shadow-md border border-blue-200">
        <h2 class="text-base font-semibold text-blue-900 mb-3">👤 Customer Info</h2>
        <dl class="space-y-2 text-blue-800">
            <div><dt class="font-medium">Name:</dt><dd>{{ $record->customer->name }}</dd></div>
            <div><dt class="font-medium">Email:</dt><dd>{{ $record->customer->email }}</dd></div>
            <div><dt class="font-medium">Phone:</dt><dd>{{ $record->customer->phone }}</dd></div>
            <div><dt class="font-medium">Total Allowance:</dt><dd>{{ number_format($record->customer->total_allowance, 0) }}</dd></div>
        </dl>
    </div>

    <div class="bg-yellow-50 p-5 rounded-xl shadow-md border border-yellow-200">
        <h2 class="text-base font-semibold text-yellow-900 mb-3">📄 Request Info</h2>
        <dl class="space-y-2 text-yellow-800">
            <div><dt class="font-medium">Amount Requested:</dt><dd>{{ number_format($record->amount_requested, 0) }}</dd></div>
            <div><dt class="font-medium">Reason:</dt><dd>{{ $record->reason }}</dd></div>
            <div><dt class="font-medium">Requested At:</dt>
                <dd>{{ optional($record->created_at)->format('H:i d/m/Y') }}</dd>
            </div>
        </dl>
    </div>
</div>
