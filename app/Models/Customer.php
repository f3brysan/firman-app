<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';    
    protected $fillable = ['id_pelanggan', 'nama', 'layanan', 'region', 'created_at', 'updated_at'];

    public function billingTransactions()
    {
        return $this->hasMany(BillingTransaction::class, 'customer_id');
    }
}
