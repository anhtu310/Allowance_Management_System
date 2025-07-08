<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>403 - Không có quyền truy cập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="text-center">
    <h1 class="text-4xl font-bold text-red-600 mb-4">403</h1>
    <p class="text-lg text-gray-700 mb-6">Bạn không có quyền truy cập vào trang này.</p>
    <a href="{{ url()->previous() }}"
       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
    >
        ← Quay lại
    </a>
</div>
</body>
</html>
