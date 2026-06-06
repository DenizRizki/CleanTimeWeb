<?php

use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\AuthApiController;

Route::post('/login', [AuthApiController::class, 'login']);
Route::get('/transactions/image/{filename}', function ($filename) {
    $basePath = storage_path('app');
    $directory = new RecursiveDirectoryIterator($basePath);
    $iterator = new RecursiveIteratorIterator($directory);
    $foundPath = null;
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getFilename() === $filename) {
            $foundPath = $file->getRealPath();
            break;
        }
    }

    if (!$foundPath) return response()->json(['message' => 'Not found'], 404);
    if (ob_get_length()) ob_end_clean();

    header('Content-Type: image/jpeg');
    header('Content-Length: ' . filesize($foundPath));
    readfile($foundPath);
    exit; 
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('customers', \App\Http\Controllers\Admin\CustomerController::class);
    Route::post('/testing-services', [ServiceController::class, 'store']);
    Route::get('/testing-services', [ServiceController::class, 'index']);
    Route::patch('/testing-services/{id}', [ServiceController::class, 'update']);
    Route::put('/testing-services/{id}', [ServiceController::class, 'update']);
    Route::delete('/testing-services/{id}', [ServiceController::class, 'destroy']);

    Route::get('/user', function (Request $request) {
        $user = $request->user();
        $customer = $user->customer; 

        return response()->json([
            'status' => 'success',
            'user' => [
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $customer ? $customer->phone : '', 
            ]
        ]);
    });

    Route::post('/update-profile', [UserProfileController::class, 'update']); 
    
    // API Orders
    Route::get('/my-orders', [AuthApiController::class, 'getMyOrders']);
    Route::get('/my-orders/{id}', [AuthApiController::class, 'getOrderDetail']);
    Route::post('/my-orders/{id}/complete', function (Request $request, $id) {
        
        $user = $request->user();
        $transaction = \App\Models\Transaction::where('id', $id)
            ->where('customer_id', $user->customer->id ?? null) 
            ->first();
        
        if (!$transaction) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Nota tidak ditemukan atau bukan milik Anda'
            ], 404);
        }

        $transaction->status = 'diambil'; 
        $transaction->save();

        return response()->json([
            'status' => 'success', 
            'message' => 'Pesanan berhasil diselesaikan dan masuk riwayat.'
        ]);
    });
});