<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Allowance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navbar */
        .navbar {
            background-color: #007bff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            flex-wrap: wrap;
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
        }

        .navbar .menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar .menu a,
        .navbar .menu form button {
            color: white;
            text-decoration: none;
            background: none;
            border: none;
            font: inherit;
            cursor: pointer;
            padding: 0;
        }

        .navbar .menu a:hover,
        .navbar .menu form button:hover {
            text-decoration: underline;
            color: #d1eaff;
        }

        /* Container */
        .container {
            max-width: 1024px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        /* Form fields */
        input[type="email"],
        input[type="password"],
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Alert message */
        .alert {
            background-color: #f8d7da;
            color: #842029;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c2c7;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f1f1f1;
        }

        @media screen and (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar .menu {
                flex-direction: column;
                width: 100%;
                margin-top: 10px;
            }

            .navbar .menu a,
            .navbar .menu form button {
                margin: 5px 0;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

{{-- Navigation Bar --}}
<div class="navbar">
    <div class="logo">Allowance System</div>
    <div class="menu">
        @auth('customer')
            <a href="{{ route('customer.info') }}">Profile</a>
            <a href="{{ route('customer.request.form') }}">Request Allowance</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </div>
</div>

{{-- Main Content --}}
<div class="container">
    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="alert" style="background-color: #d1e7dd; color: #0f5132; border-color: #badbcc;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Thông báo lỗi --}}
    @if(session('error'))
        <div class="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="alert">
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => el.remove());
    }, 5000);
</script>

</body>
</html>
