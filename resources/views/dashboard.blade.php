<x-app-layout>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight text-white">Ringkasan Bisnis</h2>
                <p class="text-slate-400 text-sm mt-1">Selamat datang kembali! Pantau performa outlet Clean Time bulan ini.</p>
            </div>
            
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            
            {{-- Pendapatan Bulan Ini --}}
            <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm flex items-center gap-5">
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pendapatan Bulan Ini</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-0.5">
                        Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- Cucian Diproses --}}
            <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm flex items-center gap-5">
                <div class="p-4 bg-amber-50 text-amber-600 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Cucian Diproses</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-0.5">
                        {{ $activeLaundryCount }} Nota
                    </h3>
                </div>
            </div>

            {{-- Total Member --}}
            <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm flex items-center gap-5 sm:col-span-2 lg:col-span-1">
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Member</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-0.5">
                        {{ $totalCustomers }} Pelanggan
                    </h3>
                </div>
            </div>
        </div>

        {{-- GRAFIK--}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 mb-10">
            <div class="mb-4">
                <h3 class="text-lg font-extrabold text-slate-800">Grafik Omset & Transaksi Bulanan</h3>
                <p class="text-slate-400 text-xs mt-0.5">Visualisasi tren total pendapatan bulanan laundry Clean Time.</p>
            </div>
            <div class="relative w-full h-72">
                <canvas id="laundryAnalyticsChart"></canvas>
            </div>
        </div>

        {{-- Aktivitas Transaksi --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-800">Aktivitas Transaksi Terakhir</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Daftar riwayat cucian yang baru masuk ke sistem.</p>
                </div>
                <a href="{{ route('admin.transactions.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-50 py-2.5 px-4 rounded-xl transition-all">
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F9FAFB] text-slate-400 text-[11px] uppercase tracking-widest font-bold border-b border-slate-100">
                            <th class="py-4 px-4">Invoice</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Layanan / Paket</th> 
                            <th class="py-4 px-6">Total Harga</th>
                            <th class="py-4 px-6 text-center w-40">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-slate-700 text-sm">
                        @foreach($recentTransactions as $transaction)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-4">
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="font-bold text-indigo-600 hover:text-indigo-900 block transition-colors group">
                                    {{ $transaction->invoice_code }}
                                    <span class="inline-block transform group-hover:translate-x-0.5 transition-transform text-[10px]">→</span>
                                </a>
                                <span class="text-[10px] text-slate-400 block mt-0.5">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            
                            <td class="py-4 px-6 font-bold text-slate-700">
                                {{ $transaction->customer->user->name ?? 'Pelanggan Umum' }}
                            </td>
                    
                          <td class="py-4 px-6">
                        @forelse($transaction->transactionDetails as $detail)
                        <span class="inline-flex items-center text-xs text-slate-600 bg-slate-50 border border-slate-100 rounded-lg px-2.5 py-1 font-medium mb-1 mr-1">
                         {{ $detail->service->service_name ?? 'Layanan Terhapus' }}
                        <span class="ml-1 text-slate-400 font-bold">
                        ({{ floatval($detail->quantity) }} {{ $detail->service->unit ?? '' }})
                        </span>
                    </span>
                    @empty
                     <span class="text-xs text-slate-400 italic">-</span>
                 @endforelse
                            </td>
                            
                            <td class="py-4 px-6 font-black text-slate-800">
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </td>
                            
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST" class="inline-block">
                                    @csrf 
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                        class="text-xs font-bold border rounded-xl px-2.5 py-1.5 focus:outline-none focus:ring-2 cursor-pointer
                                        @if($transaction->status == 'antrian') bg-amber-50 text-amber-600 border-amber-200 focus:ring-amber-400
                                        @elseif($transaction->status == 'dicuci') bg-blue-50 text-blue-600 border-blue-200 focus:ring-blue-400
                                        @elseif($transaction->status == 'disetrika') bg-indigo-50 text-indigo-600 border-indigo-200 focus:ring-indigo-400
                                        @elseif($transaction->status == 'siap diambil') bg-emerald-50 text-emerald-600 border-emerald-200 focus:ring-emerald-400
                                        @else bg-slate-100 text-slate-600 border-slate-300 focus:ring-slate-400 @endif">
                                        
                                        <option value="antrian" {{ $transaction->status == 'antrian' ? 'selected' : '' }}>Antrian</option>
                                        <option value="dicuci" {{ $transaction->status == 'dicuci' ? 'selected' : '' }}>Sedang Dicuci</option>
                                        <option value="disetrika" {{ $transaction->status == 'disetrika' ? 'selected' : '' }}>Sedang Disetrika</option>
                                        <option value="siap diambil" {{ $transaction->status == 'siap diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                        <option value="diambil" {{ $transaction->status == 'diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const canvasElement = document.getElementById('laundryAnalyticsChart');
            if (!canvasElement) return;

            const ctx = canvasElement.getContext('2d');
            const revenueGradient = ctx.createLinearGradient(0, 0, 0, 300);
            revenueGradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
            revenueGradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            const labelBulan = {!! json_encode($months ?? []) !!};
            const dataOmset = {!! json_encode($revenues ?? []) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelBulan, 
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: dataOmset, 
                        borderColor: '#10b981', 
                        borderWidth: 3,
                        backgroundColor: revenueGradient,
                        fill: true,
                        tension: 0.35, 
                        pointBackgroundColor: '#10b981',
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 11 },
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            },
                            border: { dash: [5, 5] }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { size: 11 } }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>