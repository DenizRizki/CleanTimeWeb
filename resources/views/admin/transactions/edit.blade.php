<x-app-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Koreksi / Edit Transaksi</h2>
            <p class="text-slate-500 text-sm">Mengubah Nota: <span class="font-mono font-bold text-indigo-600">{{ $transaction->invoice_code }}</span></p>
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-medium transition">
            ← Batal & Kembali
        </a>
    </div>

    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-6">
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pilih Pelanggan</label>
                    <select name="customer_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm text-slate-800 outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $transaction->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->user->name ?? $customer->name }} ({{ $customer->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Item Laundry</h3>
                        <button type="button" id="add-item-row" class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold px-3 py-1.5 rounded-lg transition">
                            + Tambah Layanan
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm" id="items-table">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 font-bold text-xs uppercase border-b border-slate-100">
                                    <th class="p-3">Nama Layanan</th>
                                    <th class="p-3 text-center w-24">Qty / Jumlah</th>
                                    <th class="p-3 text-right w-36">Harga Satuan (Rp)</th>
                                    <th class="p-3 text-right w-36">Subtotal</th>
                                    <th class="p-3 text-center w-12"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700" id="items-container">
                                @foreach($transaction->transactionDetails as $index => $detail)
                                <tr class="item-row">
                                    <td class="p-3">
                                        <select name="items[{{ $index }}][service_id]" class="service-select w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500">
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" data-price="{{ $service->price }}" {{ $detail->service_id == $service->id ? 'selected' : '' }}>
                                                    {{ $service->service_name }} ({{ $service->unit }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-3">
                                        <input type="number" name="items[{{ $index }}][quantity]" step="0.1" value="{{ $detail->quantity }}" class="quantity-input w-full text-center bg-slate-50 border border-slate-200 rounded-xl py-2 px-2 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500">
                                    </td>
                                    <td class="p-3">
                                        <input type="number" name="items[{{ $index }}][price]" value="{{ intval($detail->price) }}" class="price-input w-full text-right bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500">
                                    </td>
                                    <td class="p-3 text-right font-bold text-slate-900 align-middle">
                                        Rp <span class="subtotal-text">{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="p-3 text-center align-middle">
                                        <button type="button" class="remove-row-btn text-rose-500 hover:text-rose-700 transition font-bold text-lg">×</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white text-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between h-fit space-y-6">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Metode & Status Keuangan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs text-slate-500 font-bold uppercase mb-1">Metode Pembayaran</label>
                            <select name="payment_method" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-3 text-xs outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <option value="Cash" {{ strtolower($transaction->payment_method) == 'cash' ? 'selected' : '' }}>Cash (Tunai)</option>
                                <option value="Transfer" {{ strtolower($transaction->payment_method) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-slate-500 font-bold uppercase mb-1">Status Pembayaran</label>
                            <select name="payment_status" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-3 text-xs outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <option value="Pending" {{ strtolower($transaction->payment_status) == 'pending' ? 'selected' : '' }}>PENDING (Belum Bayar)</option>
                                <option value="Success" {{ strtolower($transaction->payment_status) == 'success' ? 'selected' : '' }}>SUCCESS (Lunas)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Estimasi Total Baru</p>
                    <h3 class="text-3xl font-black text-emerald-600 mt-1">Rp <span id="grand-total-text">{{ number_format($transaction->total_price, 0, ',', '.') }}</span></h3>
                    
                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl mt-4 transition text-xs shadow-lg shadow-emerald-500/10">
                         Simpan Perubahan Nota
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('items-container');
            const addBtn = document.getElementById('add-item-row');
            let rowIndex = {{ $transaction->transactionDetails->count() }};

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            function calculateTotals() {
                let grandTotal = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const subtotal = qty * price;
                    grandTotal += subtotal;
                    
                    row.querySelector('.subtotal-text').textContent = formatRupiah(subtotal);
                });
                document.getElementById('grand-total-text').textContent = formatRupiah(grandTotal);
            }

            container.addEventListener('change', function (e) {
                if (e.target.classList.contains('service-select')) {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const price = selectedOption.getAttribute('data-price');
                    const row = e.target.closest('.item-row');
                    row.querySelector('.price-input').value = parseInt(price) || 0;
                    calculateTotals();
                }
            });

            container.addEventListener('input', function (e) {
                if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
                    calculateTotals();
                }
            });

            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-row-btn')) {
                    const rows = document.querySelectorAll('.item-row');
                    if (rows.length > 1) {
                        e.target.closest('.item-row').remove();
                        calculateTotals();
                    } else {
                        alert('Minimal harus ada 1 item layanan dalam transaksi!');
                    }
                }
            });

            addBtn.addEventListener('click', function () {
                const firstRow = document.querySelector('.item-row');
                if (!firstRow) return;
                const templateRow = firstRow.cloneNode(true);
                const serviceSelect = templateRow.querySelector('.service-select');
                serviceSelect.selectedIndex = 0;
                const initialPrice = serviceSelect.options[0].getAttribute('data-price');
                templateRow.querySelector('.quantity-input').value = 1;
                templateRow.querySelector('.price-input').value = parseInt(initialPrice) || 0;
                templateRow.querySelector('.subtotal-text').textContent = '0';
                templateRow.querySelector('.service-select').setAttribute('name', `items[${rowIndex}][service_id]`);
                templateRow.querySelector('.quantity-input').setAttribute('name', `items[${rowIndex}][quantity]`);
                templateRow.querySelector('.price-input').setAttribute('name', `items[${rowIndex}][price]`);

                container.appendChild(templateRow);
                rowIndex++;
                calculateTotals();
            });
        });
    </script>
</x-app-layout>