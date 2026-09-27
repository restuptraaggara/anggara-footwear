<!DOCTYPE html>
<html lang="id" class="h-full bg-earth-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator — Anggara Footwear</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full flex items-center justify-center p-6 antialiased">

    <div class="w-full max-w-md">
        
        {{-- Brand --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-2xl font-bold tracking-widest uppercase text-gray-900">
                Allbirds
            </a>
            <p class="text-xs tracking-widest uppercase text-earth-600 font-semibold mt-2">Portal Manajemen Admin</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-3xl p-8 border border-earth-100 shadow-xl space-y-6">
            
            <div>
                <h2 class="text-xl font-light text-gray-900">Masuk ke Dashboard</h2>
                <p class="text-xs text-gray-400 mt-1 font-light">Gunakan kredensial administrator yang terdaftar.</p>
            </div>

            {{-- Default Credential Info Banner --}}
            <div class="bg-earth-50 rounded-2xl p-4 border border-earth-200 text-xs text-earth-800 space-y-1">
                <p class="font-semibold text-gray-900">🔑 Kredensial Default:</p>
                <p><span class="text-gray-500">Email:</span> <code class="font-mono bg-white px-1.5 py-0.5 rounded border border-earth-200">admin@allbirds.test</code></p>
                <p><span class="text-gray-500">Password:</span> <code class="font-mono bg-white px-1.5 py-0.5 rounded border border-earth-200">password123</code></p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3.5 rounded-r-xl text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3.5 rounded-r-xl text-xs">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Email Administrator</label>
                    <input type="email" name="email" value="{{ old('email', 'admin@allbirds.test') }}" required autofocus
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900 transition-colors">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700">Password</label>
                    </div>
                    <input type="password" name="password" value="password123" required
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900 transition-colors">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" checked
                           class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-earth-300 rounded">
                    <label for="remember" class="ml-2 block text-xs text-gray-600 font-light">Ingat sesi saya</label>
                </div>

                <button type="submit"
                        class="w-full bg-gray-900 text-white py-3.5 rounded-xl text-xs tracking-widest uppercase font-semibold hover:bg-earth-800 transition-all shadow-md mt-2">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="pt-4 border-t border-earth-100 text-center">
                <a href="{{ route('home') }}" class="text-xs text-earth-600 hover:text-gray-900 font-medium tracking-wide transition-colors">
                    ← Kembali ke Halaman Toko
                </a>
            </div>

        </div>

    </div>

</body>
</html>
