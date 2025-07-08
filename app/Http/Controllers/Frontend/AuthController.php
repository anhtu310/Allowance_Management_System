<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $customer = \App\Models\Customer::where('email', $credentials['email'])->first();

        if (! \Hash::check($credentials['password'], $customer->password)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'Invalid email or password.',
            ]);
        }

        if (! $customer->hasVerifiedEmail()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'Email is not registered. Please register and verify your email.',
            ]);
        }

        \Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()->route('customer.info');
    }

    public function showRegisterForm()
    {
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Kiểm tra customer có sẵn theo email
        $customer = Customer::where('email', $request->email)->first();

        if (! $customer) {
            throw ValidationException::withMessages([
                'email' => 'Email not found in our system. Please contact support.',
            ]);
        }

        // Nếu đã xác minh email thì không cho ghi đè
        if ($customer->email_verified_at !== null) {
            throw ValidationException::withMessages([
                'email' => 'This email is already registered and verified.',
            ]);
        }

        session([
            'pending_customer_update' => [
                'email' => $request->email,
                'name' => $request->name,
                'password' => $request->password,
                'phone' => $request->phone,
            ]
        ]);

        $customer->sendEmailVerificationNotification();

        return redirect()->route('login')->with('success', 'Your account has been created. Please check your email to verify.');
    }


    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
