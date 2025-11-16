<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    use HasFactory;
    protected $table = 'regions';
    protected $fillable = ['name', 'created_at', 'updated_at'];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'region_id');
    }
}
