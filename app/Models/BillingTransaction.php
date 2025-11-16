<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingTransaction extends Model
{
    use HasFactory;

    protected $table = 'billing_transactions';
    protected $fillable = ['customer_id', 'periode', 'bandwith', 'pemakaian', 'total', 'harga_satuan', 'harga_normal', 'created_at', 'updated_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
