@extends('components.layout')

@section('content')
    <h2>Đăng ký</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-3">
        @csrf
        <input name="name" type="text" placeholder="Họ tên" value="{{ old('name') }}" required>
        <input name="email" type="email" placeholder="Email" value="{{ old('email') }}" required>
        <input name="phone" type="text" placeholder="Số điện thoại" value="{{ old('phone') }}" required>
        <input name="password" type="password" placeholder="Mật khẩu" required>
        <input name="password_confirmation" type="password" placeholder="Nhập lại mật khẩu" required>
        <button type="submit">Đăng ký</button>
    </form>

    <p class="mt-4 text-center"><a href="{{ route('login') }}" class="text-blue-600">Đã có tài khoản?</a></p>
@endsection
