@php
    $history = $request->histories->sortByDesc('id')->first();
@endphp

<h2 style="margin-bottom: 16px;">👋 Hello {{ $request->customer->name }},</h2>

<p style="font-size: 16px; margin-top: 12px;">
    Request Status:
    <strong style="color: {{ $request->status === 'approved' ? '#28a745' : '#dc3545' }};">
        {{ ucfirst($request->status) }}
    </strong>
</p>

@if ($request->status === 'approved')
    <p style="margin-top: 10px;">
        🎟️ Your voucher code is:
        <strong style="font-size: 16px; color: #000;">{{ optional($history?->voucher)->code ?? 'N/A' }}</strong>
    </p>
@elseif ($request->status === 'rejected')
    <p style="margin-top: 10px;">
        💸 Since your request was rejected, the amount of
        <strong style="color: #0d6efd;">{{ number_format($request->amount_requested) }} VND</strong>
        has been refunded back to your allowance balance.
    </p>
@endif

<p style="margin-top: 12px; font-size: 14px; color: #555;">
    🕒 Processed at: {{ \Carbon\Carbon::parse($request->handled_at)->format('H:i d/m/Y') }}
</p>

<hr style="margin: 20px 0;">

<p style="font-size: 13px; color: #777;">
    If you have any questions, feel free to contact our support team.
</p>
