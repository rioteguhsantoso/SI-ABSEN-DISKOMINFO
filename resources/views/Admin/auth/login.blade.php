<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - SI-ABSEN DISKOMINFO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-7">
            <div class="text-center mb-7">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-900 text-white flex items-center justify-center mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4Zm-7 9a7 7 0 0 1 14 0" />
                    </svg>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900">Administrator</h1>
                <p class="text-sm text-slate-500 mt-1">SI-ABSEN DISKOMINFO</p>
            </div>
            @if($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 border border-red-100 px-4 py-3">
                    <p class="text-xs font-semibold text-red-600">{{ $errors->first() }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-xl bg-red-50 border border-red-100 px-4 py-3">
                    <p class="text-xs font-semibold text-red-600">{{ session('error') }}</p>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.login.authenticate') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email administrator"
                        required
                        autofocus
                        class="w-full h-11 rounded-xl border border-slate-200 px-3 text-sm text-slate-800 outline-none focus:border-slate-400 focus:ring-0"
                    >
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                        class="w-full h-11 rounded-xl border border-slate-200 px-3 text-sm text-slate-800 outline-none focus:border-slate-400 focus:ring-0"
                    >
                </div>
                <button
                    type="submit"
                    class="w-full h-11 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition"
                >
                    Masuk sebagai Administrator
                </button>
            </form>
        </div>
        <p class="text-center text-xs text-slate-400 mt-5">
            SI-ABSEN DISKOMINFO
        </p>
    </div>
</body>
</html>