<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clean Time - Sistem Manajemen Laundry Modern</title>
    
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
<body class="antialiased bg-[#FAFBFD] text-slate-700 min-h-screen flex flex-col justify-between">

    <header class="w-full max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 h-24 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center shadow-md">
                <span class="text-white font-bold text-xl">C</span>
            </div>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">CleanTime</h1>
        </div>

        <div>
            @auth
                <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-800 bg-white border border-slate-200/80 py-3 px-6 rounded-2xl shadow-sm hover:bg-slate-50 transition-all">
                    Masuk Dashboard →
                </a>
            @else
                <a href="{{ route('login') }}" class="text-sm font-bold text-white bg-slate-900 py-3 px-6 rounded-2xl shadow-lg hover:bg-slate-800 transition-all active:scale-[0.98]">
                    Login Administrator
                </a>
            @endauth
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="max-w-4xl w-full text-center space-y-8">
            
            <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full px-4 py-1.5 text-xs font-bold tracking-wide uppercase mx-auto">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Sistem Siap Digunakan
            </div>

            <div class="space-y-4">
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-none">
                    Kelola Operasional Laundry <br>
                    <span class="text-indigo-600">Jauh Lebih Bersih & Presisi.</span>
                </h2>
                <p class="text-slate-400 max-w-xl mx-auto text-sm sm:text-base font-medium leading-relaxed">
                    Pantau pemasukan bulanan, monitor status pengerjaan cucian pelanggan, dan cetak invoice kasir Clean Time secara terpusat dalam satu sistem.
                </p>
            </div>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto text-center text-base font-bold text-white bg-indigo-600 py-4 px-8 rounded-2.5rem shadow-xl hover:bg-indigo-700 transition-all active:scale-[0.98]">
                        Kembali ke Dashboard Utama
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto text-center text-base font-bold text-white bg-indigo-600 py-4 px-8 rounded-[2rem] shadow-xl hover:bg-indigo-700 transition-all active:scale-[0.98] group">
                        Mulai Sesi Kerja Sekarang 
                        <span class="inline-block transform group-hover:translate-x-1 transition-transform ml-1">→</span>
                    </a>
                @endauth
            </div>

            <div class="pt-12 grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
                <div class="bg-white p-5 border border-slate-100 rounded-2xl shadow-sm text-left flex items-start gap-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Input Kilat</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-normal">Buka nota pesanan baru, hitung berat otomatis, dan cetak invoice dalam hitungan detik.</p>
                    </div>
                </div>

                <div class="bg-white p-5 border border-slate-100 rounded-2xl shadow-sm text-left flex items-start gap-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Lacak Real-Time</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-normal">Pantau status pengerjaan cucian mulai dari antrean, cuci, setrika, hingga siap diambil.</p>
                    </div>
                </div>

                <div class="bg-white p-5 border border-slate-100 rounded-2xl shadow-sm text-left flex items-start gap-4">
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Grafik Akurat</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-normal">Data pendapatan bulanan diproses otomatis untuk melihat tren keuntungan outlet.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="w-full h-16 flex items-center justify-center border-t border-slate-100 text-[11px] font-medium text-slate-400">
        &copy; {{ date('Y') }} Clean Time Management System. All Rights Reserved.
    </footer>

</body>
</html>