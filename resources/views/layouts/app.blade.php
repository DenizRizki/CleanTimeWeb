<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Clean Time') }}</title>
    
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
<body class="antialiased bg-clean-bg text-gray-700">

@php
    $processCount = \App\Models\Transaction::whereIn('status', ['antrian', 'dicuci'])->count(); 
    if ($processCount >= 1 && $processCount <= 3) {
        $bgCard    = 'bg-emerald-50/60 border-emerald-100 text-emerald-900';
        $bgIcon    = 'bg-emerald-500';
        $textMuted = 'text-emerald-600/90';
    } elseif ($processCount >= 4 && $processCount <= 8) {
        $bgCard    = 'bg-amber-50/60 border-amber-100 text-amber-900';
        $bgIcon    = 'bg-amber-500';
        $textMuted = 'text-amber-600/90';
    } else {
        $bgCard    = 'bg-rose-50/60 border-rose-100 text-rose-900';
        $bgIcon    = 'bg-rose-500';
        $textMuted = 'text-rose-600/90';
    }
@endphp

    <div class="flex min-h-screen">
        <aside class="w-72 bg-white border-r border-gray-100 hidden md:flex flex-col justify-between fixed top-0 left-0 h-screen p-6 z-30">
            <div class="flex flex-col">
                <div class="pb-8 pt-2 px-2 flex items-center gap-3">
                    <div class="w-10 h-10 bg-clean-dark rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-clean-bright font-bold text-xl">C</span>
                    </div>
                    <h1 class="text-xl font-extrabold text-clean-dark tracking-tight">CleanTime</h1>
                </div>

                <nav class="space-y-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-4 mb-3">Main Menu</p>
                    
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-slate-50 text-slate-800' : 'text-gray-400 hover:text-slate-700 hover:bg-slate-50/50' }}">
                        <div class="p-2 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white shadow-md' : 'bg-transparent text-gray-400 group-hover:text-slate-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                            </svg>
                        </div>
                        <span class="transform transition-transform duration-200 group-hover:translate-x-0.5">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.transactions.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all duration-200 group {{ request()->routeIs('admin.transactions.*') ? 'bg-slate-50 text-slate-800' : 'text-gray-400 hover:text-slate-700 hover:bg-slate-50/50' }}">
                        <div class="p-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.transactions.*') ? 'bg-slate-900 text-white shadow-md' : 'bg-transparent text-gray-400 group-hover:text-slate-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="transform transition-transform duration-200 group-hover:translate-x-0.5">Kelola Laundry</span>
                    </a>

                    <a href="{{ route('admin.customers.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all duration-200 group {{ request()->routeIs('admin.customers.*') ? 'bg-slate-50 text-slate-800' : 'text-gray-400 hover:text-slate-700 hover:bg-slate-50/50' }}">
                        <div class="p-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.customers.*') ? 'bg-slate-900 text-white shadow-md' : 'bg-transparent text-gray-400 group-hover:text-slate-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="transform transition-transform duration-200 group-hover:translate-x-0.5">Data Pelanggan</span>
                    </a>

                    <a href="{{ route('admin.services.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all duration-200 group {{ request()->routeIs('admin.services.*') ? 'bg-slate-50 text-slate-800' : 'text-gray-400 hover:text-slate-700 hover:bg-slate-50/50' }}">
                        <div class="p-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.services.*') ? 'bg-slate-900 text-white shadow-md' : 'bg-transparent text-gray-400 group-hover:text-slate-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2-2 4 4m0-7l3 3m-3-3l-3 3M3 21h18M3 10h18M3 7h18M3 4h18"></path>
                            </svg>
                        </div>
                        <span class="transform transition-transform duration-200 group-hover:translate-x-0.5">Layanan & Harga</span>
                    </a>
                </nav>
            </div>

            <div class="space-y-4 w-full block">
                
                @if($processCount > 0)
                <div class="border rounded-2xl p-4 flex items-start gap-3 transition-colors duration-300 {{ $bgCard }}">
                    <div class="p-2 rounded-xl text-white shadow-sm flex-shrink-0 transition-colors duration-300 {{ $bgIcon }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold">{{ $processCount }} Cucian Diproses</h4>
                        <p class="text-[10px] mt-0.5 leading-relaxed {{ $textMuted }}">
                            @if($processCount <= 3)
                                Beban kerja santai. Antrean terpantau aman terkendali.
                            @elseif($processCount <= 8)
                                Toko mulai ramai. Monitor penyelesaian tepat waktu.
                            @else
                                Antrean menumpuk! Prioritaskan cucian yang hampir tenggat.
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                <div class="bg-clean-bg rounded-2xl p-4 border border-gray-100 w-full block">
                    <p class="text-xs font-semibold text-clean-dark truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500">Administrator</p>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3 w-full block">
                        @csrf
                        <button type="submit" class="text-[11px] font-bold text-red-400 hover:text-red-600 uppercase tracking-wider block focus:outline-none">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col md:pl-72 min-h-screen">
            
            <header class="h-20 flex items-center justify-between px-10">
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                    <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">
                        Dashboard
                    </a>

                    @if(request()->segment(2))
                        <span class="text-slate-300 font-normal">/</span>
                        <span class="{{ request()->segment(3) ? 'text-slate-400 font-medium' : 'text-indigo-600 font-bold' }}">
                            @switch(request()->segment(2))
                                @case('transactions') Kelola Laundry @break
                                @case('customers') Data Pelanggan @break
                                @case('services') Layanan & Harga @break
                                @default {{ ucfirst(request()->segment(2)) }}
                            @endswitch
                        </span>
                    @endif

                    @if(request()->segment(3))
                        <span class="text-slate-300 font-normal">/</span>
                        <span class="text-indigo-600 font-bold">
                            @switch(request()->segment(3))
                                @case('create') Tambah Baru @break
                                @case('edit') Ubah Data @break
                                @default {{ ucfirst(request()->segment(3)) }}
                            @endswitch
                        </span>
                    @endif
                </div>
            </header>

            <main class="px-10 pb-10">
                {{ $slot }}
            </main>
        </div>
    </div>

    <button type="button" id="layout-modal-fab" 
            class="fixed bottom-6 right-6 z-50 bg-slate-900 hover:bg-slate-800 text-white p-4 rounded-full shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95 focus:outline-none">
        <svg id="layout-modal-icon" class="w-7 h-7 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
        </svg>
    </button>

    <div id="layout-modal-overlay" 
         class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
        
        <div id="layout-modal-box" 
             class="bg-white rounded-[2.5rem] w-full max-w-md p-6 shadow-2xl transform scale-90 opacity-0 transition-all duration-300 ease-out">
            
            <div class="mb-5 text-center">
                <div class="w-12 h-12 bg-slate-100 text-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-800">Menu Input Cepat</h3>
                <p class="text-xs text-slate-400 mt-0.5">Pilih modul data baru yang ingin dibuat</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <a href="{{ route('admin.transactions.create') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:bg-slate-100 transition-all text-center group">
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 leading-tight">Nota Transaksi</span>
                </a>

                <a href="{{ route('admin.customers.create') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:bg-slate-100 transition-all text-center group">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 leading-tight">Data Pelanggan</span>
                </a>

                <a href="{{ route('admin.services.create') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:bg-slate-100 transition-all text-center group">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2-2 4 4m0-7l3 3m-3-3l-3 3M3 21h18M3 10h18M3 7h18M3 4h18"></path>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 leading-tight">Layanan Baru</span>
                </a>
            </div>

            <button type="button" id="layout-modal-close" 
                    class="w-full text-center text-xs font-bold text-slate-400 hover:text-slate-600 transition py-2 mt-5 focus:outline-none">
                Kembali ke Aplikasi
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fabBtn = document.getElementById('layout-modal-fab');
            const fabIcon = document.getElementById('layout-modal-icon');
            const overlay = document.getElementById('layout-modal-overlay');
            const modalBox = document.getElementById('layout-modal-box');
            const closeBtn = document.getElementById('layout-modal-close');

            let isOpen = false;

            function openModal() {
                isOpen = true;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                
                setTimeout(() => {
                    overlay.classList.add('opacity-100');
                    modalBox.classList.remove('scale-90', 'opacity-0');
                    modalBox.classList.add('scale-100', 'opacity-100');
                }, 10);
                
                fabIcon.classList.add('rotate-45');
                fabBtn.classList.remove('bg-slate-900');
                fabBtn.classList.add('bg-rose-600');
            }

            function closeModal() {
                isOpen = false;
                modalBox.classList.remove('scale-100', 'opacity-100');
                modalBox.classList.add('scale-90', 'opacity-0');
                overlay.classList.remove('opacity-100');
                
                setTimeout(() => {
                    overlay.classList.remove('flex');
                    overlay.classList.add('hidden');
                }, 300);
                
                fabIcon.classList.remove('rotate-45');
                fabBtn.classList.remove('bg-rose-600');
                fabBtn.classList.add('bg-slate-900');
            }

            fabBtn.addEventListener('click', function () {
                if (isOpen) closeModal();
                else openModal();
            });

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) closeModal();
            });

            closeBtn.addEventListener('click', closeModal);
        });
    </script>
</body>
</html>