<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'total_allowance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(AllowanceRequest::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(AllowanceHistory::class);
    }

//    public function vouchers()
//    {
//        return $this->hasManyThrough(
//            \App\Models\Voucher::class,
//            \App\Models\AllowanceHistory::class,
//            'customers_id',   // foreign key on AllowanceHistory
//            'id',             // local key on Voucher
//            'id',             // local key on Customer
//            'vouchers_id'     // foreign key on AllowanceHistory
//        )->distinct();
//    }
}
