<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        {{-- Flash Success --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-semibold shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Top Bar --}}
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Detail Invoice</h2>
                    <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full border
                        @if($transaction->status == 'antrian') bg-amber-50 text-amber-600 border-amber-200
                        @elseif($transaction->status == 'dicuci') bg-indigo-50 text-indigo-600 border-indigo-200
                        @elseif($transaction->status == 'disetrika') bg-sky-50 text-sky-600 border-sky-200
                        @elseif($transaction->status == 'siap diambil') bg-teal-50 text-teal-600 border-teal-200
                        @elseif($transaction->status == 'diambil') bg-emerald-50 text-emerald-600 border-emerald-200
                        @else bg-slate-100 text-slate-600 border-slate-200 @endif">
                        {{ $transaction->status }}
                    </span>
                </div>
                <p class="text-slate-400 text-xs mt-1">
                    Kode Nota:
                    <span class="font-mono font-bold text-indigo-600 bg-indigo-50/60 px-2 py-0.5 rounded-md text-sm ml-1">
                        {{ $transaction->invoice_code }}
                    </span>
                </p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a href="{{ route('admin.transactions.index') }}"
                   class="bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>

                <form id="delete-transaction-form" action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="document.getElementById('modal-delete').classList.remove('hidden')"
                            class="bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Nota
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">

            {{-- KOLOM KIRI --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- Profil Pelanggan --}}
                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profil Pelanggan
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Nama Pelanggan</p>
                            <p class="text-base font-extrabold text-slate-800 mt-0.5">
                                {{ $transaction->customer->user->name ?? ($transaction->customer->name ?? 'Guest') }}
                            </p>
                            <p class="text-xs text-slate-500 font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $transaction->customer->phone ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Waktu Masuk Sistem</p>
                            <p class="text-sm font-bold text-slate-800 mt-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $transaction->created_at->format('d M Y') }}
                                <span class="text-xs text-slate-400 font-normal">({{ $transaction->created_at->format('H:i') }} WIB)</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Rincian Paket Cucian --}}
                <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Rincian Paket Cucian
                    </h3>

                    <div class="overflow-x-auto rounded-2xl border border-slate-100 max-h-[320px] overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead class="sticky top-0 z-10 bg-slate-50">
                                <tr class="text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-slate-100">
                                    <th class="py-4 px-4 bg-slate-50">Layanan / Paket</th>
                                    <th class="py-4 px-4 text-center bg-slate-50">Jumlah</th>
                                    <th class="py-4 px-4 text-right bg-slate-50">Harga/Unit</th>
                                    <th class="py-4 px-4 text-right bg-slate-50">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-slate-700 font-medium">
                                @forelse($transaction->transactionDetails as $detail)
                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                        <td class="py-4 px-4 font-bold text-slate-800">
                                            {{ $detail->service->service_name ?? 'Layanan Laundry' }}
                                        </td>
                                        <td class="py-4 px-4 text-center font-extrabold text-slate-800">
                                            {{ $detail->quantity }}
                                            <span class="text-[10px] text-slate-400 font-bold uppercase ml-0.5">
                                                {{ $detail->service->unit ?? '' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-right text-slate-500 font-mono text-xs">
                                            Rp {{ number_format($detail->price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-4 text-right font-black text-slate-900 font-mono text-sm">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-10 text-center text-slate-400 italic text-sm">
                                            Tidak ada detail item pada transaksi ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($transaction->transactionDetails->count() > 0)
                                <tfoot>
                                    <tr class="bg-slate-50 border-t-2 border-slate-200">
                                        <td colspan="3" class="py-3 px-4 text-right text-xs font-black uppercase tracking-wider text-slate-500">Grand Total</td>
                                        <td class="py-3 px-4 text-right font-black text-emerald-600 font-mono text-sm">
                                            Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── KANAN ── --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- Kondisi Fisik Pakaian --}}
                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Kondisi Fisik Pakaian
                    </h3>

                    @if($transaction->initial_clothes_condition)
                        <div class="relative group rounded-2xl overflow-hidden border border-slate-100 bg-slate-50 shadow-inner">
                            <img src="{{ asset('storage/' . $transaction->initial_clothes_condition) }}"
                                 alt="Kondisi Cucian" class="w-full h-44 object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                                <a href="{{ asset('storage/' . $transaction->initial_clothes_condition) }}" target="_blank"
                                   class="bg-white text-slate-800 text-[11px] font-bold px-3 py-2 rounded-xl shadow-md hover:scale-105 transition-transform flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Buka Foto Penuh
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center bg-slate-50">
                            <p class="text-xs text-slate-400 font-medium">Belum ada foto kondisi pakaian.</p>
                        </div>
                    @endif

                    @if($transaction->status !== 'diambil')
                        <div class="mt-4 pt-4 border-t border-slate-50">
                            <form action="{{ route('admin.transactions.update-photo', $transaction->id) }}"
                                  method="POST" enctype="multipart/form-data" class="space-y-2">
                                @csrf
                                @method('PATCH')
                                <label class="block text-[11px] font-bold text-slate-500 uppercase">Ganti / Unggah Foto:</label>
                                <div class="flex items-center gap-2">
                                    <input type="file" name="initial_clothes_condition" accept="image/*" required
                                           class="flex-1 text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer">
                                    <button type="submit"
                                            class="bg-slate-800 hover:bg-slate-900 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all flex items-center gap-1 shadow-sm flex-shrink-0">
                                        Upload
                                    </button>
                                </div>
                                @error('initial_clothes_condition')
                                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </form>
                        </div>
                    @else
                        <div class="mt-3 p-2 bg-slate-50 rounded-xl border border-slate-200/60 flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Foto dikunci (Selesai Transaksi)</p>
                        </div>
                    @endif
                </div>

                {{-- Status Pengerjaan --}}
                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Status Pengerjaan Laundry
                    </h3>
                    <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-2">
                            <select name="status" class="flex-1 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2 px-3 text-xs outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer font-semibold transition">
                                <option value="antrian"      {{ $transaction->status == 'antrian'      ? 'selected' : '' }}>Antrian</option>
                                <option value="dicuci"       {{ $transaction->status == 'dicuci'       ? 'selected' : '' }}>Dicuci</option>
                                <option value="disetrika"    {{ $transaction->status == 'disetrika'    ? 'selected' : '' }}>Disetrika</option>
                                <option value="siap diambil" {{ $transaction->status == 'siap diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                <option value="diambil"      {{ $transaction->status == 'diambil'      ? 'selected' : '' }}>Sudah Diambil</option>
                            </select>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all shadow-md shadow-indigo-100 flex-shrink-0">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Administrasi Keuangan --}}
                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Administrasi Keuangan
                    </h3>

                    {{-- Form Status Pembayaran --}}
                    <form action="{{ route('admin.transactions.update-payment', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-3">
                            <div class="flex gap-2">
                                <select name="payment_status"
                                        class="flex-1 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2 px-3 text-xs outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer font-semibold transition">
                                    <option value="pending" {{ $transaction->payment_status == 'pending' ? 'selected' : '' }}>Belum Bayar (Pending)</option>
                                    <option value="paid"    {{ $transaction->payment_status == 'paid'    ? 'selected' : '' }}>Lunas (Paid)</option>
                                </select>
                                <button type="submit"
                                        class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all shadow-md shadow-emerald-100 flex-shrink-0">
                                    Update Payment
                                </button>
                            </div>

                            <div class="text-[11px] text-slate-500 space-y-1.5 bg-slate-50 p-3 rounded-xl border border-slate-100 font-medium">
                                <div class="flex justify-between">
                                    <span>Metode Pembayaran:</span>
                                    <span class="text-slate-800 font-bold uppercase tracking-wider">
                                        {{ $transaction->payment_method ?? 'CASH' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Waktu Pembayaran:</span>
                                    <span class="text-slate-800 font-mono font-semibold">
                                        {{ $transaction->paid_at ? $transaction->paid_at->format('d/m/Y H:i') : '-' }}
                                    </span>
                                </div>

                                <div class="pt-2 border-t border-slate-200/60 space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span>Bukti Transfer:</span>
                                        @if($transaction->transfer_proof)
                                            <button type="button" onclick="document.getElementById('modal-bukti').classList.remove('hidden')"
                                                    class="flex items-center gap-1 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg transition-all shadow-sm">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat Bukti
                                            </button>
                                        @else
                                            <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2.5 py-1 rounded-lg">
                                                Belum Diupload
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Form Bukti Transfer --}}
                    @if($transaction->payment_method === 'transfer' && $transaction->payment_status === 'pending' && !$transaction->transfer_proof)
                        <form action="{{ route('admin.transactions.update-transfer-proof', $transaction->id) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="pt-3">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center gap-2 bg-slate-50 p-1.5 rounded-xl border border-slate-200">
                                <input type="file" name="transfer_proof" accept="image/*" required
                                       class="flex-1 text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer">
                                <button type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-3 py-1.5 rounded-lg text-[10px] transition-all flex-shrink-0 shadow-sm">
                                    Upload
                                </button>
                            </div>
                            @error('transfer_proof')
                                <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </form>
                    @endif

                    {{-- Tombol Struk --}}
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <button type="button" onclick="cetakStruk()"
                                class="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-all flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Cetak Struk Transaksi
                        </button>
                    </div>
                </div>

            </div> 

        </div>
    </div>

    <div id="modal-bukti" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl relative">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-black text-slate-800 text-sm tracking-tight">Bukti Pembayaran Transfer</h4>
                <button type="button" onclick="document.getElementById('modal-bukti').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            @if($transaction->transfer_proof)
                <img src="{{ asset('storage/' . $transaction->transfer_proof) }}" alt="Bukti Transfer"
                     class="w-full h-auto max-h-[60vh] object-contain rounded-2xl border border-slate-100">
                <div class="mt-4 flex justify-end">
                    <a href="{{ asset('storage/' . $transaction->transfer_proof) }}" target="_blank"
                       class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2 rounded-xl transition">
                        Buka Tab Baru
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div id="modal-delete" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl relative">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mb-4 shadow-inner">
                    <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h4 class="font-black text-slate-800 text-lg tracking-tight mb-2">Hapus Transaksi?</h4>
                <p class="text-sm text-slate-500 font-medium">
                    Tindakan ini tidak dapat dibatalkan. Semua data terkait
                    <span class="font-bold text-slate-700">Nota {{ $transaction->invoice_code }}</span>
                    akan dihapus permanen.
                </p>
            </div>
            <div class="flex gap-3 w-full">
                <button type="button"
                        onclick="document.getElementById('modal-delete').classList.add('hidden')"
                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2.5 rounded-xl text-sm transition-all">
                    Batal
                </button>
                <button type="button"
                        onclick="document.getElementById('delete-transaction-form').submit()"
                        class="flex-1 bg-rose-600 hover:bg-rose-500 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-rose-200">
                    Ya, Hapus Data
                </button>
            </div>
        </div>
    </div>

    <div id="struk-print">
        <div class="struk-wrapper">
            {{-- ── Header Toko ── --}}
            <div class="struk-header">
                <div class="struk-logo">✿</div>
                <div class="struk-toko-nama">LAUNDRY BERSIH</div>
                <div class="struk-toko-tagline">Bersih &middot; Wangi &middot; Tepat Waktu</div>
                <div class="struk-toko-kontak">Jl. Contoh No. 123 &bull; Telp: 0812-3456-7890</div>
            </div>

            <div class="struk-sep-dash"></div>

            {{-- ── Info Nota ── --}}
            <div class="struk-row">
                <span class="struk-lbl">No. Nota</span>
                <span class="struk-val">{{ $transaction->invoice_code }}</span>
            </div>
            <div class="struk-row">
                <span class="struk-lbl">Tanggal</span>
                <span class="struk-val">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="struk-row">
                <span class="struk-lbl">Pelanggan</span>
                <span class="struk-val">{{ $transaction->customer->user->name ?? ($transaction->customer->name ?? 'Guest') }}</span>
            </div>
            <div class="struk-row">
                <span class="struk-lbl">No. HP</span>
                <span class="struk-val">{{ $transaction->customer->phone ?? '-' }}</span>
            </div>

            <div class="struk-sep-dash"></div>

            {{-- ── Daftar Item Layanan ── --}}
            <table class="struk-table">
                <thead>
                    <tr>
                        <th class="text-left">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Sub</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaction->transactionDetails as $detail)
                        <tr>
                            <td>{{ $detail->service->service_name ?? 'Laundry' }}</td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-right">{{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada item</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="struk-sep-dash"></div>

            {{-- ── Total & Pembayaran ── --}}
            <div class="struk-row struk-bold">
                <span class="struk-lbl">GRAND TOTAL</span>
                <span class="struk-val">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="struk-row">
                <span class="struk-lbl">Status Pembayaran</span>
                <span class="struk-val">
                    {{ $transaction->payment_status == 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                </span>
            </div>

            <div class="struk-sep-dash"></div>

            {{-- ── Footer ── --}}
            <div class="struk-footer">
                <p>Terima Kasih!</p>
                <p>Harap periksa kembali cucian Anda.</p>
                <p>Barang yang tidak diambil lewat 30 hari<br>bukan tanggung jawab kami.</p>
            </div>
        </div>
    </div>

    <style>
        #struk-print {
            display: none;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            
            #struk-print, #struk-print * {
                visibility: visible;
            }
            
            #struk-print {
                display: block;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white;
                padding-top: 10px;
            }

            .struk-wrapper {
                width: 58mm; 
                margin: 0 auto;
                font-family: 'Courier New', Courier, monospace;
                font-size: 11px;
                color: #000;
                line-height: 1.3;
            }

            .struk-header { text-align: center; margin-bottom: 8px; }
            .struk-logo { font-size: 20px; }
            .struk-toko-nama { font-weight: bold; font-size: 14px; margin-bottom: 2px;}
            .struk-toko-tagline, .struk-toko-kontak { font-size: 9px; }
            
            .struk-sep-dash { border-top: 1px dashed #000; margin: 8px 0; }
            .struk-row { display: flex; justify-content: space-between; margin-bottom: 4px; }
            .struk-bold { font-weight: bold; }
            
            .struk-table { width: 100%; border-collapse: collapse; }
            .struk-table th, .struk-table td { padding: 4px 0; font-size: 10px; }
            .struk-table th { border-bottom: 1px dashed #000; font-weight: bold; }
            .struk-table .text-left { text-align: left; }
            .struk-table .text-center { text-align: center; }
            .struk-table .text-right { text-align: right; }
            
            .struk-footer { text-align: center; margin-top: 10px; font-size: 9px; }
            @page {
                margin: 0;
            }
        }
    </style>

    <script>
        function cetakStruk() {
            window.print();
        }
    </script>

</x-app-layout>