<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login E-HIBAH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-blue-600">E-HIBAH BBWS</h1>
            <p class="text-gray-500">Masuk untuk melanjutkan</p>
        </div>

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" name="username" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            @if($errors->any())
                <p class="text-red-500 text-xs italic mb-4">{{ $errors->first() }}</p>
            @endif

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition">
                LOGIN
            </button>
        </form>
    </div>
</body>
</html>