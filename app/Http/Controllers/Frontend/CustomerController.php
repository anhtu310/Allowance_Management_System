<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AllowanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function info()
    {
        $customer = auth('customer')->user();

        $histories = $customer->histories()
            ->with(['request', 'voucher'])
            ->latest()
            ->get();

        return view('frontend.customer-info', compact('customer', 'histories'));
    }

    public function requestForm()
    {
        $customer = auth('customer')->user();

        $pendingRequests = $customer->requests()
            ->where('status', 'pending')
            ->latest()
            ->get();

        $histories = $customer->histories()
            ->with(['request', 'voucher'])
            ->latest()
            ->get();

        return view('frontend.customer-request', compact('customer', 'pendingRequests', 'histories'));
    }

    public function submitRequest(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $customer = auth('customer')->user();

        // Kiểm tra đủ phụ cấp
        if ($customer->total_allowance < $request->amount) {
            return back()->withErrors(['amount' => 'You do not have enough allowance available.']);
        }

        DB::transaction(function () use ($customer, $request) {
            // Trừ phụ cấp
            $customer->decrement('total_allowance', $request->amount);

            // Tạo yêu cầu phụ cấp
            $customer->requests()->create([
                'amount_requested' => $request->amount,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('customer.request.form')->with('success', 'Request has been submitted.');
    }

}
