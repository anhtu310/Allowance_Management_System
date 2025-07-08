<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    public $timestamps = false;

    protected $fillable = ['code', 'status', 'created_at', 'used_at'];

    public function histories(): HasMany
    {
        return $this->hasMany(AllowanceHistory::class, 'vouchers_id');
    }

    public function history()
    {
        return $this->hasOne(AllowanceHistory::class, 'vouchers_id');
    }

}


