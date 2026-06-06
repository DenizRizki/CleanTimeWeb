<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Service;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['customer.user', 'service'])->latest()->paginate(10);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        $services = Service::all();
        
        return view('admin.transactions.create', compact('customers', 'services'));
    }

   public function store(Request $request)
{
    $transaction = Transaction::create([
        'invoice_number' => 'INV-' . time(),
        'customer_id' => $request->customer_id,
        'payment_method' => $request->payment_method,
        'status' => 'Pending',
    ]);

    foreach ($request->items as $item) {
        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'service_id' => $item['service_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    return redirect()->route('admin.transactions.index')->with('success', 'Transaksi Berhasil Disimpan!');
}

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:antrian,dicuci,siap diambil,diambil'
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.transactions.index')
                         ->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid'
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'payment_status' => $request->payment_status,
            'paid_at' => $request->payment_status === 'paid' ? now() : null
        ]);

        return redirect()->route('admin.transactions.index')
                         ->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string',
        ]);

        $ids = explode(',', $request->selected_ids);

        if (count($ids) > 0) {
            $transactions = Transaction::whereIn('id', $ids)->get();
            foreach($transactions as $trans) {
                if($trans->payment_proof) {
                    Storage::disk('public')->delete($trans->payment_proof);
                }
            }

            Transaction::whereIn('id', $ids)->delete();
            
            return redirect()->route('admin.transactions.index')
                             ->with('success', count($ids) . ' data transaksi berhasil dihapus massal.');
        }

        return redirect()->route('admin.transactions.index');
    }

}