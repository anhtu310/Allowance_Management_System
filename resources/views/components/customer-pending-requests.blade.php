<table border="1" width="100%">
    <thead>
    <tr>
        <th>Amount</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Requested At</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($pendingRequests as $req)
        <tr>
            <td>{{ number_format($req->amount_requested, 0) }} VND</td>
            <td>{{ $req->reason }}</td>
            <td>{{ ucfirst($req->status) }}</td>
            <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No pending requests.</td>
        </tr>
    @endforelse
    </tbody>
</table>
