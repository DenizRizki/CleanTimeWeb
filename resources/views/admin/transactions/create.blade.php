<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="mb-8">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800">Pencatatan Transaksi</h2>
                <p class="text-slate-400 text-sm mt-1">Loket Kasir Utama — Pembuatan invoice laundry baru masuk (Bulk Order).</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ strtoupper(date('d M Y')) }}
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.transactions.store') }}" method="POST" enctype="multipart/form-data" id="main-transaction-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- KOLOM KIRI --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- Card Info Pelanggan & Pembayaran --}}
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Pilih Pelanggan --}}
                    <div class="md:col-span-2 relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Pelanggan Terdaftar</label>
                        <div class="relative">
                            <input type="text" id="customer_search" placeholder="Cari nama atau nomor telepon pelanggan..."
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm outline-none focus:bg-white focus:ring-4 focus:ring-slate-100 transition-all font-medium text-slate-700">
                            <div class="absolute right-4 top-3.5 text-slate-400 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <input type="hidden" name="customer_id" id="customer_id">
                        <div id="customer_dropdown" class="hidden absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl max-h-60 overflow-y-auto p-2 space-y-1">
                            <div class="text-xs text-slate-400 p-2 italic no-results-msg hidden">Pelanggan tidak ditemukan...</div>
                            @foreach($customers as $customer)
                                <div class="customer-item p-3 text-sm rounded-xl cursor-pointer hover:bg-slate-50 transition-colors text-slate-700 font-medium flex justify-between"
                                     data-id="{{ $customer->id }}"
                                     data-search="{{ strtolower($customer->user->name . ' ' . $customer->phone) }}">
                                    <span>{{ $customer->user->name }}</span>
                                    <span class="text-xs text-slate-400 font-mono">{{ $customer->phone }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm outline-none focus:ring-4 focus:ring-slate-100 transition-all">
                            <option value="cash">Tunai / Cash</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    {{-- Status Pembayaran --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Status Pembayaran</label>
                        <select name="payment_status" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-5 text-sm outline-none focus:ring-4 focus:ring-slate-100 transition-all">
                            <option value="pending">Belum Bayar (Pending)</option>
                            <option value="paid">Lunas (Paid)</option>
                        </select>
                    </div>

                    {{-- Upload Bukti Transfer --}}
                    <div id="transfer-proof-wrapper" class="hidden md:col-span-2 space-y-2">
                        <label id="transfer-proof-label" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Upload Bukti Transfer <span id="transfer-proof-optional" class="normal-case font-normal text-slate-400">(Opsional — bisa diupload nanti)</span>
                        </label>
                        <div class="relative w-full bg-slate-50/50 border border-dashed border-slate-200 rounded-2xl p-4 flex items-center gap-4" id="transfer-proof-box">
                            <input type="file" name="transfer_proof" id="transfer_proof" accept="image/*"
                                   class="text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer w-full">
                        </div>
                    </div>
                </div>

                {{-- Card Layanan --}}
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">Pilih Layanan Laundry (Satuan / Kiloan)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div class="md:col-span-1 relative">
                            <label class="block text-xs font-bold text-slate-400 mb-1.5">Nama Paket / Layanan</label>
                            <div class="relative">
                                <input type="text" id="service_search" placeholder="Pilih paket..."
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm outline-none focus:bg-white focus:ring-2 focus:ring-slate-200 transition-all font-medium text-slate-700">
                            </div>
                            <input type="hidden" id="service_id">
                            <input type="hidden" id="service_selected_name">
                            <input type="hidden" id="service_selected_price">
                            <input type="hidden" id="service_selected_unit">
                            <div id="service_dropdown" class="hidden absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2 space-y-1">
                                <div class="text-xs text-slate-400 p-2 italic no-results-msg hidden">Layanan tidak ditemukan...</div>
                                @foreach($services as $service)
                                    <div class="service-item p-2.5 text-xs rounded-lg cursor-pointer hover:bg-slate-50 transition-colors text-slate-700 font-medium flex flex-col gap-0.5"
                                         data-id="{{ $service->id }}"
                                         data-name="{{ $service->service_name }}"
                                         data-price="{{ $service->price }}"
                                         data-unit="{{ $service->unit }}"
                                         data-search="{{ strtolower($service->service_name) }}">
                                        <span class="font-bold">{{ $service->service_name }}</span>
                                        <span class="text-slate-400">Rp{{ number_format($service->price, 0, ',', '.') }}/{{ $service->unit }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-1.5">
                                Jumlah / Kuantitas (<span id="unit-indicator" class="lowercase">unit</span>)
                            </label>
                            <input type="number" id="qty_selector" step="0.01" min="0.01" value="1.00"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm outline-none focus:bg-white focus:ring-2 focus:ring-slate-200 transition-all">
                        </div>

                        <div>
                            <button type="button" id="btn-add-item"
                                    class="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition flex items-center justify-center gap-2 shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah ke Struk
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tabel Cart --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700">Daftar Cucian yang Dimasukkan</h3>
                        <span class="text-xs font-bold bg-slate-100 text-slate-500 px-3 py-1 rounded-full" id="item-badge-count">0 Item</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left" id="cart-table">
                            <thead>
                                <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase tracking-widest font-bold border-b border-slate-100">
                                    <th class="py-4 px-6">Nama Layanan</th>
                                    <th class="py-4 px-4 text-center">Satuan</th>
                                    <th class="py-4 px-4 text-right">Harga</th>
                                    <th class="py-4 px-4 text-center">Qty</th>
                                    <th class="py-4 px-4 text-right">Subtotal</th>
                                    <th class="py-4 px-6 text-center w-16">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-slate-700 text-sm">
                                <tr id="empty-cart-row">
                                    <td colspan="6" class="py-12 text-center text-slate-400 italic">
                                        Belum ada item laundry yang dipilih. Silakan masukkan item di atas.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- KANAN --}}
           <div class="flex flex-col gap-6">

    {{-- Foto Kondisi Pakaian --}}
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100">
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Foto Kondisi Bundel Pakaian</label>
        <p class="text-slate-400 text-[11px] mb-4">Wajib memotret keadaan pakaian utuh saat diterima untuk menghindari klaim sepihak.</p>
        <div class="relative w-full bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-4 text-center hover:bg-slate-100/50 transition-all">
            <input type="file" name="initial_clothes_condition" accept="image/*"
                   class="text-sm text-slate-600 file:mr-auto file:mb-2 file:block file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-700 cursor-pointer w-full">
        </div>
    </div>

    {{-- Kalkulasi & Submit --}}
    <div class="bg-white text-slate-700 rounded-[2rem] p-8 shadow-xl border border-slate-200 flex flex-col justify-between h-72 relative overflow-hidden">
        
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-200/40 rounded-full blur-2xl"></div>

        <div>
            <h4 class="text-lg font-bold text-slate-900">Kalkulasi Tagihan</h4>
            <p class="text-slate-500 text-xs mt-0.5">Sistem kas otomatis real-time.</p>

            <div class="mt-6 flex justify-between text-sm border-b border-slate-100 pb-4">
                <span class="text-slate-500">Data tercatat</span>
            </div>
        </div>

        <div>
            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">
                Grand Total Akhir
            </p>

            <h3 class="text-4xl font-black text-emerald-600 mt-1" id="grand-total-display">
                Rp 0
            </h3>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-2xl mt-5 transition flex items-center justify-center gap-2 text-sm shadow-lg shadow-indigo-300/40 group">
                
                Finalisasi & Cetak Struk

                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </button>
        </div>

    </div>
</div>

        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerSearch   = document.getElementById('customer_search');
        const customerDropdown = document.getElementById('customer_dropdown');
        const customerHidden   = document.getElementById('customer_id');
        const customerItems    = document.querySelectorAll('.customer-item');

        const serviceSearch    = document.getElementById('service_search');
        const serviceDropdown  = document.getElementById('service_dropdown');
        const serviceHiddenId  = document.getElementById('service_id');
        const serviceHiddenName  = document.getElementById('service_selected_name');
        const serviceHiddenPrice = document.getElementById('service_selected_price');
        const serviceHiddenUnit  = document.getElementById('service_selected_unit');
        const serviceItems     = document.querySelectorAll('.service-item');

        const paymentMethod       = document.getElementById('payment_method');
        const transferProofWrapper = document.getElementById('transfer-proof-wrapper');
        const transferProofInput  = document.getElementById('transfer_proof');

        const btnAddItem       = document.getElementById('btn-add-item');
        const qtySelector      = document.getElementById('qty_selector');
        const unitIndicator    = document.getElementById('unit-indicator');
        const cartTableBody    = document.querySelector('#cart-table tbody');
        const emptyCartRow     = document.getElementById('empty-cart-row');
        const itemBadgeCount   = document.getElementById('item-badge-count');
        const grandTotalDisplay = document.getElementById('grand-total-display');
        const mainForm         = document.getElementById('main-transaction-form');
        let cart = [];

        customerSearch.addEventListener('focus', () => customerDropdown.classList.remove('hidden'));
        customerSearch.addEventListener('input', function () {
            const val = this.value.toLowerCase().trim();
            let count = 0;
            customerItems.forEach(item => {
                const match = item.getAttribute('data-search').includes(val);
                item.classList.toggle('hidden', !match);
                if (match) count++;
            });
            customerDropdown.querySelector('.no-results-msg').classList.toggle('hidden', count > 0);
        });
        customerItems.forEach(item => {
            item.addEventListener('click', function () {
                customerHidden.value  = this.getAttribute('data-id');
                customerSearch.value  = this.querySelector('span:first-child').textContent;
                customerDropdown.classList.add('hidden');
            });
        });

        serviceSearch.addEventListener('focus', () => serviceDropdown.classList.remove('hidden'));
        serviceSearch.addEventListener('input', function () {
            const val = this.value.toLowerCase().trim();
            let count = 0;
            serviceItems.forEach(item => {
                const match = item.getAttribute('data-search').includes(val);
                item.classList.toggle('hidden', !match);
                if (match) count++;
            });
            serviceDropdown.querySelector('.no-results-msg').classList.toggle('hidden', count > 0);
        });
        serviceItems.forEach(item => {
            item.addEventListener('click', function () {
                serviceHiddenId.value    = this.getAttribute('data-id');
                serviceHiddenName.value  = this.getAttribute('data-name');
                serviceHiddenPrice.value = this.getAttribute('data-price');
                serviceHiddenUnit.value  = this.getAttribute('data-unit');
                serviceSearch.value      = this.getAttribute('data-name');
                unitIndicator.textContent = this.getAttribute('data-unit');
                serviceDropdown.classList.add('hidden');
            });
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#customer_search') && !e.target.closest('#customer_dropdown'))
                customerDropdown.classList.add('hidden');
            if (!e.target.closest('#service_search') && !e.target.closest('#service_dropdown'))
                serviceDropdown.classList.add('hidden');
        });

        const paymentStatus    = document.querySelector('[name="payment_status"]');
        const transferProofBox = document.getElementById('transfer-proof-box');
        const transferProofOptional = document.getElementById('transfer-proof-optional');
        const transferProofLabel    = document.getElementById('transfer-proof-label');

        function updateTransferProof() {
            const isTransfer = paymentMethod.value === 'transfer';
            const isPaid     = paymentStatus.value === 'paid';

            transferProofWrapper.classList.toggle('hidden', !isTransfer);

            if (!isTransfer) {
                transferProofInput.removeAttribute('required');
                transferProofInput.value = '';
                return;
            }

            if (isPaid) {
                transferProofInput.setAttribute('required', 'required');
                transferProofLabel.className = 'block text-xs font-bold uppercase tracking-wider text-rose-500 mb-2';
                transferProofOptional.textContent = '*';
                transferProofBox.className = 'relative w-full bg-rose-50/30 border border-dashed border-rose-200 rounded-2xl p-4 flex items-center gap-4';
                transferProofInput.className = 'text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-100 file:text-rose-700 hover:file:bg-rose-200 cursor-pointer w-full';
            } else {
                transferProofInput.removeAttribute('required');
                transferProofLabel.className = 'block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2';
                transferProofOptional.textContent = '(Opsional — bisa diupload nanti)';
                transferProofBox.className = 'relative w-full bg-slate-50/50 border border-dashed border-slate-200 rounded-2xl p-4 flex items-center gap-4';
                transferProofInput.className = 'text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer w-full';
            }
        }

        paymentMethod.addEventListener('change', updateTransferProof);
        paymentStatus.addEventListener('change', updateTransferProof);
        btnAddItem.addEventListener('click', function () {
            if (!customerHidden.value) {
                Swal.fire({ icon: 'warning', title: 'Pelanggan Belum Dipilih',
                    text: 'Silakan tentukan identitas pelanggan terlebih dahulu!',
                    confirmButtonColor: '#1e293b' });
                return;
            }
            if (!serviceHiddenId.value) {
                Swal.fire({ icon: 'warning', title: 'Paket Belum Dipilih',
                    text: 'Silakan tentukan jenis paket layanan yang ingin diinput.',
                    confirmButtonColor: '#1e293b' });
                return;
            }

            const qty = parseFloat(qtySelector.value) || 0;
            if (qty <= 0) {
                Swal.fire({ icon: 'error', title: 'Kuantitas Tidak Valid',
                    text: 'Jumlah tidak boleh kosong atau minus!',
                    confirmButtonColor: '#f43f5e' });
                return;
            }

            const serviceId   = serviceHiddenId.value;
            const serviceName = serviceHiddenName.value;
            const price       = parseFloat(serviceHiddenPrice.value);
            const unit        = serviceHiddenUnit.value;

            const existing = cart.find(i => i.serviceId === serviceId);
            if (existing) {
                existing.qty      = parseFloat((existing.qty + qty).toFixed(2));
                existing.subtotal = existing.qty * existing.price;
            } else {
                cart.push({ serviceId, serviceName, price, unit, qty, subtotal: price * qty });
            }

            qtySelector.value        = '1.00';
            serviceSearch.value      = '';
            serviceHiddenId.value    = '';
            unitIndicator.textContent = 'unit';

            renderCart();

            Swal.fire({ toast: true, position: 'bottom-end', icon: 'success',
                title: `${serviceName} dimasukkan ke struk`,
                showConfirmButton: false, timer: 1500 });
        });

        function renderCart() {
            cartTableBody.querySelectorAll('tr:not(#empty-cart-row)').forEach(tr => tr.remove());

            if (cart.length === 0) {
                emptyCartRow.classList.remove('hidden');
            } else {
                emptyCartRow.classList.add('hidden');
                cart.forEach((item, index) => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-50/50 transition-colors border-b border-slate-100';
                    tr.innerHTML = `
                        <td class="py-4 px-6 font-bold text-slate-800">
                            ${item.serviceName}
                            <input type="hidden" name="items[${index}][service_id]" value="${item.serviceId}">
                            <input type="hidden" name="items[${index}][quantity]"   value="${item.qty}">
                            <input type="hidden" name="items[${index}][price]"      value="${item.price}">
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="text-xs bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded-md uppercase">${item.unit}</span>
                        </td>
                        <td class="py-4 px-4 text-right font-medium text-slate-600">Rp ${item.price.toLocaleString('id-ID')}</td>
                        <td class="py-4 px-4 text-center font-bold text-slate-800">${item.qty}</td>
                        <td class="py-4 px-4 text-right font-bold text-slate-900">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                        <td class="py-4 px-6 text-center">
                            <button type="button" class="text-rose-500 hover:text-rose-700 text-xs font-bold btn-remove" data-id="${item.serviceId}">Hapus</button>
                        </td>`;
                    cartTableBody.appendChild(tr);
                });
            }
            calculateGrandTotal();
        }

        cartTableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-remove')) {
                cart = cart.filter(i => i.serviceId !== e.target.getAttribute('data-id'));
                renderCart();
            }
        });

        function calculateGrandTotal() {
            const total = cart.reduce((sum, i) => sum + i.subtotal, 0);
            itemBadgeCount.textContent  = `${cart.length} Jenis Cucian`;
            grandTotalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        mainForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (cart.length === 0) {
                Swal.fire({ icon: 'error', title: 'Struk Belum Berisi!',
                    text: 'Tambahkan minimal 1 layanan ke dalam keranjang terlebih dahulu.',
                    confirmButtonColor: '#f43f5e' });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Simpan?',
                text: 'Pastikan semua item, timbangan, dan foto sudah benar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Simpan & Cetak!',
                cancelButtonText: 'Periksa Kembali',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) mainForm.submit();
            });
        });
    });
    </script>
</x-app-layout>