<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Administrator - Clean Time</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
</style>
<body class="antialiased bg-[#FAFBFD] text-slate-700 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        
        <div class="mb-8 text-center flex flex-col items-center">
            <a href="/" class="group flex items-center gap-3 mb-4 focus:outline-none">
                <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center shadow-xl group-hover:scale-105 transition-transform">
                    <span class="text-white font-bold text-2xl">C</span>
                </div>
                <div class="text-left">
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight leading-none">CleanTime</h1>
                    <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mt-1">Workspace</p>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-100/50 relative overflow-hidden">
            
            <div class="mb-6">
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
                <p class="text-slate-400 text-xs mt-1">Silakan masukkan akun admin Anda untuk mengelola kasir.</p>
            </div>

            @if (session('status'))
                <div class="mb-4 bg-emerald-50 border border-emerald-100 text-emerald-700 p-3.5 rounded-xl text-xs font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Alamat Email</label>
                    <div class="relative">
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="admin@cleantime.com"
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-4 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all" />
                    </div>
                    @if($errors->has('email'))
                        <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password" 
                           placeholder="••••••••"
                           class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-4 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all" />
                    
                    @if($errors->has('password'))
                        <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div class="flex items-center">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember"
                               class="rounded-lg border-slate-200 text-indigo-600 shadow-sm focus:ring-indigo-500/20 focus:ring-offset-0 w-4 h-4 cursor-pointer">
                        <span class="ms-2.5 text-xs font-bold text-slate-400 group-hover:text-slate-600 transition">{{ __('Ingat Sesi Saya') }}</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-4 px-6 rounded-2xl shadow-xl shadow-indigo-100 transition-all active:scale-[0.98] focus:outline-none">
                        Masuk Sistem Kasir
                    </button>
                </div>
            </form>
            </div>

        <div class="text-center mt-6">
            <a href="/" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition">
                ← Kembali ke Halaman Utama
            </a>
        </div>

    </div>

</body>
</html>