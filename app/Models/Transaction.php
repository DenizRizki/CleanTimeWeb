<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_code',
        'admin_id',
        'customer_id',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'payment_proof',
        'transfer_proof',
        'initial_clothes_condition',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relasi ke detail baris item (multi-layanan per transaksi)
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
   public function details() {
    return $this->hasMany(TransactionDetail::class, 'transaction_id');
}

    public function items()
    {
    return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    
}