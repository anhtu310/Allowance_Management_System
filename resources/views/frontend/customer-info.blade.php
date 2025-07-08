@extends('components.layout')

@section('content')
    <div class="container mx-auto max-w-5xl p-8 bg-white shadow-lg rounded-lg">
        {{-- Customer Info --}}
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 border-b pb-3">Customer Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 text-base">
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 mb-1">Name</span>
                <div class="bg-gray-100 rounded-md px-4 py-2">{{ $customer->name }}</div>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 mb-1">Email</span>
                <div class="bg-gray-100 rounded-md px-4 py-2">{{ $customer->email }}</div>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 mb-1">Phone</span>
                <div class="bg-gray-100 rounded-md px-4 py-2">{{ $customer->phone ?? '-' }}</div>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 mb-1">Total Allowance</span>
                <div class="bg-gray-100 rounded-md px-4 py-2 font-semibold text-green-700">
                    {{ number_format($customer->total_allowance, 0) }} VND
                </div>
            </div>
        </div>

        {{-- Usage History --}}
        <h3 class="text-2xl font-semibold mt-10 mb-4 text-gray-800 border-b pb-2">Usage History</h3>

        <div class="mt-4 overflow-x-auto">
            @include('components.customer-history', ['histories' => $histories])
        </div>

    </div>
@endsection
