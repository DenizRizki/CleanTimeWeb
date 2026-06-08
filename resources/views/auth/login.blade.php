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
<body class="antialiased bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-slate-700 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[500px] h-[500px] bg-slate-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        
        <div class="mb-8 text-center flex flex-col items-center">
            <a href="/" class="group flex items-center gap-3 mb-4 focus:outline-none">
                <div class="w-12 h-12 bg-white text-slate-900 rounded-2xl flex items-center justify-center shadow-2xl shadow-indigo-500/10 group-hover:scale-105 transition-transform border border-slate-100/10">
                    <span class="font-black text-2xl tracking-tighter">C</span>
                </div>
                <div class="text-left">
                    <h1 class="text-xl font-extrabold text-white tracking-tight leading-none">CleanTime</h1>
                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mt-1">Workspace</p>
                </div>
            </a>
        </div>

        <div class="bg-white/95 backdrop-blur-md rounded-[2.5rem] p-8 border border-white/20 shadow-2xl shadow-slate-950/50 relative overflow-hidden">
            
            <div class="mb-6">
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
                <p class="text-slate-400 text-xs mt-1 font-medium">Silakan masukkan akun admin Anda untuk mengelola kasir.</p>
            </div>

            @if (session('status'))
                <div class="mb-4 bg-emerald-50 border border-emerald-100 text-emerald-700 p-3.5 rounded-xl text-xs font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Alamat Email</label>
                    <div class="relative">
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="admin@cleantime.com"
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-4 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all" />
                    </div>
                    @if($errors->has('email'))
                        <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    
                    <div class="relative">
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               placeholder="••••••••"
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 pl-4 pr-12 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all" />
                        
                        <button type="button" id="toggle-password" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none p-1">
                            <svg id="eye-icon-show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-icon-hide" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    
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
                        <span class="ms-2.5 text-xs font-bold text-slate-400 group-hover:text-slate-500 transition-colors">{{ __('Ingat Sesi Saya') }}</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-4 px-6 rounded-2xl shadow-xl shadow-indigo-600/10 hover:shadow-indigo-600/20 transition-all active:scale-[0.98] focus:outline-none">
                        Masuk Sistem Kasir
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center mt-6">
            <a href="/" class="text-xs font-bold text-slate-400 hover:text-slate-300 transition-colors">
                ← Kembali ke Halaman Utama
            </a>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-password');
            const iconShow = document.getElementById('eye-icon-show');
            const iconHide = document.getElementById('eye-icon-hide');

            if (toggleButton && passwordInput) {
                toggleButton.addEventListener('click', function () {
                    // Cek tipe input saat ini
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        iconShow.classList.add('hidden');
                        iconHide.classList.remove('hidden');
                    } else {
                        passwordInput.type = 'password';
                        iconShow.classList.remove('hidden');
                        iconHide.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>