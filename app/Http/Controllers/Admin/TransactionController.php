<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $transactions = Transaction::with(['customer.user', 'transactionDetails.service'])
            ->where('invoice_code', 'like', "%$search%")
            ->orWhereHas('customer.user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        $services  = Service::all();

        return view('admin.transactions.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'               => 'required|exists:customers,id',
            'payment_method'            => 'required|in:cash,transfer',
            'payment_status'            => 'required|in:pending,paid',
            'transfer_proof'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'initial_clothes_condition' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'items'                     => 'required|array|min:1',
            'items.*.service_id'        => 'required|exists:services,id',
            'items.*.quantity'          => 'required|numeric|min:0.01',
            'items.*.price'             => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $dateCode = now()->format('Ymd');
            $latest   = Transaction::where('invoice_code', 'like', "LND-{$dateCode}-%")->latest()->first();
            $nextNum  = $latest
                ? str_pad(intval(substr($latest->invoice_code, -4)) + 1, 4, '0', STR_PAD_LEFT)
                : '0001';
            $invoiceCode = "LND-{$dateCode}-{$nextNum}";

            $transferProofPath = $request->hasFile('transfer_proof')
                ? $request->file('transfer_proof')->store('payment_proofs', 'public')
                : null;

            $clothesPhotoPath = null;
            if ($request->hasFile('initial_clothes_condition')) {
                $file = $request->file('initial_clothes_condition');
                
                // Mengunci ekstensi file akhir secara mutlak menjadi .jpg demi kompatibilitas React Native
                $filenameWithExt = time() . '_' . md5($file->getClientOriginalName()) . '.jpg';
                
                $file->move(public_path('storage/transactions'), $filenameWithExt);
                $clothesPhotoPath = 'transactions/' . $filenameWithExt;
            }

            $totalPrice = collect($request->items)->sum(function ($item) {
                return (float) $item['quantity'] * (float) $item['price'];
            });

            $transaction = Transaction::create([
                'invoice_code'              => $invoiceCode,
                'admin_id'                  => auth()->id() ?? 1,
                'customer_id'               => $request->customer_id,
                'total_price'               => $totalPrice,
                'status'                    => 'antrian',
                'payment_method'            => $request->payment_method,
                'payment_status'            => $request->payment_status,
                'transfer_proof'            => $transferProofPath,
                'initial_clothes_condition' => $clothesPhotoPath,
                'paid_at'                   => $request->payment_status === 'paid' ? now() : null,
            ]);

            foreach ($request->items as $item) {
                $qty      = (float) $item['quantity'];
                $price    = (float) $item['price'];
                $subtotal = $qty * $price;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'service_id'     => $item['service_id'],
                    'quantity'       => $qty,
                    'price'          => $price,
                    'subtotal'       => $subtotal,
                ]);
            }

            return redirect()
                ->route('admin.transactions.show', $transaction->id)
                ->with('success', "Transaksi {$invoiceCode} berhasil disimpan!");
        });
    }
public function completeOrder($id)
{
    $transaction = Transaction::findOrFail($id);
    if ($transaction->customer->user_id !== auth()->id()) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
    }

    if ($transaction->payment_status !== 'paid') {
        return response()->json([
            'status'  => 'error',
            'message' => 'Pembayaran belum lunas.'
        ], 422);
    }

    if ($transaction->status !== 'siap diambil') {
        return response()->json([
            'status'  => 'error',
            'message' => 'Status pesanan belum siap diambil.'
        ], 422);
    }

    $transaction->update(['status' => 'diambil']);

    return response()->json(['status' => 'success', 'message' => 'Pesanan telah diselesaikan.']);
}
   public function show($id)
{
    $transaction = Transaction::with(['customer.user', 'transactionDetails.service'])->findOrFail($id);
    return view('admin.transactions.show', compact('transaction'));
}


    public function edit(Transaction $transaction)
    {
        $transaction->load(['customer.user', 'transactionDetails.service']);
        $customers = Customer::with('user')->get();
        $services  = Service::all();

        return view('admin.transactions.edit', compact('transaction', 'customers', 'services'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'customer_id'               => 'required|exists:customers,id',
            'payment_method'            => 'required|in:cash,transfer',
            'payment_status'            => 'required|in:pending,paid',
            'initial_clothes_condition' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'items'                     => 'required|array|min:1',
            'items.*.service_id'        => 'required|exists:services,id',
            'items.*.quantity'          => 'required|numeric|min:0.01',
            'items.*.price'             => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $transaction) {
            $transaction->transactionDetails()->delete();

            $totalPrice = 0;
            foreach ($request->items as $item) {
                $qty      = (float) $item['quantity'];
                $price    = (float) $item['price'];
                $subtotal = $qty * $price;
                $totalPrice += $subtotal;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'service_id'     => $item['service_id'],
                    'quantity'       => $qty,
                    'price'          => $price,
                    'subtotal'       => $subtotal,
                ]);
            }

            $clothesPhotoPath = $transaction->initial_clothes_condition;

            if ($request->hasFile('initial_clothes_condition')) {
                if ($transaction->initial_clothes_condition) {
                    $oldFilename = basename($transaction->initial_clothes_condition);
                    $oldFilePath = public_path('storage/transactions/' . $oldFilename);
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
                
                $file = $request->file('initial_clothes_condition');                
                $filenameWithExt = time() . '_' . md5($file->getClientOriginalName()) . '.jpg';               
                $file->move(public_path('storage/transactions'), $filenameWithExt);
                $clothesPhotoPath = 'transactions/' . $filenameWithExt;
            }

            $transaction->update([
                'customer_id'               => $request->customer_id,
                'payment_method'            => $request->payment_method,
                'payment_status'            => $request->payment_status,
                'initial_clothes_condition' => $clothesPhotoPath,
                'total_price'               => $totalPrice,
                'paid_at'                   => $request->payment_status === 'paid'
                    ? ($transaction->paid_at ?? now())
                    : null,
            ]);
        });

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil dikoreksi dan foto disinkronkan!');
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:antrian,dicuci,disetrika,siap diambil,diambil',
        ]);

        $transaction->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Status pengerjaan berhasil diperbarui!');
    }

    public function updatePayment(Request $request, Transaction $transaction)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid',
        ]);

        $transaction->update([
            'payment_status' => $request->payment_status,
            'paid_at'        => $request->payment_status === 'paid'
                ? ($transaction->paid_at ?? now())
                : null,
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }

   public function updateTransferProof(Request $request, Transaction $transaction)
    {
        $request->validate([
            'transfer_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('transfer_proof')) {
            if ($transaction->transfer_proof && Storage::disk('public')->exists($transaction->transfer_proof)) {
                Storage::disk('public')->delete($transaction->transfer_proof);
            }
            $path = $request->file('transfer_proof')->store('transfer_proofs', 'public');
            $transaction->update([
                'transfer_proof' => $path,
                'payment_status' => 'paid', 
                'paid_at'        => $transaction->paid_at ?? now()
            ]);
        }

        return redirect()->back()->with('success', 'Bukti transfer dan status pembayaran berhasil diperbarui!');
    }

    public function updatePhoto(Request $request, Transaction $transaction)
    {
        $request->validate([
            'initial_clothes_condition' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($transaction->initial_clothes_condition) {
            $oldFilename = basename($transaction->initial_clothes_condition);
            $oldFilePath = public_path('storage/transactions/' . $oldFilename);
            if (file_exists($oldFilePath)) {
                @unlink($oldFilePath);
            }
        }

        $file = $request->file('initial_clothes_condition');
        $filenameWithExt = time() . '_' . md5($file->getClientOriginalName()) . '.jpg';
        
        $file->move(public_path('storage/transactions'), $filenameWithExt);
        $clothesPhotoPath = 'transactions/' . $filenameWithExt;

        $transaction->update(['initial_clothes_condition' => $clothesPhotoPath]);

        return redirect()->back()->with('success', 'Foto kondisi pakaian berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->transfer_proof && Storage::disk('public')->exists($transaction->transfer_proof)) {
            Storage::disk('public')->delete($transaction->transfer_proof);
        }

        if ($transaction->initial_clothes_condition) {
            $filename = basename($transaction->initial_clothes_condition);
            $filePath = public_path('storage/transactions/' . $filename);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $transaction->delete();

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus dari sistem.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['selected_ids' => 'required|string']);

        $ids          = array_filter(explode(',', $request->selected_ids), 'is_numeric');
        $transactions = Transaction::whereIn('id', $ids)->get();

        foreach ($transactions as $t) {
            if ($t->transfer_proof && Storage::disk('public')->exists($t->transfer_proof)) {
                Storage::disk('public')->delete($t->transfer_proof);
            }

            if ($t->initial_clothes_condition) {
                $filename = basename($t->initial_clothes_condition);
                $filePath = public_path('storage/transactions/' . $filename);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        Transaction::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', count($ids) . ' transaksi berhasil dihapus.');
    }
}