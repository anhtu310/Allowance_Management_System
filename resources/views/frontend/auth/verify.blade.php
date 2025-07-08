@extends('components.layout')

@section('content')
    <div class="text-center">
        <h2 class="text-xl font-bold mb-4">Verify Your Email Address</h2>
        <p class="mb-4">
            Before proceeding, please check your email for a verification link.
            If you did not receive the email, click below to request another.
        </p>

        @if (session('message'))
            <div class="alert">{{ session('message') }}</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">Resend Verification Email</button>
        </form>
    </div>
@endsection
