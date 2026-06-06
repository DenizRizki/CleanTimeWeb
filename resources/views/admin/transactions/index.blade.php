<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800">Riwayat Transaksi</h2>
            <p class="text-gray-400 text-sm mt-1">Pantau semua pesanan laundry yang masuk hari ini.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form id="bulk-delete-transactions-form" action="{{ route('admin.transactions.bulkDelete') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
                <input type="hidden" name="selected_ids" id="selected-transactions-ids">
            </form>

            <button type="button" id="btn-submit-bulk-delete" class="hidden bg-rose-50 text-rose-600 px-5 py-3 rounded-2xl font-bold text-xs border border-rose-200 hover:bg-rose-100 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a3 3 0 003 3h10M4 7h16"></path>
                </svg>
                Hapus (<span id="transactions-delete-count">0</span>) Transaksi
            </button>

            <a href="{{ route('admin.transactions.create') }}" 
               class="bg-slate-800 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-slate-700 transition flex items-center gap-2 group">
                <svg class="w-5 h-5 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Transaksi Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-slate-800 text-white text-sm rounded-2xl shadow-md border-l-4 border-emerald-400 flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-6">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="relative max-w-sm">
            <input type="text" name="search" placeholder="Cari invoice atau pelanggan..." 
                   value="{{ request('search') }}"
                   class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-slate-800 outline-none shadow-sm transition-all">
            <svg class="w-5 h-5 absolute left-3 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </form>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden border border-gray-50">
        <table class="w-full text-left align-middle">
            <thead>
                <tr class="bg-[#F9FAFB] text-gray-400 text-[11px] uppercase tracking-widest font-bold border-b border-slate-100">
                    <th class="py-5 px-6 text-center w-12">
                        <input type="checkbox" id="select-all-transactions" class="w-4 h-4 text-slate-800 border-slate-300 rounded focus:ring-slate-500">
                    </th>
                    <th class="py-5 px-4">Invoice</th>
                    <th class="py-5 px-6">Pelanggan</th>
                    <th class="py-5 px-6">Detail Cucian</th>
                    <th class="py-5 px-6">Total Tagihan</th>
                    <th class="py-5 px-6 text-center">Status</th>
                    <th class="py-5 px-6 text-right w-1/4">Manajemen Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-slate-700">
                @forelse($transactions as $trx)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-5 px-6 text-center">
                        <input type="checkbox" value="{{ $trx->id }}" class="transaction-checkbox w-4 h-4 text-slate-800 border-slate-300 rounded focus:ring-slate-500">
                    </td>
                    
                    <td class="py-5 px-4">
                        <span class="text-sm font-bold text-slate-800">{{ $trx->invoice_code }}</span>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                    </td>
                    
                    <td class="py-5 px-6">
                        <p class="text-sm font-bold text-slate-700">{{ $trx->customer->user->name ?? ($trx->customer->name ?? 'Pelanggan Umum') }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5 uppercase font-medium">{{ $trx->payment_method }}</p>
                    </td>
                    
                    <td class="py-5 px-6">
                        <div class="flex flex-col gap-1.5 max-w-xs">
                            @php
                                $items = $trx->items ?? collect([]);
                                $limit = 2; 
                            @endphp

                            @foreach($items->take($limit) as $item)
                                <div class="text-[11px] text-slate-600 flex items-center justify-between bg-slate-50 border border-slate-100 rounded-lg px-2.5 py-1.5 w-full">
                                    <span class="truncate font-medium pr-2">{{ $item->service->service_name ?? 'Layanan' }}</span>
                                    <span class="font-bold text-slate-800 shrink-0 text-[10px] bg-slate-200/60 px-1.5 py-0.5 rounded">
                                        {{ floatval($item->quantity) }} {{ $item->service->unit ?? '' }}
                                    </span>
                                </div>
                            @endforeach

                            @if($items->count() > $limit)
                                <div class="text-[10px] text-slate-400 italic pl-1 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    +{{ $items->count() - $limit }} layanan lainnya dalam order ini
                                </div>
                            @endif
                        </div>
                    </td>
                    
                    <td class="py-5 px-6">
                        <span class="text-sm font-black text-slate-800">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</span>
                    </td>
                    
                    <td class="py-5 px-6 text-center">
                        <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full inline-block
                            @if($trx->status == 'antrian') bg-amber-50 text-amber-600 border border-amber-200
                            @elseif($trx->status == 'dicuci') bg-blue-50 text-blue-600 border border-blue-200
                            @elseif($trx->status == 'disetrika') bg-indigo-50 text-indigo-600 border border-indigo-200
                            @elseif($trx->status == 'siap diambil') bg-emerald-50 text-emerald-600 border border-emerald-200
                            @elseif($trx->status == 'diambil') bg-slate-100 text-slate-500 border border-slate-300
                            @else bg-slate-50 text-slate-600 @endif">
                            {{ $trx->status }}
                        </span>
                    </td>
                    
                    <td class="py-5 px-6">
                        <div class="flex items-center justify-end gap-2.5">
                            {{-- Form Update Status --}}
                            <form action="{{ route('admin.transactions.update-status', $trx->id) }}" method="POST" class="inline-block">
                                @csrf 
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-2 py-1.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 cursor-pointer">
                                    <option value="antrian" {{ $trx->status == 'antrian' ? 'selected' : '' }}>Antrian</option>
                                    <option value="dicuci" {{ $trx->status == 'dicuci' ? 'selected' : '' }}>Sedang Dicuci</option>
                                    <option value="disetrika" {{ $trx->status == 'disetrika' ? 'selected' : '' }}>Sedang Disetrika</option>
                                    <option value="siap diambil" {{ $trx->status == 'siap diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                    <option value="diambil" {{ $trx->status == 'diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                                </select>
                            </form>

                            {{-- Tombol Detail --}}
                            <a href="{{ route('admin.transactions.show', $trx->id) }}" 
                               class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-xl transition inline-block" 
                               title="Lihat Detail & Foto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>

                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.transactions.edit', $trx->id) }}" 
                               class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-indigo-600 rounded-xl transition inline-block" 
                               title="Ubah Data">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            {{-- Tombol Hapus Tunggal --}}
                            <button type="button" 
                                    class="p-2 bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 rounded-xl transition inline-block btn-single-delete" 
                                    data-id="{{ $trx->id }}"
                                    data-invoice="{{ $trx->invoice_code }}"
                                    title="Hapus Transaksi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a3 3 0 003 3h10M4 7h16"></path>
                                </svg>
                            </button>

                            <form id="delete-form-{{ $trx->id }}" action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST" class="hidden">
                                @csrf 
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-400 text-sm italic">Tidak ada data transaksi hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('select-all-transactions');
            const checkboxes = document.querySelectorAll('.transaction-checkbox');
            const form = document.getElementById('bulk-delete-transactions-form');
            const btnSubmitBulk = document.getElementById('btn-submit-bulk-delete');
            const countSpan = document.getElementById('transactions-delete-count');
            const hiddenInput = document.getElementById('selected-transactions-ids');

            function updateVisibility() {
                const checked = document.querySelectorAll('.transaction-checkbox:checked');
                if (checked.length > 0) {
                    const ids = Array.from(checked).map(cb => cb.value);
                    hiddenInput.value = ids.join(',');
                    countSpan.textContent = checked.length;
                    btnSubmitBulk.classList.remove('hidden');
                } else {
                    btnSubmitBulk.classList.add('hidden');
                    hiddenInput.value = '';
                }
            }

            if(selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateVisibility();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    if (!this.checked && selectAll) selectAll.checked = false;
                    if (document.querySelectorAll('.transaction-checkbox:checked').length === checkboxes.length && selectAll) {
                        selectAll.checked = true;
                    }
                    updateVisibility();
                });
            });

            if (btnSubmitBulk) {
                btnSubmitBulk.addEventListener('click', function () {
                    const count = countSpan.textContent;
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: `Sebanyak ${count} data transaksi yang dipilih akan dihapus permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f43f5e',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus Semua!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }

            document.querySelectorAll('.btn-single-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const invoice = this.getAttribute('data-invoice');

                    Swal.fire({
                        title: 'Hapus Transaksi?',
                        text: `Apakah Anda yakin ingin menghapus invoice ${invoice} secara permanen?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f43f5e',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${id}`).submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>