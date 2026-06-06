<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-slate-800">Data Pelanggan</h2>
            <p class="text-slate-400 text-sm mt-0.5">Pantau dan kelola semua data pelanggan Clean Time.</p>
        </div>
        
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <form id="bulk-delete-customers-form" action="{{ route('admin.customers.bulkDelete') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
                <input type="hidden" name="selected_ids" id="selected-customers-ids">
                <button type="button" id="btn-submit-bulk-delete" 
                        class="bg-rose-50 text-rose-600 px-5 py-3.5 rounded-2xl font-bold text-xs border border-rose-100 hover:bg-rose-100/70 transition flex items-center gap-2 active:scale-[0.98]">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a3 3 0 003 3h10M4 7h16"></path>
                    </svg>
                    Hapus (<span id="customers-delete-count" class="font-black">0</span>) Data
                </button>
            </form>

            <a href="{{ route('admin.customers.create') }}" 
               class="bg-slate-800 text-white px-6 py-3.5 rounded-2xl font-extrabold text-xs tracking-widest uppercase shadow-lg shadow-slate-900/10 hover:bg-slate-900 transition flex items-center gap-2 group active:scale-[0.98]">
                <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Pelanggan Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-slate-900 text-white text-sm rounded-2xl shadow-lg shadow-slate-950/5 border-l-4 border-emerald-400 flex items-center justify-between gap-3 animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="p-1 bg-emerald-500/10 rounded-lg text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="font-semibold tracking-wide text-slate-200">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="mb-6">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="relative max-w-sm">
            <input type="text" name="search" placeholder="Cari nama, email, atau telepon..." 
                   value="{{ request('search') }}"
                   class="w-full pl-10 pr-4 py-3.5 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-slate-100 focus:border-slate-400 outline-none shadow-sm transition-all text-slate-700 placeholder-slate-400 font-medium">
            <svg class="w-5 h-5 absolute left-3 top-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </form>
    </div>

    {{-- Table Container --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-slate-200/60">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-[11px] uppercase tracking-widest font-extrabold">
                        <th class="py-5 px-6 text-center w-16">
                            <input type="checkbox" id="select-all-customers" class="w-4 h-4 text-slate-800 border-slate-300 rounded-lg focus:ring-4 focus:ring-slate-100 focus:border-slate-400 transition-all cursor-pointer">
                        </th>
                        <th class="py-5 px-6">Nama Pelanggan</th>
                        <th class="py-5 px-6">Email</th>
                        <th class="py-5 px-6">No. Telepon</th>
                        <th class="py-5 px-6 text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-slate-50/40 transition-colors group">
                        <td class="py-4 px-6 text-center">
                            <input type="checkbox" value="{{ $customer->id }}" class="customer-checkbox w-4 h-4 text-slate-800 border-slate-300 rounded-lg focus:ring-4 focus:ring-slate-100 focus:border-slate-400 transition-all cursor-pointer">
                        </td>
                        
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $customer->user->name ?? 'User Terhapus' }}</div>
                        </td>
                        
                        <td class="py-4 px-6 text-sm text-slate-500 font-medium">
                            {{ $customer->user->email ?? '-' }}
                        </td>
                        
                        <td class="py-4 px-6 text-sm text-slate-500 font-semibold tracking-wide">
                            {{ $customer->phone ?? '-' }}
                        </td>
                        
                        <td class="py-4 px-6 text-right">
    <div class="flex justify-end gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
        {{-- Tombol Lihat --}}
        <a href="{{ route('admin.customers.show', $customer->id) }}" 
           class="p-2.5 text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all" 
           title="Lihat Profil">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
        </a>

        {{-- Tombol Edit --}}
        <a href="{{ route('admin.customers.edit', $customer->id) }}" 
           class="p-2.5 text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all" 
           title="Ubah Data">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
            </svg>
        </a>

        {{-- Tombol Hapus (Single Delete) --}}
        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline-block form-single-delete">
            @csrf
            @method('DELETE')
            <button type="button" 
                    class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all btn-single-delete" 
                    title="Hapus Data"
                    data-name="{{ $customer->user->name ?? 'Pelanggan ini' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a3 3 0 003 3h10M4 7h16"></path>
                </svg>
            </button>
        </form>
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center text-slate-400 text-sm font-medium italic bg-slate-50/20">
                            @if(request('search'))
                                Tidak ada pelanggan yang cocok dengan pencarian "{{ request('search') }}".
                            @else
                                Tidak ada data pelanggan saat ini.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $customers->appends(['search' => request('search')])->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('select-all-customers');
            const checkboxes = document.querySelectorAll('.customer-checkbox');
            const form = document.getElementById('bulk-delete-customers-form');
            const btnSubmitBulk = document.getElementById('btn-submit-bulk-delete');
            const countSpan = document.getElementById('customers-delete-count');
            const hiddenInput = document.getElementById('selected-customers-ids');
            const btnSingleDeletes = document.querySelectorAll('.btn-single-delete');

btnSingleDeletes.forEach(button => {
    button.addEventListener('click', function () {
        const form = this.closest('.form-single-delete');
        const customerName = this.getAttribute('data-name');
        
        Swal.fire({
            title: 'Hapus Pelanggan?',
            text: `Data pelanggan "${customerName}" akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e293b', 
            cancelButtonColor: '#f43f5e',  
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl font-bold text-sm px-5 py-3',
                cancelButton: 'rounded-xl font-bold text-sm px-5 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

            function updateVisibility() {
                const checked = document.querySelectorAll('.customer-checkbox:checked');
                if (checked.length > 0) {
                    const ids = Array.from(checked).map(cb => cb.value);
                    hiddenInput.value = ids.join(',');
                    countSpan.textContent = checked.length;
                    form.classList.remove('hidden');
                } else {
                    form.classList.add('hidden');
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
                    if (document.querySelectorAll('.customer-checkbox:checked').length === checkboxes.length && selectAll) {
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
                        text: `Sebanyak ${count} data pelanggan terpilih akan dihapus permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#1e293b', 
                        cancelButtonColor: '#f43f5e',  
                        confirmButtonText: 'Ya, Hapus Permanen',
                        cancelButtonText: 'Kembali',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl font-bold text-sm px-5 py-3',
                            cancelButton: 'rounded-xl font-bold text-sm px-5 py-3'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>