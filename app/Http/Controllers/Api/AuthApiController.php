<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class AuthApiController extends Controller
{
    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email atau password salah.'
            ], 401);
        }

        $user  = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => 'success',
            'message'      => 'Login berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ]
        ], 200);
    }

    public function getOrderDetail($id)
{
    $order = \App\Models\Transaction::with(['customer.user', 'transactionDetails.service'])
        ->where('id', $id)
        ->where('customer_id', auth()->user()->customer->id ?? null) 
        ->first();

    if (!$order) {
        return response()->json([
            'status' => 'error',
            'message' => 'Pesanan tidak ditemukan atau Anda tidak memiliki akses.'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'order' => $order
    ], 200);
}

    /**
     * — Sinkron dengan arsitektur transaction_details (bulk order)
     */
    public function getMyOrders(Request $request)
    {
        try {
            $user = $request->user();
            $customer = DB::table('customers')
                ->where('user_id', $user->id)
                ->first();

            if (!$customer) {
                return response()->json([
                    'status'  => 'success',
                    'orders'  => [],
                    'message' => 'Akun ini belum terdaftar sebagai pelanggan.'
                ], 200);
            }

            $transactions = DB::table('transactions')
                ->where('customer_id', $customer->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $formattedOrders = $transactions->map(function ($trx) {

                $dbStatus     = trim(strtolower($trx->status));
                $statusMobile = 'Antrian';

                if (in_array($dbStatus, ['dicuci', 'proses', 'washing'])) {
                    $statusMobile = 'Dicuci';
                } elseif (in_array($dbStatus, ['disetrika', 'setrika', 'ironing'])) {
                    $statusMobile = 'Disetrika';
                } elseif (in_array($dbStatus, ['siap diambil', 'siap', 'ready'])) {
                    $statusMobile = 'Siap Diambil';
                } elseif (in_array($dbStatus, ['diambil', 'selesai', 'done'])) {
                    $statusMobile = 'Selesai';
                }

                $details = DB::table('transaction_details')
                    ->where('transaction_id', $trx->id)
                    ->get();

                $items       = [];
                $totalQty    = 0;
                $serviceName = 'Layanan Laundry';
                $unitLabel   = 'Pcs/Kg';

                if ($details->count() > 0) {
                    foreach ($details as $detail) {
                        $service = DB::table('services')
                            ->where('id', $detail->service_id)
                            ->first();

                        $svcName = $service->service_name
                            ?? $service->name
                            ?? $service->nama_layanan
                            ?? 'Layanan';

                        $svcUnit = $service->unit ?? 'Pcs';

                        $items[] = [
                            'service_name' => $svcName,
                            'quantity'     => (float) $detail->quantity,
                            'unit'         => $svcUnit,
                            'price'        => (int) $detail->price,
                            'subtotal'     => (int) $detail->subtotal,
                        ];

                        $totalQty += (float) $detail->quantity;
                        $unitLabel = $svcUnit; 
                    }

                    $serviceName = $items[0]['service_name'];
                    if (count($items) > 1) {
                        $serviceName .= ' (+' . (count($items) - 1) . ' item)';
                    }
                }

                $paymentStatus = strtolower($trx->payment_status ?? 'pending');
                $paymentLabel  = $paymentStatus === 'paid' ? 'Lunas' : 'Belum Bayar';
                $clothesPhotoName = null;
                if ($trx->initial_clothes_condition) {
                    $clothesPhotoName = basename($trx->initial_clothes_condition);
                }

                return [
                    'id'                 => (string) $trx->id,
                    'invoice_number'     => $trx->invoice_code,
                    'service_name'       => $serviceName,
                    'weight_or_unit'     => number_format($totalQty, 2, '.', '') . ' ' . $unitLabel,
                    'items'              => $items,
                    'status'             => $statusMobile,
                    'payment_method'     => strtoupper($trx->payment_method ?? 'CASH'),
                    'payment_status'     => $paymentLabel,
                    'total_price'        => (int) $trx->total_price,
                    'created_at'         => Carbon::parse($trx->created_at)->translatedFormat('d M Y'),
                    'clothes_photo_url'  => $clothesPhotoName 
                        ? url('api/transactions/image/' . $clothesPhotoName) 
                        : null,
                    'transfer_proof_url' => null, 
                ];
            });

            return response()->json([
                'status' => 'success',
                'orders' => $formattedOrders
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'line'    => $e->getLine()
            ], 500);
        }
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logout berhasil.'
        ], 200);
    }
}