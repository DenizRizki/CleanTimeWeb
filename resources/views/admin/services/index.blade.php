<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Header Section --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 text-white">Layanan & Harga</h2>
            <p class="text-slate-400 text-sm mt-1">Atur daftar harga jasa laundry Clean Time.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form id="bulk-delete-services-form" action="{{ route('admin.services.bulkDelete') }}" method="POST" class="hidden opacity-0 transition-all duration-300 transform translate-y-2">
                @csrf
                @method('DELETE')
                <input type="hidden" name="selected_ids" id="selected-services-ids">
                <button type="button" id="btn-submit-bulk-delete" 
                        class="bg-rose-50 text-rose-600 px-5 py-3 rounded-2xl font-bold text-xs border border-rose-200 hover:bg-rose-100 transition flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a3 3 0 003 3h10M4 7h16"></path>
                    </svg>
                    Hapus (<span id="services-delete-count">0</span>) Layanan
                </button>
            </form>

            <a href="{{ route('admin.services.create') }}" 
               class="bg-slate-800 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-slate-700 transition flex items-center gap-2 group">
                <svg class="w-5 h-5 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Layanan Baru
            </a>
        </div>
    </div>

    {{-- Flash Alert Success --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-slate-800 text-white text-sm rounded-2xl shadow-md border-l-4 border-emerald-400 flex items-center gap-3 animate-fade-in-down">
            <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Search Filter --}}
    <div class="mb-6">
        <form action="{{ route('admin.services.index') }}" method="GET" class="relative w-full max-w-md group">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama layanan..." 
                   class="w-full bg-white border border-slate-100 rounded-2xl py-3.5 px-5 text-sm shadow-sm focus:ring-4 focus:ring-slate-200/50 focus:border-slate-300 outline-none transition-all">
            <button type="submit" class="absolute right-4 top-3.5 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[11px] uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 text-center w-12">
                            <input type="checkbox" id="select-all-services" class="w-4 h-4 text-slate-800 border-slate-300 rounded focus:ring-slate-500">
                        </th>
                        <th class="py-5 px-4 text-center w-16">No</th>
                        <th class="py-5 px-8">Nama Layanan</th>
                        <th class="py-5 px-8 text-center w-32">Satuan</th>
                        <th class="py-5 px-8">Harga Tarif</th>
                        <th class="py-5 px-8 text-center w-48">Pembaruan Terakhir</th>
                        <th class="py-5 px-8 text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-700">
                    @forelse($services as $service)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="py-5 px-6 text-center">
                            <input type="checkbox" value="{{ $service->id }}" class="service-checkbox w-4 h-4 text-slate-800 border-slate-300 rounded focus:ring-slate-500">
                        </td>
                        <td class="py-5 px-4 text-center text-xs text-slate-400 font-medium">
                            {{ ($services->currentPage() - 1) * $services->perPage() + $loop->iteration }}
                        </td>
                        <td class="py-5 px-8">
                            <span class="text-sm font-bold text-slate-800 group-hover:text-slate-900 transition-colors">{{ $service->service_name }}</span>
                        </td>
                        <td class="py-5 px-8 text-center">
                            <span class="text-[10px] font-black bg-slate-100 text-slate-500 border border-slate-200/60 px-2.5 py-1 rounded-lg uppercase tracking-wider">
                                {{ $service->unit }}
                            </span>
                        </td>
                        <td class="py-5 px-8">
                            <span class="text-sm font-bold text-slate-900">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-5 px-8 text-center text-xs text-slate-400 font-medium">
                            {{ $service->updated_at ? date('d M Y', strtotime($service->updated_at)) : ($service->created_at ? date('d M Y', strtotime($service->created_at)) : '-') }}
                        </td>
                        <td class="py-5 px-8">
                            <div class="flex justify-end gap-2 items-center">
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="p-2 text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all" title="Ubah">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="single-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-single-delete p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-slate-50 p-4 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-400 text-sm font-medium italic">Belum ada daftar layanan laundry saat ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $services->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('select-all-services');
            const checkboxes = document.querySelectorAll('.service-checkbox');
            const formBulk = document.getElementById('bulk-delete-services-form');
            const btnSubmitBulk = document.getElementById('btn-submit-bulk-delete');
            const countSpan = document.getElementById('services-delete-count');
            const hiddenInput = document.getElementById('selected-services-ids');

            function updateVisibility() {
                const checked = document.querySelectorAll('.service-checkbox:checked');
                
                if (checked.length > 0) {
                    const ids = Array.from(checked).map(cb => cb.value);
                    hiddenInput.value = ids.join(',');
                    countSpan.textContent = checked.length;
                    
                    formBulk.classList.remove('hidden');
                    setTimeout(() => {
                        formBulk.classList.remove('opacity-0', 'translate-y-2');
                    }, 10);
                } else {
                    formBulk.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => {
                        formBulk.classList.add('hidden');
                    }, 300); 
                    hiddenInput.value = '';
                }
            }

            if (selectAll && checkboxes.length > 0) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateVisibility();
                });
            } else if (selectAll) {
                selectAll.disabled = true;
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    if (!this.checked && selectAll) selectAll.checked = false;
                    if (document.querySelectorAll('.service-checkbox:checked').length === checkboxes.length && selectAll) {
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
                        text: `Sebanyak ${count} data layanan yang dipilih akan dihapus permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f43f5e', 
                        cancelButtonColor: '#64748b',  
                        confirmButtonText: 'Ya, Hapus Semua!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formBulk.submit();
                        }
                    });
                });
            }

            const singleDeleteButtons = document.querySelectorAll('.btn-single-delete');
            singleDeleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetForm = this.closest('.single-delete-form');
                    
                    Swal.fire({
                        title: 'Hapus layanan ini?',
                        text: "Data layanan yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f43f5e',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            targetForm.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>