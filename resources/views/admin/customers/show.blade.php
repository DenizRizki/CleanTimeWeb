<x-app-layout>
    <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
        
        {{-- Navigation Header --}}
        <div class="mb-8 pl-1 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.customers.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-200 transition flex items-center gap-2 group mb-3 w-fit">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Data Pelanggan
                </a>
                <h2 class="text-3xl font-black tracking-tight text-slate-800 text-white">Profil Pelanggan</h2>
                <p class="text-slate-400 text-sm mt-0.5">Detail informasi akun dan hak akses login aplikasi.</p>
            </div>
            
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="bg-white border border-slate-200 text-slate-700 px-5 py-3.5 rounded-2xl font-extrabold text-xs tracking-widest uppercase hover:bg-slate-50 hover:text-slate-900 transition flex items-center gap-2 w-fit shadow-sm active:scale-[0.98]">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Ubah Data Profil
            </a>
        </div>

        {{-- Profile Card Container --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 p-8 md:p-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 divide-y md:divide-y-0 md:divide-x divide-slate-100">
                <div class="space-y-6 md:pr-8">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Nama Lengkap</span>
                        <p class="text-xl font-black text-slate-800 mt-1.5">{{ $customer->user->name ?? 'Tanpa Nama' }}</p>
                    </div>
                    
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Alamat Email Login</span>
                        <p class="text-sm font-bold text-slate-600 mt-1.5 tracking-wide">{{ $customer->user->email ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-2">Hak Akses Aplikasi</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[11px] font-extrabold bg-slate-100 text-slate-800 uppercase tracking-widest">
                            {{ $customer->user->role ?? 'Customer' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-6 pt-6 md:pt-0 md:pl-8">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">No. Telepon / WhatsApp</span>
                        <p class="text-sm font-bold text-slate-600 mt-1.5 tracking-wider">{{ $customer->phone ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Alamat Rumah</span>
                        <p class="text-sm font-semibold text-slate-600 mt-1.5 leading-relaxed bg-slate-50/60 rounded-2xl p-4 border border-slate-100">
                            {{ $customer->address ?? 'Alamat belum diisi.' }}
                        </p>
                    </div>
                    
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Bergabung Sejak</span>
                        <p class="text-sm font-bold text-slate-500 mt-1.5">
                            {{ $customer->created_at ? $customer->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>