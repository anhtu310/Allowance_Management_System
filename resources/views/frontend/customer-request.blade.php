@extends('components.layout')

@section('content')
    <div class="container mx-auto max-w-5xl p-6 bg-white shadow-md rounded-lg">

        {{-- Form gửi yêu cầu --}}
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Request</h2>

        <form method="POST" action="{{ route('customer.request.submit') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
            @csrf
            <div>
                <label class="font-semibold block mb-1">Amount</label>
                <input type="number" name="amount" required
                       class="w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="font-semibold block mb-1">Reason</label>
                <input type="text" name="reason" required
                       class="w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="col-span-1 md:col-span-2 text-right">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    Gửi yêu cầu
                </button>
            </div>
        </form>

        {{-- Danh sách yêu cầu đang xử lý --}}
        <h3 class="text-xl font-bold mt-10 mb-4 text-gray-800 border-b pb-2">Pending Request</h3>

        @include('components.customer-pending-requests', ['pendingRequests' => $pendingRequests])
    </div>
@endsection
