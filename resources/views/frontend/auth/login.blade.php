@extends('components.layout')

@section('content')
    <h2>Đăng nhập</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input name="email" type="email" placeholder="Email" value="{{ old('email') }}" required>
        <input name="password" type="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>

    <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></p>
@endsection
