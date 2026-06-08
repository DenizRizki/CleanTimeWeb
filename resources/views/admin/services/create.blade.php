<x-app-layout>
    <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="mb-8 pl-1">
            <a href="{{ route('admin.services.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-200 transition flex items-center gap-2 group mb-3 w-fit">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Layanan & Harga
            </a>
            <h2 class="text-3xl font-black tracking-tight text-slate-800 text-white">Tambah Layanan Baru</h2>
            <p class="text-slate-400 text-sm mt-0.5">Buat kategori dan tarif jasa laundry baru untuk Clean Time.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-10 max-w-3xl">
            <form id="serviceForm" action="{{ route('admin.services.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="group">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-slate-800">
                        Nama Layanan
                    </label>
                    <input type="text" id="service_name" name="service_name" value="{{ old('service_name') }}" required autofocus placeholder="Contoh: Cuci Kering Setrika Kilat"
                           class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-2xl py-4 px-6 text-sm focus:ring-4 focus:ring-slate-100 focus:border-slate-400 focus:bg-white outline-none transition-all text-slate-700 font-bold">
                    @error('service_name') 
                        <span class="text-rose-500 text-[11px] mt-2 ml-1 block font-semibold">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="group">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-slate-800">
                            Jenis Satuan / Kategori
                        </label>
                        <div class="relative">
                            <select id="unit" name="unit" required 
                                    class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-2xl py-4 px-6 text-sm focus:ring-4 focus:ring-slate-100 focus:border-slate-400 focus:bg-white outline-none transition-all text-slate-700 font-bold cursor-pointer appearance-none">
                                <option value="" disabled {{ old('unit') ? '' : 'selected' }}>-- Pilih Jenis --</option>
                                <option value="Kg" {{ old('unit') == 'Kg' ? 'selected' : '' }}>KILOAN (Per Kg)</option>
                                <option value="Pcs" {{ old('unit') == 'Pcs' ? 'selected' : '' }}>SATUAN (Per Pcs)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 pt-2 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('unit') 
                            <span class="text-rose-500 text-[11px] mt-2 ml-1 block font-semibold">{{ $message }}</span> 
                        @enderror
                    </div>
                    
                    <div class="group">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-slate-800">
                            Harga Tarif (Rp)
                        </label>
                        <div class="relative mt-2">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm font-bold">Rp</span>
                            </div>
                            <input type="text" id="price_display" value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}" placeholder="0" required
                                   class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl py-4 pl-14 pr-6 text-sm focus:ring-4 focus:ring-slate-100 focus:border-slate-400 focus:bg-white outline-none transition-all text-slate-800 font-black tracking-wider">
                            <input type="hidden" name="price" id="price_actual" value="{{ old('price') }}">
                        </div>
                        @error('price') 
                            <span class="text-rose-500 text-[11px] mt-2 ml-1 block font-semibold">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="pt-4">
                    <button type="submit" id="submitBtn" disabled
                            class="w-full bg-slate-200 text-slate-400 py-4 rounded-2xl font-extrabold shadow-sm transition-all text-xs tracking-widest uppercase flex items-center justify-center gap-2 group cursor-not-allowed opacity-70">
                        Simpan Layanan Baru
                        <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('serviceForm');
            const submitBtn = document.getElementById('submitBtn');
            const priceDisplay = document.getElementById('price_display');
            const priceActual = document.getElementById('price_actual');
            
            const requiredInputs = [
                document.getElementById('service_name'),
                document.getElementById('unit'),
                priceActual
            ];

            if (priceDisplay && priceActual) {
                priceDisplay.addEventListener('input', function() {
                    let value = this.value.replace(/[^0-9]/g, '');
                    
                    priceActual.value = value ? parseInt(value, 10) : '';
                    
                    if (value) {
                        this.value = parseInt(value, 10).toLocaleString('id-ID');
                    } else {
                        this.value = '';
                    }
                    
                    checkFormValidity();
                });
            }

            function checkFormValidity() {
                let isFormValid = true;
                
                requiredInputs.forEach(input => {
                    if (!input || !input.value.trim()) {
                        isFormValid = false;
                    }
                });

                if (isFormValid) {
                    submitBtn.removeAttribute('disabled');
                    submitBtn.classList.remove('bg-slate-200', 'text-slate-400', 'cursor-not-allowed', 'opacity-70', 'shadow-sm');
                    submitBtn.classList.add('bg-slate-800', 'text-white', 'hover:bg-slate-900', 'active:scale-[0.99]', 'shadow-lg', 'shadow-slate-900/10');
                } else {
                    submitBtn.setAttribute('disabled', 'disabled');
                    submitBtn.classList.add('bg-slate-200', 'text-slate-400', 'cursor-not-allowed', 'opacity-70', 'shadow-sm');
                    submitBtn.classList.remove('bg-slate-800', 'text-white', 'hover:bg-slate-900', 'active:scale-[0.99]', 'shadow-lg', 'shadow-slate-900/10');
                }
            }

            ['service_name', 'unit'].forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('input', checkFormValidity);
                    element.addEventListener('change', checkFormValidity);
                }
            });

            checkFormValidity();
        });
    </script>
</x-app-layout>