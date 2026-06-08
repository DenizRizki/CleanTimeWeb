<x-app-layout>
    <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8  min-h-screen">
        
        {{-- Navigation Header --}}
        <div class="mb-8 pl-1">
            <a href="{{ route('admin.customers.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-200 transition flex items-center gap-2 group mb-3 w-fit">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Data Pelanggan
            </a>
            <h2 class="text-3xl font-black tracking-tight text-slate-800 text-white">Perbarui Data Pelanggan</h2>
            <p class="text-slate-400 text-sm mt-0.5">Ubah data profil atau setel ulang password akun aplikasi pelanggan.</p>
        </div>

        {{-- Form Container --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 p-8 md:p-10 max-w-3xl">
            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="group">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-slate-800">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $customer->user->name ?? '') }}" required placeholder="Contoh: Budi Santoso"
                               class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-2xl py-4 px-6 text-sm focus:ring-4 focus:ring-slate-100 focus:border-slate-400 focus:bg-white outline-none transition-all text-slate-700 font-bold">
                        @error('name') <span class="text-rose-500 text-[11px] mt-2 ml-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="group">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-slate-800">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required placeholder="Contoh: 08123456789"
                               class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-2xl py-4 px-6 text-sm focus:ring-4 focus:ring-slate-100 focus:border-slate-400 focus:bg-white outline-none transition-all text-slate-700 font-bold tracking-wide">
                        @error('phone') <span class="text-rose-500 text-[11px] mt-2 ml-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="group">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-slate-800">Alamat Email Login</label>
                        <input type="email" name="email" value="{{ old('email', $customer->user->email ?? '') }}" required placeholder="budi@example.com"
                               class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-2xl py-4 px-6 text-sm focus:ring-4 focus:ring-slate-100 focus:border-slate-400 focus:bg-white outline-none transition-all text-slate-700 font-bold">
                        @error('email') <span class="text-rose-500 text-[11px] mt-2 ml-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="group">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-slate-800">Ganti Password (Opsional)</label>
                        <div class="relative mt-2">
                            <input type="password" id="password_input" name="password" placeholder="Minimal 8 karakter unik"
                                   class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl py-4 pl-6 pr-14 text-sm focus:ring-4 focus:ring-slate-100 focus:border-slate-400 focus:bg-white outline-none transition-all text-slate-700 font-semibold tracking-wide">
                            
                            <button type="button" id="password_toggle" class="absolute inset-y-0 right-0 pr-5 flex items-center text-slate-400 hover:text-slate-700 transition">
                                <svg id="eye_open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eye_close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password') <span class="text-rose-500 text-[11px] mt-2 ml-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="group">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-slate-800">Alamat Rumah</label>
                    <textarea name="address" required rows="3" placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, dan kecamatan..."
                              class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-2xl py-4 px-6 text-sm focus:ring-4 focus:ring-slate-100 focus:border-slate-400 focus:bg-white outline-none transition-all text-slate-700 font-semibold leading-relaxed">{{ old('address', $customer->address) }}</textarea>
                    @error('address') <span class="text-rose-500 text-[11px] mt-2 ml-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-800 text-white py-4 rounded-2xl font-extrabold shadow-lg shadow-slate-900/10 hover:bg-slate-900 transition-all active:scale-[0.99] text-xs tracking-widest uppercase flex items-center justify-center gap-2 group">
                        Simpan Perubahan Data
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
            const passwordInput = document.getElementById('password_input');
            const toggleButton = document.getElementById('password_toggle');
            const eyeOpen = document.getElementById('eye_open');
            const eyeClose = document.getElementById('eye_close');

            if (toggleButton && passwordInput) {
                toggleButton.addEventListener('click', function () {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        eyeOpen.classList.add('hidden');
                        eyeClose.classList.remove('hidden');
                    } else {
                        passwordInput.type = 'password';
                        eyeOpen.classList.remove('hidden');
                        eyeClose.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-app-layout>