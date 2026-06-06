<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'price',
        'unit'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function service()
{
    return $this->belongsTo(Service::class);
}
}